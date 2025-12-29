<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    /**
     * Display user's game library
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem thư viện');
        }

        $library = Library::where('user_id', Auth::id())
            ->with(['game.category'])
            ->orderBy('purchased_at', 'desc')
            ->get();

        // Nạp thủ công orderItem để dùng đúng game_id của từng library
        $library->each(function ($item) {
            $item->setRelation('orderItem', $item->orderItem()->with('gameKey')->first());
        });

        return view('library.index', compact('library'));
    }
}
