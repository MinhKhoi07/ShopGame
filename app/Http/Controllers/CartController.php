<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        $cartItems = $this->getCartItems();
        
        // Tính tổng tiền
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->getSubtotal();
        }
        
        // Tính thuế (giả sử 10%)
        $tax = $subtotal * 0.1;
        $total = $subtotal + $tax;

        // Lấy danh sách game_id đã có trong giỏ
        $cartGameIds = $cartItems->pluck('game_id')->toArray();
        
        // Lấy các đơn hàng đang chờ xác nhận của user
        $pendingOrders = collect();
        if (Auth::check()) {
            $pendingOrders = \App\Models\Order::with(['orderItems.game'])
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Lấy game đề xuất - chỉ các game đang giảm giá và không có trong giỏ
        $recommendedGames = Game::where('is_active', true)
            ->whereNotNull('price_sale')
            ->whereColumn('price_sale', '<', 'price')
            ->whereNotIn('id', $cartGameIds)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'total', 'recommendedGames', 'pendingOrders'));
    }

    /**
     * Thêm game vào giỏ hàng
     */
    public function add(Request $request, $gameId)
    {
        $game = Game::findOrFail($gameId);
        
        // Lấy user_id hoặc session_id
        $userId = Auth::id();
        $sessionId = $userId ? null : session()->getId();

        // Kiểm tra xem game đã có trong giỏ chưa
        $cartItem = CartItem::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->where('game_id', $gameId)->first();

        if ($cartItem) {
            // Nếu đã có, tăng quantity
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            // Nếu chưa có, tạo mới
            CartItem::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'game_id' => $gameId,
                'quantity' => 1,
                'price' => $game->price_sale ?? $game->price,
            ]);
        }

        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            $cartCount = $this->getCartItems()->count();
            
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào giỏ hàng!',
                'cart_count' => $cartCount,
                'game' => [
                    'id' => $game->id,
                    'name' => $game->name,
                    'thumbnail' => asset('storage/' . $game->thumbnail),
                    'price' => (float) $game->price,
                    'price_sale' => $game->price_sale ? (float) $game->price_sale : null,
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    /**
     * Xóa item khỏi giỏ hàng
     */
    public function remove($itemId)
    {
        $cartItem = CartItem::findOrFail($itemId);
        
        // Kiểm tra quyền sở hữu
        $userId = Auth::id();
        $sessionId = session()->getId();
        
        if (($userId && $cartItem->user_id == $userId) || 
            (!$userId && $cartItem->session_id == $sessionId)) {
            $cartItem->delete();
            return redirect()->back()->with('success', 'Đã xóa khỏi giỏ hàng!');
        }

        return redirect()->back()->with('error', 'Không thể xóa item này!');
    }

    /**
     * Cập nhật quantity
     */
    public function update(Request $request, $itemId)
    {
        $cartItem = CartItem::findOrFail($itemId);
        
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return redirect()->back()->with('success', 'Đã cập nhật giỏ hàng!');
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
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

        return redirect()->back()->with('success', 'Đã xóa toàn bộ giỏ hàng!');
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
     * Đếm số lượng items trong giỏ
     */
    public function count()
    {
        $count = $this->getCartItems()->count();
        
        // Return JSON for AJAX
        if (request()->expectsJson()) {
            return response()->json(['count' => $count]);
        }
        
        return $count;
    }
}
