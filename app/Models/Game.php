<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Game extends Model
{
    use HasFactory;

    protected $table = 'games';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'price_sale',
        'thumbnail',
        'images',
        'system_requirements',
        'developer',
        'is_active',
        'is_free',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_sale' => 'decimal:2',
        'images' => 'array', // Cast JSON sang array
        'is_active' => 'boolean',
        'is_free' => 'boolean',
    ];

    /**
     * Quan hệ: Game thuộc về Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Quan hệ: Game có nhiều GameKeys
     */
    public function gameKeys()
    {
        return $this->hasMany(GameKey::class);
    }

    /**
     * Quan hệ: Game có nhiều OrderItems
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Quan hệ: Game có nhiều Libraries
     */
    public function libraries()
    {
        return $this->hasMany(Library::class);
    }

    /**
     * Quan hệ: Game có nhiều Reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Quan hệ: Game có nhiều Sales (theo game hoặc danh mục)
     */
    public function sales()
    {
        return $this->hasMany(Sale::class, 'game_id');
    }

    /**
     * Scope: Lọc game đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Lấy sale hiệu quả nhất cho game này
     * Policy: không cộng dồn, ưu tiên mức giảm cao nhất, game > category
     */
    public function effectiveSale()
    {
        // Lấy sale theo game
        $gameSale = Sale::active()
            ->where('game_id', $this->id)
            ->orderBy('discount_percent', 'desc')
            ->first();

        // Lấy sale theo category
        $categorySale = Sale::active()
            ->where('category_id', $this->category_id)
            ->orderBy('discount_percent', 'desc')
            ->first();

        // Không có sale nào
        if (!$gameSale && !$categorySale) {
            return null;
        }

        // Cả hai đều có: chọn mức giảm cao nhất, nếu bằng thì ưu tiên game
        if ($gameSale && $categorySale) {
            if ($gameSale->discount_percent >= $categorySale->discount_percent) {
                return $gameSale;
            } else {
                return $categorySale;
            }
        }

        // Chỉ một trong hai
        return $gameSale ?? $categorySale;
    }

    /**
     * Tính giá sale dựa trên effective sale
     */
    public function effectiveSalePrice()
    {
        $sale = $this->effectiveSale();
        if (!$sale) {
            return null;
        }

        $discount = ($this->price * $sale->discount_percent) / 100;
        return $this->price - $discount;
    }

    /**
     * Kiểm tra game có sale trùng (vừa theo game vừa theo category)
     */
    public function hasConflictingSales()
    {
        $gameSale = Sale::active()
            ->where('game_id', $this->id)
            ->first();

        $categorySale = Sale::active()
            ->where('category_id', $this->category_id)
            ->first();

        return $gameSale && $categorySale;
    }

    /**
     * Lấy giá cuối cùng (ưu tiên giá sale)
     */
    public function getFinalPriceAttribute()
    {
        return $this->price_sale ?? $this->price;
    }

    /**
     * Kiểm tra game có đang giảm giá không
     */
    public function isOnSale()
    {
        return !is_null($this->price_sale) && $this->price_sale < $this->price;
    }

    /**
     * Đếm số lượng key còn available
     */
    public function availableKeysCount()
    {
        return $this->gameKeys()->available()->count();
    }

    /**
     * Accessor: image_url - map từ thumbnail column
     */
    public function getImageUrlAttribute()
    {
        // Prefer thumbnail; fallback to first image in images array
        $path = $this->thumbnail;

        if (!$path && is_array($this->images) && count($this->images) > 0) {
            $path = $this->images[0];
        }

        if (!$path) {
            return null;
        }

        // If already absolute (http/https), return as-is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Otherwise prefix with storage public url
        return Storage::url($path);
    }
}
