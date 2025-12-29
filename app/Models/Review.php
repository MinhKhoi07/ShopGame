<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'user_id',
        'game_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Quan hệ: Review thuộc về User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ: Review thuộc về Game
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Scope: Lọc theo số sao
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope: Lọc đánh giá có comment
     */
    public function scopeWithComment($query)
    {
        return $query->whereNotNull('comment');
    }
}
