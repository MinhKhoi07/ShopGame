<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Game;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ
     * - Banners đang hoạt động
     * - Games mới nhất
     * - Games đang giảm giá
     */
    public function index()
    {
        // Lấy Banners đang hoạt động (is_active = 1), sắp xếp theo display_order
        $sliderBanners = Banner::active()
            ->byType('slider')
            ->orderBy('display_order', 'asc')
            ->get();

        $sidebarBanners = Banner::active()
            ->byType('sidebar')
            ->orderBy('display_order', 'asc')
            ->get();

        // Lấy Games mới nhất (top 8), chỉ lấy game đang hoạt động
        $latestGames = Game::active()
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Lấy Games miễn phí
        $freeGames = Game::active()
            ->where('is_free', true)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Lấy Games đang giảm giá (price_sale IS NOT NULL và < price)
        $saleGames = Game::active()
            ->with('category')
            ->whereNotNull('price_sale')
            ->whereColumn('price_sale', '<', 'price')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Lấy Games hot/trending (game có nhiều order items gần đây)
        $hotGames = Game::active()
            ->with('category', 'orderItems')
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(8)
            ->get();

        return view('home', compact(
            'sliderBanners',
            'sidebarBanners',
            'latestGames',
            'freeGames',
            'saleGames',
            'hotGames'
        ));
    }
}
