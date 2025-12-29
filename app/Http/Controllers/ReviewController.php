<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Library;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store or update a review for a purchased game.
     */
    public function store(Request $request, $gameId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        $game = Game::findOrFail($gameId);

        // Check ownership in libraries
        $owned = Library::where('user_id', Auth::id())
            ->where('game_id', $game->id)
            ->exists();

        if (!$owned) {
            return redirect()->back()->withErrors(['review' => 'Bạn cần mua game này trước khi đánh giá.']);
        }

        // Upsert review (unique user_id + game_id)
        Review::updateOrCreate(
            ['user_id' => Auth::id(), 'game_id' => $game->id],
            ['rating' => (int)$request->rating, 'comment' => trim((string)$request->comment)]
        );

        return redirect()->route('games.show', $game->slug)
            ->with('review_success', 'Cảm ơn bạn! Đánh giá đã được ghi nhận.');
    }
}
