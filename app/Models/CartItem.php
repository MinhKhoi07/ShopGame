<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'game_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Quan hệ: CartItem thuộc về User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ: CartItem thuộc về Game
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Lấy giá hiện tại của game (có sale hay không)
     */
    public function getCurrentPrice()
    {
        $game = $this->game;
        return $game->price_sale ?? $game->price;
    }

    /**
     * Tính tổng tiền cho item này
     */
    public function getSubtotal()
    {
        return $this->getCurrentPrice() * $this->quantity;
    }
}
