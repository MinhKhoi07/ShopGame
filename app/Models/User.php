<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Quan hệ: User có nhiều Orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Quan hệ: User có nhiều Libraries (thư viện game)
     */
    public function libraries()
    {
        return $this->hasMany(Library::class);
    }

    /**
     * Quan hệ: User có nhiều Reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Quan hệ: User có nhiều Messages
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Kiểm tra user có phải Admin không
     */
    public function isAdmin()
    {
        return $this->is_admin === true;
    }
}
