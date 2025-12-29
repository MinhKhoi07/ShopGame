@extends('admin.layout')

@section('page-title', 'Thêm Banner Mới')

@section('content')
<div style="max-width: 900px;">
    <h2 style="color: white; margin-bottom: 30px;">
        <a href="{{ route('admin.banners') }}" style="color: var(--steam-blue); text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
        Thêm Banner Mới
    </h2>

    <form method="POST" action="{{ route('admin.banners.store') }}" class="admin-form" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">Tiêu đề *</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required 
                   placeholder="Nhập tiêu đề banner">
            @error('title')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="description" rows="3" 
                      placeholder="Nhập mô tả banner">{{ old('description') }}</textarea>
            @error('description')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Loại Media *</label>
            <div style="display: flex; gap: 20px; margin-top: 10px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="radio" name="media_type" value="image" checked onchange="toggleMediaInput()" 
                           style="margin-right: 8px;">
                    <i class="fas fa-image" style="margin-right: 5px;"></i> Hình ảnh
                </label>
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="radio" name="media_type" value="video" onchange="toggleMediaInput()" 
                           style="margin-right: 8px;">
                    <i class="fas fa-video" style="margin-right: 5px;"></i> Video
                </label>
            </div>
            @error('media_type')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" id="imageInput">
            <label for="image">Tải lên Hình ảnh *</label>
            <input type="file" id="image" name="image" accept="image/*">
            <small style="color: var(--steam-text); display: block; margin-top: 5px;">
                Định dạng: JPG, PNG, GIF. Tối đa 5MB
            </small>
            @error('image')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" id="videoInput" style="display: none;">
            <label for="video">Tải lên Video *</label>
            <input type="file" id="video" name="video" accept="video/*">
            <small style="color: var(--steam-text); display: block; margin-top: 5px;">
                Định dạng: MP4, WebM, OGG. Tối đa 50MB
            </small>
            @error('video')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="link">Link (URL)</label>
            <input type="url" id="link" name="link" value="{{ old('link') }}" 
                   placeholder="https://example.com">
            @error('link')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="order">Thứ tự hiển thị *</label>
            <input type="number" id="order" name="order" value="{{ old('order', 0) }}" required min="0">
            <small style="color: var(--steam-text); display: block; margin-top: 5px;">
                Số càng nhỏ sẽ hiển thị trước
            </small>
            @error('order')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="is_active">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                Kích hoạt banner
            </label>
        </div>

        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Lưu Banner
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
            document.getElementById('image').required = true;
            document.getElementById('video').required = false;
        } else {
            imageInput.style.display = 'none';
            videoInput.style.display = 'block';
            document.getElementById('image').required = false;
            document.getElementById('video').required = true;
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

    .form-group label input[type="checkbox"],
    .form-group label input[type="radio"] {
        width: auto;
        margin-right: 8px;
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
