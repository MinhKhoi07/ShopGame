<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameKey extends Model
{
    use HasFactory;

    protected $table = 'game_keys';

    protected $fillable = [
        'game_id',
        'key_code',
        'status',
    ];

    /**
     * Quan hệ: GameKey thuộc về Game
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Quan hệ: GameKey có thể thuộc về OrderItem
     */
    public function orderItem()
    {
        return $this->hasOne(OrderItem::class);
    }

    /**
     * Scope: Lọc các key có sẵn (chưa bán)
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope: Lọc các key đã bán
     */
    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }

    /**
     * Scope: Lọc các key bị khóa
     */
    public function scopeLocked($query)
    {
        return $query->where('status', 'locked');
    }

    /**
     * Đánh dấu key là đã bán
     */
    public function markAsSold()
    {
        $this->update(['status' => 'sold']);
    }

    /**
     * Đánh dấu key bị khóa
     */
    public function markAsLocked()
    {
        $this->update(['status' => 'locked']);
    }

    /**
     * Kiểm tra key có sẵn không
     */
    public function isAvailable()
    {
        return $this->status === 'available';
    }
}
