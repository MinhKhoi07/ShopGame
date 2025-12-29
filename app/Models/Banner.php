<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'media_type',
        'video_path',
        'link',
        'type',
        'display_order',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Lấy đường dẫn media (ảnh hoặc video)
     */
    public function getMediaPath()
    {
        if ($this->media_type === 'video' && $this->video_path) {
            return asset('storage/' . $this->video_path);
        }
        return asset('storage/' . ($this->image_path ?? $this->image ?? ''));
    }

    /**
     * Scope: Lọc banner đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope: Lọc theo loại banner
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
