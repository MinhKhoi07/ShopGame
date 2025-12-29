<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameApiController extends Controller
{
    /**
     * Public game list with simple pagination.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = max(1, min($perPage, 50));

        $games = Game::active()
            ->select(['id', 'name', 'slug', 'price', 'price_sale', 'thumbnail'])
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json($games);
    }

    /**
     * Public game detail endpoint.
     */
    public function show(Game $game)
    {
        if (!$game->is_active) {
            return response()->json(['message' => 'Game is not available'], 404);
        }

        return response()->json($game);
    }
}
