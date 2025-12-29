<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * Hiển thị danh sách game
     * - Có phân trang
     * - Lọc theo Category
     * - Tìm kiếm theo tên
     */
    public function index(Request $request)
    {
        $query = Game::active()->with('category');

        // Tìm kiếm theo tên game
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Lọc theo Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Sắp xếp (mặc định: mới nhất)
        $sortBy = $request->input('sort', 'latest');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(price_sale, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(price_sale, price) DESC');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default: // latest
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Phân trang (12 game/trang)
        $games = $query->paginate(12)->withQueryString();

        // Lấy danh sách Categories để hiển thị filter
        $categories = Category::orderBy('name', 'asc')->get();

        return view('games.index', compact('games', 'categories'));
    }

    /**
     * Hiển thị chi tiết game theo slug
     * - Thông tin game đầy đủ
     * - Reviews của game
     * - Kiểm tra còn key available không
     */
    public function show($slug)
    {
        // Lấy game theo slug, kèm theo category
        $game = Game::active()
            ->with(['category', 'gameKeys'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Lấy Reviews của game, kèm theo thông tin user
        $reviews = $game->reviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Tính điểm trung bình đánh giá
        $averageRating = $game->reviews()->avg('rating');
        $totalReviews = $game->reviews()->count();

        // Kiểm tra còn key available không
        $availableKeysCount = $game->gameKeys()->available()->count();
        $hasAvailableKeys = $availableKeysCount > 0;

        // Lấy các game liên quan (cùng category, trừ game hiện tại)
        $relatedGames = Game::active()
            ->where('category_id', $game->category_id)
            ->where('id', '!=', $game->id)
            ->take(4)
            ->get();

        // Determine ownership and user's review (if logged in)
        $owned = false;
        $userReview = null;
        if (Auth::check()) {
            $owned = $game->libraries()->where('user_id', Auth::id())->exists();
            $userReview = $game->reviews()->where('user_id', Auth::id())->first();
        }

        return view('games.show', compact(
            'game',
            'reviews',
            'averageRating',
            'totalReviews',
            'hasAvailableKeys',
            'availableKeysCount',
            'relatedGames',
            'owned',
            'userReview'
        ));
    }

    /**
     * Gợi ý tìm kiếm nhanh (autocomplete)
     */
    public function suggest(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $games = Game::active()
            ->where('name', 'like', '%' . $q . '%')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $data = $games->map(function ($game) {
            $hasSale = $game->price_sale && $game->price_sale > 0 && $game->price_sale < $game->price;
            $thumb = $game->thumbnail
                ? asset('storage/' . ltrim($game->thumbnail, '/'))
                : 'https://via.placeholder.com/160x90?text=Game';

            return [
                'name' => $game->name,
                'slug' => $game->slug,
                'url' => route('games.show', $game->slug),
                'thumbnail' => $thumb,
                'hasSale' => $hasSale,
                'discount' => $hasSale ? round((($game->price - $game->price_sale) / $game->price) * 100) : 0,
                'price' => number_format($game->price),
                'sale_price' => $hasSale ? number_format($game->price_sale) : null,
            ];
        });

        return response()->json($data);
    }
}
