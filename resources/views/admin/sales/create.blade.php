@extends('admin.layout')

@section('page-title', 'Tạo Sale Mới')

@section('content')
<div style="max-width: 800px;">
    <h2 style="color: white; margin-bottom: 30px;">
        <a href="{{ route('admin.sales') }}" style="color: var(--steam-blue); text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
        Tạo Sale / Khuyến Mãi Mới
    </h2>

    <form method="POST" action="{{ route('admin.sales.store') }}" class="admin-form">
        @csrf

        <div class="form-group">
            <label for="name">Tên Sale *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                   placeholder="Ví dụ: Giảng sinh 2025, Black Friday Sale...">
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="description" rows="3" 
                      placeholder="Nhập mô tả về chương trình sale">{{ old('description') }}</textarea>
            @error('description')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Loại Sale *</label>
            <div style="display: flex; gap: 20px; margin-top: 10px;">
                <label style="color: var(--steam-text); cursor: pointer;">
                    <input type="radio" name="sale_type" value="game" {{ old('sale_type') == 'game' ? 'checked' : '' }} required onchange="toggleSaleType()">
                    Sale theo Game
                </label>
                <label style="color: var(--steam-text); cursor: pointer;">
                    <input type="radio" name="sale_type" value="category" {{ old('sale_type') == 'category' ? 'checked' : '' }} onchange="toggleSaleType()">
                    Sale theo Danh mục
                </label>
            </div>
            @error('sale_type')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" id="game_select" style="display: none;">
            <label for="game_id">Chọn Game *</label>
            <select id="game_id" name="game_id">
                <option value="">-- Chọn Game --</option>
                @foreach($games as $game)
                    <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                        {{ $game->name }} ({{ number_format($game->price) }}đ)
                    </option>
                @endforeach
            </select>
            @error('game_id')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" id="category_select" style="display: none;">
            <label for="category_id">Chọn Danh mục *</label>
            <select id="category_id" name="category_id">
                <option value="">-- Chọn Danh mục --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="discount_percent">Phần trăm giảm giá (%) *</label>
            <input type="number" id="discount_percent" name="discount_percent" value="{{ old('discount_percent') }}" 
                   required min="1" max="100" placeholder="Nhập % giảm (1-100)">
            @error('discount_percent')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="start_date">Ngày bắt đầu *</label>
                <input type="datetime-local" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                @error('start_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="end_date">Ngày kết thúc *</label>
                <input type="datetime-local" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                @error('end_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="is_active">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                Kích hoạt sale ngay
            </label>
        </div>

        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Tạo Sale
            </button>
            <a href="{{ route('admin.sales') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Hủy
            </a>
        </div>
    </form>
</div>

<script>
function toggleSaleType() {
    const saleType = document.querySelector('input[name="sale_type"]:checked').value;
    const gameSelect = document.getElementById('game_select');
    const categorySelect = document.getElementById('category_select');
    
    if (saleType === 'game') {
        gameSelect.style.display = 'block';
        categorySelect.style.display = 'none';
        document.getElementById('game_id').required = true;
        document.getElementById('category_id').required = false;
    } else {
        gameSelect.style.display = 'none';
        categorySelect.style.display = 'block';
        document.getElementById('game_id').required = false;
        document.getElementById('category_id').required = true;
    }
}

// Khởi tạo khi load trang
document.addEventListener('DOMContentLoaded', function() {
    const checkedType = document.querySelector('input[name="sale_type"]:checked');
    if (checkedType) {
        toggleSaleType();
    }
});
</script>

<style>
    .admin-form {
        background: var(--steam-dark);
        border-radius: 8px;
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        color: var(--steam-text-light);
        margin-bottom: 8px;
        font-weight: 500;
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group input[type="datetime-local"],
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        background: var(--steam-darker);
        border: 1px solid var(--steam-border);
        border-radius: 3px;
        color: var(--steam-text-light);
        font-size: 14px;
    }

    .form-group input[type="text"]:focus,
    .form-group input[type="number"]:focus,
    .form-group input[type="datetime-local"]:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--steam-blue);
    }

    .error {
        color: #ff6b6b;
        font-size: 13px;
        display: block;
        margin-top: 5px;
    }
</style>
@endsection
