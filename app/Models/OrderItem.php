<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'game_id',
        'game_key_id',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Quan hệ: OrderItem thuộc về Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Quan hệ: OrderItem thuộc về Game
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Quan hệ: OrderItem thuộc về GameKey (key đã giao cho khách)
     */
    public function gameKey()
    {
        return $this->belongsTo(GameKey::class);
    }
}
