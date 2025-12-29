<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Sale;
use App\Models\GameKey;
use App\Models\Review;
use App\Models\Library;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Hiển thị dashboard admin
     */
    public function dashboard()
    {
        $totalGames = Game::count();
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $totalRevenue = Order::where('status', 'paid')->sum('total_amount');

        // Đơn hàng gần đây
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Game mới nhất
        $recentGames = Game::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalGames',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'recentOrders',
            'recentGames'
        ));
    }

    /**
     * Hiển thị trang thống kê doanh thu
     */
    public function statistics(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $weekParam = $request->get('week');
        
        // Parse month and week
        $monthStart = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();
        
        // Parse ISO week (Y-Www format from HTML5 week input)
        if ($weekParam && preg_match('/(\d{4})-W(\d{2})/', $weekParam, $matches)) {
            $year = (int)$matches[1];
            $weekNum = (int)$matches[2];
            $weekStart = \Carbon\Carbon::create($year)->setISODate($year, $weekNum)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();
            $week = $weekParam;
        } else {
            $weekStart = now()->startOfWeek();
            $weekEnd = now()->endOfWeek();
            $week = now()->format('Y-\WW');
        }
        
        // Thống kê doanh thu theo thời gian
        $today = now()->format('Y-m-d');
        $startOfWeek = now()->startOfWeek();
        $startOfMonth = now()->startOfMonth();
        
        $revenueToday = Order::where('status', 'paid')
            ->whereDate('created_at', '=', $today)
            ->sum('total_amount');
        
        $revenueThisWeek = Order::where('status', 'paid')
            ->where('created_at', '>=', $startOfWeek)
            ->sum('total_amount');
        
        $revenueThisMonth = Order::where('status', 'paid')
            ->where('created_at', '>=', $startOfMonth)
            ->sum('total_amount');
        
        // Tổng doanh thu
        $totalRevenue = Order::where('status', 'paid')->sum('total_amount');

        // Thống kê đơn hàng theo trạng thái
        $orderStats = [
            'paid' => Order::where('status', 'paid')->count(),
            'pending' => Order::where('status', 'pending')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // Dữ liệu biểu đồ theo tháng
        $monthlyData = Order::where('status', 'paid')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $monthlyLabels = $monthlyData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->toArray();
        $monthlyRevenue = $monthlyData->pluck('total')->toArray();
        $monthlyOrders = $monthlyData->pluck('count')->toArray();

        // Dữ liệu biểu đồ theo tuần
        $dailyData = Order::where('status', 'paid')
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dayNames = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];
        $dailyLabels = [];
        $dailyRevenue = [];
        $dailyOrders = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $weekStart->copy()->addDays($i)->format('Y-m-d');
            $dailyLabels[] = $dayNames[$i];
            
            $dataPoint = $dailyData->firstWhere('date', $date);
            $dailyRevenue[] = $dataPoint ? $dataPoint->total : 0;
            $dailyOrders[] = $dataPoint ? $dataPoint->count : 0;
        }

        return view('admin.statistics', compact(
            'revenueToday',
            'revenueThisWeek',
            'revenueThisMonth',
            'totalRevenue',
            'orderStats',
            'monthlyLabels',
            'monthlyRevenue',
            'monthlyOrders',
            'dailyLabels',
            'dailyRevenue',
            'dailyOrders',
            'month',
            'week',
            'monthStart',
            'weekStart',
            'weekEnd'
        ));
    }

    /**
     * Danh sách games
     */
    public function games(Request $request)
    {
        $query = Game::with(['category', 'sales']);

        if ($search = $request->get('q')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($categoryId = $request->get('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($status = $request->get('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $games = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.games.index', compact('games', 'categories'));
    }

    /**
     * Danh sách categories
     */
    public function categories()
    {
        $categories = Category::withCount('games')->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Hiển thị form tạo category
     */
    public function createCategory()
    {
        return view('admin.categories.create');
    }

    /**
     * Lưu category mới
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['name']);

        // Nếu slug bị trùng, thêm hậu tố
        if (Category::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . substr(uniqid(), -4);
        }

        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
        ]);

        return redirect()->route('admin.categories')
            ->with('success', 'Đã tạo category thành công!');
    }

    /**
     * Hiển thị form sửa category
     */
    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Cập nhật category
     */
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['name']);

        $category->update([
            'name' => $validated['name'],
            'slug' => $slug,
        ]);

        return redirect()->route('admin.categories')
            ->with('success', 'Đã cập nhật category!');
    }

    /**
     * Xóa category
     */
    public function deleteCategory($id)
    {
        $category = Category::withCount('games')->findOrFail($id);

        if ($category->games_count > 0) {
            return redirect()->route('admin.categories')
                ->with('error', 'Không thể xóa category đang có game!');
        }

        $category->delete();

        return redirect()->route('admin.categories')
            ->with('success', 'Đã xóa category!');
    }

    /**
     * Danh sách orders
     */
    public function orders(Request $request)
    {
        $query = Order::with(['user', 'orderItems.game', 'invoice']);
        
        // Lọc theo trạng thái
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Lọc theo phương thức thanh toán
        if ($request->has('payment_method') && $request->payment_method !== '') {
            $query->where('payment_method', $request->payment_method);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Thống kê
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'completed' => Order::where('status', 'paid')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }
    
    /**
     * Xác nhận thanh toán đơn hàng
     */
    public function confirmPayment($orderId)
    {
        try {
            $order = Order::with('user')->findOrFail($orderId);
            
            if ($order->status !== 'pending') {
                return redirect()->back()->with('error', 'Đơn hàng đã được xử lý trước đó!');
            }
            
            // Cập nhật trạng thái đơn hàng
            $order->update(['status' => 'paid']);
            
            // Cập nhật game keys từ reserved sang sold và thêm vào thư viện
            foreach ($order->orderItems as $item) {
                if ($item->game_key_id) {
                    \App\Models\GameKey::where('id', $item->game_key_id)
                        ->update(['status' => 'sold']);
                }
                
                // Thêm game vào thư viện (nếu chưa có)
                if ($order->user_id) {
                    Library::firstOrCreate(
                        [
                            'user_id' => $order->user_id,
                            'game_id' => $item->game_id,
                        ],
                        [
                            'order_id' => $order->id,
                            'purchased_at' => now(),
                        ]
                    );
                }
            }
            
            // Xóa giỏ hàng của khách (nếu có user_id)
            if ($order->user_id) {
                \App\Models\CartItem::where('user_id', $order->user_id)->delete();
            }
            
            return redirect()->back()->with('success', 'Đã xác nhận thanh toán đơn hàng #' . $order->id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Hủy đơn hàng
     */
    public function cancelOrder($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            
            if ($order->status === 'paid') {
                return redirect()->back()->with('error', 'Không thể hủy đơn hàng đã hoàn thành!');
            }
            
            // Hoàn lại game keys về trạng thái available
            foreach ($order->orderItems as $item) {
                if ($item->game_key_id) {
                    \App\Models\GameKey::where('id', $item->game_key_id)
                        ->update(['status' => 'available']);
                }
            }
            
            $order->update(['status' => 'cancelled']);
            
            return redirect()->back()->with('success', 'Đã hủy đơn hàng #' . $order->id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Danh sách users
     */
    public function users()
    {
        $users = User::paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Hiển thị form chỉnh sửa user
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin user
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'is_admin' => 'required|boolean',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'Cập nhật user thành công!');
    }

    /**
     * Khóa/Mở khóa tài khoản user
     */
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Không cho phép khóa chính mình
        if ($user->id === \Illuminate\Support\Facades\Auth::id()) {
            return redirect()->back()->with('error', 'Không thể khóa tài khoản của chính bạn!');
        }
        
        $user->update([
            'is_active' => !$user->is_active
        ]);
        
        $status = $user->is_active ? 'mở khóa' : 'khóa';
        return redirect()->back()->with('success', "Đã {$status} tài khoản: {$user->name}");
    }

    /**
     * Hiển thị form tạo game mới
     */
    public function createGame()
    {
        $categories = Category::all();
        return view('admin.games.create', compact('categories'));
    }

    /**
     * Lưu game mới
     */
    public function storeGame(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:games',
            'slug' => 'required|string|unique:games',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'price_sale' => 'nullable|numeric|min:0',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'developer' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_free' => 'boolean',
        ]);

        // Upload thumbnail
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('games/thumbnails', 'public');
        }

        // Upload multiple images
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('games/images', 'public');
            }
            $validated['images'] = $imagePaths;
        }

        Game::create($validated);

        return redirect()->route('admin.games')->with('success', 'Game đã được thêm thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa game
     */
    public function editGame($id)
    {
        $game = Game::findOrFail($id);
        $categories = Category::all();
        return view('admin.games.edit', compact('game', 'categories'));
    }

    /**
     * Cập nhật game
     */
    public function updateGame(Request $request, $id)
    {
        $game = Game::findOrFail($id);
        
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:games,name,' . $id,
            'slug' => 'required|string|unique:games,slug,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'price_sale' => 'nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'developer' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_free' => 'boolean',
        ]);

        // Upload thumbnail mới nếu có
        if ($request->hasFile('thumbnail')) {
            // Xóa ảnh cũ
            if ($game->thumbnail && Storage::disk('public')->exists($game->thumbnail)) {
                Storage::disk('public')->delete($game->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('games/thumbnails', 'public');
        }

        // Xử lý images
        $currentImages = $game->images ?? [];
        
        // Xóa các ảnh được chọn
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $index) {
                if (isset($currentImages[$index])) {
                    if (Storage::disk('public')->exists($currentImages[$index])) {
                        Storage::disk('public')->delete($currentImages[$index]);
                    }
                    unset($currentImages[$index]);
                }
            }
            $currentImages = array_values($currentImages); // Reindex array
        }

        // Upload ảnh mới
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $currentImages[] = $image->store('games/images', 'public');
            }
        }

        $validated['images'] = $currentImages;

        $game->update($validated);

        return redirect()->route('admin.games')->with('success', 'Game đã được cập nhật thành công!');
    }

    /**
     * Xóa game
     */
    public function deleteGame($id)
    {
        $game = Game::findOrFail($id);
        $game->delete();

        return redirect()->route('admin.games')->with('success', 'Game đã được xóa thành công!');
    }

    /**
     * Danh sách banners
     */
    public function banners()
    {
        $banners = Banner::orderBy('order')->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Hiển thị form tạo banner mới
     */
    public function createBanner()
    {
        return view('admin.banners.create');
    }

    /**
     * Lưu banner mới
     */
    public function storeBanner(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media_type' => 'required|in:image,video',
            'image' => 'required_if:media_type,image|image|mimes:jpeg,png,jpg,gif|max:5120',
            'video' => 'required_if:media_type,video|mimes:mp4,webm,ogg|max:51200',
            'link' => 'nullable|url',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'media_type' => $validated['media_type'],
            'link' => $validated['link'] ?? null,
            'order' => $validated['order'],
            'is_active' => $request->has('is_active') ? true : false,
        ];

        // Upload media
        if ($validated['media_type'] === 'image' && $request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('banners/images', 'public');
        } elseif ($validated['media_type'] === 'video' && $request->hasFile('video')) {
            $data['video_path'] = $request->file('video')->store('banners/videos', 'public');
        }

        Banner::create($data);

        return redirect()->route('admin.banners')->with('success', 'Banner đã được thêm thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa banner
     */
    public function editBanner($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Cập nhật banner
     */
    public function updateBanner(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media_type' => 'required|in:image,video',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'video' => 'nullable|mimes:mp4,webm,ogg|max:51200',
            'link' => 'nullable|url',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'media_type' => $validated['media_type'],
            'link' => $validated['link'] ?? null,
            'order' => $validated['order'],
            'is_active' => $request->has('is_active') ? true : false,
        ];

        // Upload media mới nếu có
        if ($validated['media_type'] === 'image' && $request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $data['image_path'] = $request->file('image')->store('banners/images', 'public');
            $data['video_path'] = null;
        } elseif ($validated['media_type'] === 'video' && $request->hasFile('video')) {
            // Xóa video cũ
            if ($banner->video_path && Storage::disk('public')->exists($banner->video_path)) {
                Storage::disk('public')->delete($banner->video_path);
            }
            $data['video_path'] = $request->file('video')->store('banners/videos', 'public');
            $data['image_path'] = null;
        }

        $banner->update($data);

        return redirect()->route('admin.banners')->with('success', 'Banner đã được cập nhật thành công!');
    }

    /**
     * Xóa banner
     */
    public function deleteBanner($id)
    {
        $banner = Banner::findOrFail($id);
        
        // Xóa file
        if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }
        if ($banner->video_path && Storage::disk('public')->exists($banner->video_path)) {
            Storage::disk('public')->delete($banner->video_path);
        }
        
        $banner->delete();

        return redirect()->route('admin.banners')->with('success', 'Banner đã được xóa thành công!');
    }

    /**
     * Toggle trạng thái banner
     */
    public function toggleBanner($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->is_active = !$banner->is_active;
        $banner->save();

        $status = $banner->is_active ? 'kích hoạt' : 'tắt';
        return redirect()->route('admin.banners')->with('success', "Banner đã được {$status}!");
    }

    /**
     * ===============================
     * SALES MANAGEMENT
     * ===============================
     */

    /**
     * Hiển thị danh sách sales
     */
    public function sales()
    {
        $sales = Sale::with(['game', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.sales.index', compact('sales'));
    }

    /**
     * Hiển thị form tạo sale mới
     */
    public function createSale()
    {
        $games = Game::where('is_active', true)->get();
        $categories = Category::all();
        return view('admin.sales.create', compact('games', 'categories'));
    }

    /**
     * Lưu sale mới
     */
    public function storeSale(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sale_type' => 'required|in:game,category',
            'game_id' => 'required_if:sale_type,game|nullable|exists:games,id',
            'category_id' => 'required_if:sale_type,category|nullable|exists:categories,id',
            'discount_percent' => 'required|integer|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Sale::create([
            'name' => $request->name,
            'description' => $request->description,
            'game_id' => $request->sale_type === 'game' ? $request->game_id : null,
            'category_id' => $request->sale_type === 'category' ? $request->category_id : null,
            'discount_percent' => $request->discount_percent,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.sales')->with('success', 'Sale đã được tạo thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa sale
     */
    public function editSale($id)
    {
        $sale = Sale::findOrFail($id);
        $games = Game::where('is_active', true)->get();
        $categories = Category::all();
        return view('admin.sales.edit', compact('sale', 'games', 'categories'));
    }

    /**
     * Cập nhật sale
     */
    public function updateSale(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sale_type' => 'required|in:game,category',
            'game_id' => 'required_if:sale_type,game|nullable|exists:games,id',
            'category_id' => 'required_if:sale_type,category|nullable|exists:categories,id',
            'discount_percent' => 'required|integer|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $sale = Sale::findOrFail($id);
        $sale->update([
            'name' => $request->name,
            'description' => $request->description,
            'game_id' => $request->sale_type === 'game' ? $request->game_id : null,
            'category_id' => $request->sale_type === 'category' ? $request->category_id : null,
            'discount_percent' => $request->discount_percent,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.sales')->with('success', 'Sale đã được cập nhật!');
    }

    /**
     * Xóa sale
     */
    public function deleteSale($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return redirect()->route('admin.sales')->with('success', 'Sale đã được xóa!');
    }

    /**
     * Toggle trạng thái sale
     */
    public function toggleSale($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->is_active = !$sale->is_active;
        $sale->save();

        $status = $sale->is_active ? 'kích hoạt' : 'tắt';
        return redirect()->route('admin.sales')->with('success', "Sale đã được {$status}!");
    }

    /**
     * Import legacy game price_sale into Sales module (one-off tool)
     */
    public function importLegacySales()
    {
        $games = Game::whereNotNull('price_sale')
            ->where('price_sale', '>', 0)
            ->whereColumn('price_sale', '<', 'price')
            ->get();

        $created = 0;
        foreach ($games as $game) {
            // Skip if an active sale already exists for this game in the current window
            $existing = Sale::where('game_id', $game->id)
                ->where('is_active', true)
                ->where('end_date', '>=', now())
                ->first();
            if ($existing) {
                continue;
            }

            $discount = (int) round((1 - ($game->price_sale / $game->price)) * 100);
            if ($discount <= 0 || $discount > 100) {
                continue;
            }

            Sale::create([
                'name' => 'Auto Sale: ' . $game->name,
                'description' => 'Nhập từ giá sale cũ của game',
                'game_id' => $game->id,
                'category_id' => null,
                'discount_percent' => $discount,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'is_active' => true,
            ]);
            $created++;
        }

        return redirect()->route('admin.sales')->with('success', "Đã nhập {$created} sale từ game đang sale.");
    }

    /**
     * Hiển thị danh sách game keys
     */
    public function gameKeys(Request $request)
    {
        $gameId = $request->get('game_id');
        $status = $request->get('status');

        $query = GameKey::with('game');

        if ($gameId) {
            $query->where('game_id', $gameId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $keys = $query->orderBy('created_at', 'desc')->paginate(50);
        $games = Game::orderBy('name')->get();

        // Thống kê
        $stats = [
            'total' => GameKey::count(),
            'available' => GameKey::where('status', 'available')->count(),
            'sold' => GameKey::where('status', 'sold')->count(),
            'reserved' => GameKey::where('status', 'reserved')->count(),
        ];

        return view('admin.keys.index', compact('keys', 'games', 'stats'));
    }

    /**
     * ===============================
     * REVIEWS MANAGEMENT
     * ===============================
     */
    public function reviews(Request $request)
    {
        $query = Review::with(['user', 'game']);

        if ($rating = $request->get('rating')) {
            $query->where('rating', (int)$rating);
        }

        if ($request->filled('has_comment')) {
            $query->whereNotNull('comment')->where('comment', '!=', '');
        }

        if ($q = $request->get('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->whereHas('user', function ($uq) use ($q) {
                        $uq->where('name', 'like', "%$q%")
                           ->orWhere('email', 'like', "%$q%");
                    })
                    ->orWhereHas('game', function ($gq) use ($q) {
                        $gq->where('name', 'like', "%$q%");
                    });
            });
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $stats = [
            'total' => Review::count(),
            5 => Review::where('rating', 5)->count(),
            4 => Review::where('rating', 4)->count(),
            3 => Review::where('rating', 3)->count(),
            2 => Review::where('rating', 2)->count(),
            1 => Review::where('rating', 1)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    public function deleteReview($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        return redirect()->route('admin.reviews')->with('success', 'Đã xóa đánh giá.');
    }

    /**
     * Hiển thị form thêm game keys
     */
    public function createGameKey()
    {
        $games = Game::orderBy('name')->get();
        return view('admin.keys.create', compact('games'));
    }

    /**
     * Lưu game keys mới
     */
    public function storeGameKey(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'keys' => 'required|string',
        ]);

        $gameId = $validated['game_id'];
        $keysText = $validated['keys'];

        // Tách keys theo dòng
        $keyLines = array_filter(array_map('trim', explode("\n", $keysText)));
        
        $created = 0;
        $duplicates = 0;

        foreach ($keyLines as $keyCode) {
            // Kiểm tra trùng
            $exists = GameKey::where('key_code', $keyCode)->exists();
            
            if ($exists) {
                $duplicates++;
                continue;
            }

            GameKey::create([
                'game_id' => $gameId,
                'key_code' => $keyCode,
                'status' => 'available',
            ]);
            
            $created++;
        }

        $message = "Đã tạo {$created} keys.";
        if ($duplicates > 0) {
            $message .= " Bỏ qua {$duplicates} keys trùng.";
        }

        return redirect()->route('admin.keys')->with('success', $message);
    }

    /**
     * Xóa game key
     */
    public function deleteGameKey($id)
    {
        $key = GameKey::findOrFail($id);
        
        // Chỉ cho phép xóa key chưa bán
        if ($key->status !== 'available') {
            return redirect()->back()->with('error', 'Không thể xóa key đã bán hoặc đã đặt trước!');
        }

        $key->delete();
        return redirect()->back()->with('success', 'Đã xóa game key!');
    }
}
