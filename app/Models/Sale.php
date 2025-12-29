<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'game_id',
        'category_id',
        'discount_percent',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Quan hệ: Sale thuộc về Game (nếu sale theo game)
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Quan hệ: Sale thuộc về Category (nếu sale theo danh mục)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Kiểm tra sale còn hiệu lực hay không
     */
    public function isValid()
    {
        return $this->is_active 
            && now()->between($this->start_date, $this->end_date);
    }

    /**
     * Scope: Lọc sale đang bật và nằm trong khoảng thời gian hiệu lực
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }
}
