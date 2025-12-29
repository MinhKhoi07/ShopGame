<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use App\Models\GameKey;

class Library extends Model
{
    use HasFactory;

    protected $table = 'libraries';

    protected $fillable = [
        'user_id',
        'game_id',
        'order_id',
        'purchased_at',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
    ];


    /**
     * Quan hệ: Library thuộc về User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ: Library thuộc về Game
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Order item gắn với library (cùng order_id và game_id)
     */
    public function orderItem()
    {
        return $this->hasOne(OrderItem::class, 'order_id', 'order_id')
            ->where('game_id', $this->game_id);
    }
}
