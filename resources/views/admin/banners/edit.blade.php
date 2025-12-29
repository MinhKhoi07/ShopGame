@extends('admin.layout')

@section('page-title', 'Chỉnh Sửa Banner')

@section('content')
<div style="max-width: 900px;">
    <h2 style="color: white; margin-bottom: 30px;">
        <a href="{{ route('admin.banners') }}" style="color: var(--steam-blue); text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
        Chỉnh Sửa Banner
    </h2>

    <form method="POST" action="{{ route('admin.banners.update', $banner->id) }}" class="admin-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Tiêu đề *</label>
            <input type="text" id="title" name="title" value="{{ old('title', $banner->title) }}" required>
            @error('title')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="description" rows="3">{{ old('description', $banner->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Loại Media *</label>
            <div style="display: flex; gap: 20px; margin-top: 10px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="radio" name="media_type" value="image" {{ $banner->media_type === 'image' ? 'checked' : '' }} onchange="toggleMediaInput()" style="margin-right: 8px;">
                    <i class="fas fa-image" style="margin-right: 5px;"></i> Hình ảnh
                </label>
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="radio" name="media_type" value="video" {{ $banner->media_type === 'video' ? 'checked' : '' }} onchange="toggleMediaInput()" style="margin-right: 8px;">
                    <i class="fas fa-video" style="margin-right: 5px;"></i> Video
                </label>
            </div>
        </div>

        <div class="form-group" id="imageInput" @if($banner->media_type !== 'image') style="display: none;" @endif>
            <label for="image">Tải lên Hình ảnh mới</label>
            @if($banner->media_type === 'image' && $banner->image_path)
                <div style="margin-bottom: 10px;">
                    <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}" 
                         style="max-width: 300px; border-radius: 5px;">
                </div>
            @endif
            <input type="file" id="image" name="image" accept="image/*">
            <small style="color: var(--steam-text); display: block; margin-top: 5px;">
                Để trống nếu không muốn thay đổi
            </small>
        </div>

        <div class="form-group" id="videoInput" @if($banner->media_type !== 'video') style="display: none;" @endif>
            <label for="video">Tải lên Video mới</label>
            @if($banner->media_type === 'video' && $banner->video_path)
                <div style="margin-bottom: 10px;">
                    <video style="max-width: 400px; border-radius: 5px;" controls>
                        <source src="{{ asset('storage/' . $banner->video_path) }}" type="video/mp4">
                    </video>
                </div>
            @endif
            <input type="file" id="video" name="video" accept="video/*">
            <small style="color: var(--steam-text); display: block; margin-top: 5px;">
                Để trống nếu không muốn thay đổi
            </small>
        </div>

        <div class="form-group">
            <label for="link">Link (URL)</label>
            <input type="url" id="link" name="link" value="{{ old('link', $banner->link) }}">
        </div>

        <div class="form-group">
            <label for="order">Thứ tự hiển thị *</label>
            <input type="number" id="order" name="order" value="{{ old('order', $banner->order) }}" required min="0">
        </div>

        <div class="form-group">
            <label for="is_active">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }}>
                Kích hoạt banner
            </label>
        </div>

        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.banners') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Hủy
            </a>
        </div>
    </form>
</div>

<script>
    function toggleMediaInput() {
        const mediaType = document.querySelector('input[name="media_type"]:checked').value;
        const imageInput = document.getElementById('imageInput');
        const videoInput = document.getElementById('videoInput');
        
        if (mediaType === 'image') {
            imageInput.style.display = 'block';
            videoInput.style.display = 'none';
        } else {
            imageInput.style.display = 'none';
            videoInput.style.display = 'block';
        }
    }
</script>

<style>
    .admin-form {
        background: var(--steam-dark);
        border-radius: 8px;
        padding: 30px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        color: var(--steam-text);
        margin-bottom: 8px;
        font-weight: 500;
    }

    .form-group input[type="text"],
    .form-group input[type="url"],
    .form-group input[type="number"],
    .form-group input[type="file"],
    .form-group textarea {
        width: 100%;
        padding: 12px;
        background: var(--steam-darker);
        border: 1px solid var(--steam-border);
        border-radius: 5px;
        color: white;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--steam-blue);
    }

    .form-group textarea {
        resize: vertical;
    }

    .error {
        color: #ff6b6b;
        font-size: 13px;
        display: block;
        margin-top: 5px;
    }

    .btn-secondary {
        background: var(--steam-border);
        color: var(--steam-text);
    }

    .btn-secondary:hover {
        background: #3a5a73;
    }
</style>
@endsection
