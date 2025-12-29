@extends('admin.layout')

@section('page-title', 'Chỉnh Sửa Game')

@section('content')
<div style="max-width: 800px;">
    <h2 style="color: white; margin-bottom: 30px;">
        <a href="{{ route('admin.games') }}" style="color: var(--steam-blue); text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
        Chỉnh Sửa Game: {{ $game->name }}
    </h2>

    <form method="POST" action="{{ route('admin.games.update', $game->id) }}" class="admin-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Tên Game *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $game->name) }}" required 
                   placeholder="Nhập tên game">
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="slug">Slug *</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $game->slug) }}" required 
                   placeholder="Nhập slug (ví dụ: the-witcher-3)">
            @error('slug')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="category_id">Category *</label>
            <select id="category_id" name="category_id" required>
                <option value="">-- Chọn Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" 
                        {{ old('category_id', $game->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="description" rows="5" 
                      placeholder="Nhập mô tả game">{{ old('description', $game->description) }}</textarea>
            @error('description')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" id="price-group">
            <label for="price">Giá *</label>
            <input type="number" id="price" name="price" value="{{ old('price', $game->price) }}" required 
                   placeholder="Nhập giá" step="0.01">
            @error('price')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="thumbnail">Ảnh Thumbnail</label>
            @if($game->thumbnail)
                <div style="margin-bottom: 10px;">
                    <img src="{{ asset('storage/' . $game->thumbnail) }}" alt="{{ $game->name }}" style="max-width: 200px; border-radius: 5px;">
                    <p style="color: var(--steam-text); font-size: 13px; margin-top: 5px;">Ảnh hiện tại</p>
                </div>
            @endif
            <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
            <small style="color: var(--steam-text); display: block; margin-top: 5px;">Chọn ảnh mới để thay thế (JPG, PNG, tối đa 2MB)</small>
            @error('thumbnail')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="images">Ảnh Game (Nhiều ảnh)</label>
            @if($game->images && count($game->images) > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; margin-bottom: 10px;">
                    @foreach($game->images as $index => $image)
                        <div style="position: relative;">
                            <img src="{{ asset('storage/' . $image) }}" alt="Game image {{ $index + 1 }}" style="width: 100%; height: 100px; object-fit: cover; border-radius: 5px;">
                            <input type="checkbox" name="delete_images[]" value="{{ $index }}" style="position: absolute; top: 5px; right: 5px;">
                            <label style="position: absolute; top: 5px; left: 5px; background: rgba(0,0,0,0.7); color: white; padding: 2px 5px; font-size: 11px; border-radius: 3px;">Xóa</label>
                        </div>
                    @endforeach
                </div>
                <p style="color: var(--steam-text); font-size: 13px; margin-bottom: 10px;">Ảnh hiện tại - Tích chọn để xóa</p>
            @endif
            <input type="file" id="images" name="images[]" accept="image/*" multiple>
            <small style="color: var(--steam-text); display: block; margin-top: 5px;">Chọn thêm ảnh mới (Ctrl+Click để chọn nhiều)</small>
            @error('images')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="developer">Nhà Phát Triển</label>
            <input type="text" id="developer" name="developer" value="{{ old('developer', $game->developer) }}" 
                   placeholder="Nhập tên nhà phát triển">
            @error('developer')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="is_active">
                <input type="checkbox" id="is_active" name="is_active" value="1" 
                       {{ old('is_active', $game->is_active) ? 'checked' : '' }}>
                Kích hoạt game
            </label>
        </div>

        <div class="form-group">
            <label for="is_free">
                <input type="checkbox" id="is_free" name="is_free" value="1" 
                       {{ old('is_free', $game->is_free) ? 'checked' : '' }}>
                Game Miễn Phí (Không cần Thanh Toán)
            </label>
        </div>

        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.games') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Hủy
            </a>
        </div>
    </form>
</div>

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

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px;
        background: var(--steam-darker);
        border: 1px solid var(--steam-border);
        border-radius: 5px;
        color: white;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--steam-blue);
    }

    .form-group textarea {
        resize: vertical;
    }

    .form-group label input[type="checkbox"] {
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

    #price-free-label {
        display: none;
        color: var(--steam-sale);
        font-weight: 600;
        padding: 12px;
        background: rgba(190, 238, 17, 0.1);
        border-radius: 5px;
        text-align: center;
        margin-top: 8px;
    }
</style>

<script>
    const isFreeCheckbox = document.getElementById('is_free');
    const priceInput = document.getElementById('price');
    const priceGroup = document.getElementById('price-group');
    
    function updatePriceDisplay() {
        if (isFreeCheckbox.checked) {
            priceInput.style.display = 'none';
            priceInput.removeAttribute('required');
            priceInput.value = '0';
            
            let freeLabel = document.getElementById('price-free-label');
            if (!freeLabel) {
                freeLabel = document.createElement('div');
                freeLabel.id = 'price-free-label';
                freeLabel.textContent = '🎁 MIỄN PHÍ';
                priceGroup.appendChild(freeLabel);
            }
            freeLabel.style.display = 'block';
        } else {
            priceInput.style.display = 'block';
            priceInput.setAttribute('required', 'required');
            const freeLabel = document.getElementById('price-free-label');
            if (freeLabel) freeLabel.style.display = 'none';
        }
    }
    
    isFreeCheckbox.addEventListener('change', updatePriceDisplay);
    updatePriceDisplay();
</script>
@endsection
