<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'user_id',
        'message',
        'is_from_admin',
        'is_read',
    ];

    protected $casts = [
        'is_from_admin' => 'boolean',
        'is_read' => 'boolean',
    ];

    /**
     * Quan hệ: Message thuộc về User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Lọc tin nhắn chưa đọc
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', 0);
    }

    /**
     * Scope: Lọc tin nhắn từ admin
     */
    public function scopeFromAdmin($query)
    {
        return $query->where('is_from_admin', 1);
    }

    /**
     * Scope: Lọc tin nhắn từ customer
     */
    public function scopeFromCustomer($query)
    {
        return $query->where('is_from_admin', 0);
    }
}
