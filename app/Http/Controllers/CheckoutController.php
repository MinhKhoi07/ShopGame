<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\GameKey;
use App\Models\Invoice;
use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Hiển thị trang thanh toán
     */
    public function index()
    {
        // Lấy items trong giỏ
        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng trống! Vui lòng thêm game trước khi thanh toán.');
        }

        // Tính toán
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->getSubtotal();
        }
        
        $tax = $subtotal * 0.1; // 10% VAT
        $total = $subtotal + $tax;

        // Lấy thông tin user nếu đã đăng nhập
        $user = Auth::user();

        return view('checkout.index', compact('cartItems', 'subtotal', 'tax', 'total', 'user'));
    }

    /**
     * Xử lý thanh toán
     */
    public function process(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'payment_method' => 'required|in:bank_transfer,momo,zalopay,credit_card',
        ]);

        // Lấy items trong giỏ
        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng trống!');
        }

        DB::beginTransaction();
        try {
            // Tính tổng tiền
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item->getSubtotal();
            }
            $tax = $subtotal * 0.1;
            $total = $subtotal + $tax;

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'payment_method' => $validated['payment_method'],
                'status' => 'pending', // pending, completed, cancelled
            ]);

            // Tạo order items và gán game keys
            foreach ($cartItems as $cartItem) {
                // Tìm game key khả dụng
                $gameKey = GameKey::where('game_id', $cartItem->game_id)
                    ->where('status', 'available')
                    ->first();

                // Nếu không có key khả dụng, tự động tạo key mới
                if (!$gameKey) {
                    $gameKey = $this->generateGameKey($cartItem->game_id, $cartItem->game->name);
                }

                // Nếu là game free, đánh dấu ngay là sold (không cần thanh toán)
                // Nếu là game trả tiền, đánh dấu reserved (chờ thanh toán)
                $gameKey->update([
                    'status' => $cartItem->game->is_free ? 'sold' : 'reserved',
                ]);

                // Tạo order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'game_id' => $cartItem->game_id,
                    'game_key_id' => $gameKey->id,
                    'price' => $cartItem->price,
                ]);
            }

            // Xóa giỏ hàng
            $this->clearCart();

            // Nếu toàn bộ đơn là game free, coi như đã thanh toán xong
            $hasPaidGames = $cartItems->contains(function ($item) {
                return !$item->game->is_free;
            });

            if (!$hasPaidGames) {
                // Toàn game free: cập nhật status = paid ngay
                $order->update(['status' => 'paid']);
                
                // Thêm game vào thư viện
                $this->addGamesToLibrary($order);
                
                DB::commit();
                return redirect()->route('checkout.success', $order->id)
                    ->with('success', 'Nhận game miễn phí thành công!');
            }

            // Với bank_transfer: hiển thị QR để khách thanh toán
            if ($validated['payment_method'] === 'bank_transfer') {
                DB::commit();

                // Tạo dữ liệu QR
                $transferContent = \App\Services\VietQRService::generateTransferContent($order->id);
                $qrUrl = \App\Services\VietQRService::generateQRUrl($total, $transferContent);
                $bankInfo = \App\Services\VietQRService::getBankInfo();

                return view('checkout.qr', [
                    'order' => $order,
                    'qrUrl' => $qrUrl,
                    'bankInfo' => $bankInfo,
                    'amount' => $total,
                    'transferContent' => $transferContent,
                ]);
            }

            // Các phương thức khác: coi như thanh toán ngay
            $order->update(['status' => 'paid']);
            
            // Thêm game vào thư viện
            $this->addGamesToLibrary($order);

            // Commit transaction
            DB::commit();

            // Redirect đến trang success
            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Trang thông báo thanh toán thành công
     */
    public function success($orderId)
    {
        $order = Order::with(['orderItems.game', 'orderItems.gameKey', 'invoice', 'user'])
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            return redirect()->route('home')
                ->with('error', 'Không tìm thấy đơn hàng!');
        }

        // Kiểm tra quyền xem đơn hàng
        if (Auth::check() && $order->user_id !== Auth::id()) {
            return redirect()->route('home')
                ->with('error', 'Bạn không có quyền xem đơn hàng này!');
        }

        return view('checkout.success', compact('order'));
    }
    
    /**
     * Kiểm tra trạng thái đơn hàng (AJAX)
     */
    public function checkStatus($orderId)
    {
        $order = Order::find($orderId);
        
        if (!$order) {
            return response()->json(['error' => 'Không tìm thấy đơn hàng'], 404);
        }
        
        // Kiểm tra quyền
        if (Auth::check() && $order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Không có quyền truy cập'], 403);
        }
        
        return response()->json([
            'status' => $order->status,
            'message' => $this->getStatusMessage($order->status)
        ]);
    }
    
    /**
     * Lấy thông báo trạng thái
     */
    private function getStatusMessage($status)
    {
        return match($status) {
            'pending' => 'Đang chờ xác nhận thanh toán',
            'paid' => 'Thanh toán thành công',
            'cancelled' => 'Đơn hàng đã bị hủy',
            default => 'Trạng thái không xác định'
        };
    }
    
    /**
     * User hủy đơn hàng của mình
     */
    public function cancel($orderId)
    {
        try {
            $order = Order::with('orderItems')->findOrFail($orderId);
            
            // Kiểm tra quyền
            if (!Auth::check() || $order->user_id !== Auth::id()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Bạn không có quyền hủy đơn hàng này!');
            }
            
            // Chỉ cho phép hủy đơn pending
            if ($order->status !== 'pending') {
                return redirect()->route('cart.index')
                    ->with('error', 'Chỉ có thể hủy đơn hàng đang chờ xác nhận!');
            }
            
            // Hoàn lại game keys về available
            foreach ($order->orderItems as $item) {
                if ($item->game_key_id) {
                    GameKey::where('id', $item->game_key_id)
                        ->update(['status' => 'available']);
                }
            }
            
            // Cập nhật trạng thái
            $order->update(['status' => 'cancelled']);
            
            return redirect()->route('cart.index')
                ->with('success', 'Đã hủy đơn hàng #' . $order->id);
        } catch (\Exception $e) {
            return redirect()->route('cart.index')
                ->with('error', 'Có lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Lấy cart items của user hiện tại
     */
    private function getCartItems()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        return CartItem::with('game.category')
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->get();
    }

    /**
     * Xóa giỏ hàng
     */
    private function clearCart()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        CartItem::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->delete();
    }

    /**
     * Tự động tạo game key
     */
    private function generateGameKey($gameId, $gameName)
    {
        // Tạo mã key ngẫu nhiên dựa trên tên game
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $gameName), 0, 4));
        if (strlen($prefix) < 2) {
            $prefix = 'GAME';
        }
        
        // Format: PREFIX-XXXX-XXXX-XXXX (giống Steam key)
        $keyCode = $prefix . '-' 
                 . strtoupper(substr(md5(uniqid()), 0, 4)) . '-'
                 . strtoupper(substr(md5(uniqid()), 0, 4)) . '-'
                 . strtoupper(substr(md5(uniqid()), 0, 4));

        // Kiểm tra trùng lặp (rất hiếm nhưng cần check)
        while (GameKey::where('key_code', $keyCode)->exists()) {
            $keyCode = $prefix . '-' 
                     . strtoupper(substr(md5(uniqid()), 0, 4)) . '-'
                     . strtoupper(substr(md5(uniqid()), 0, 4)) . '-'
                     . strtoupper(substr(md5(uniqid()), 0, 4));
        }

        // Tạo và lưu key mới với status available
        return GameKey::create([
            'game_id' => $gameId,
            'key_code' => $keyCode,
            'status' => 'available',
        ]);
    }

    /**
     * Thêm game vào thư viện của user
     */
    private function addGamesToLibrary($order)
    {
        // Load order items if not already loaded
        if (!$order->relationLoaded('orderItems')) {
            $order->load('orderItems');
        }

        foreach ($order->orderItems as $item) {
            // Kiểm tra xem game đã có trong thư viện chưa
            $exists = Library::where('user_id', $order->user_id)
                ->where('game_id', $item->game_id)
                ->exists();

            if (!$exists) {
                Library::create([
                    'user_id' => $order->user_id,
                    'game_id' => $item->game_id,
                    'order_id' => $order->id,
                    'purchased_at' => now(),
                ]);
            }
        }

        // Cập nhật status của game keys thành 'sold'
        foreach ($order->orderItems as $item) {
            if ($item->game_key_id) {
                GameKey::where('id', $item->game_key_id)
                    ->update(['status' => 'sold']);
            }
        }
    }
}
