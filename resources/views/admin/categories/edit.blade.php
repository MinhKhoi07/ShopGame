@extends('admin.layout')

@section('title', 'Sửa Category')

@section('content')
<div class="admin-header">
    <h1><i class="fas fa-edit"></i> Sửa Category</h1>
    <a href="{{ route('admin.categories') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

@if($errors->any())
    <div style="background: #c84b31; color: white; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
        <i class="fas fa-exclamation-circle"></i> Vui lòng kiểm tra lại thông tin.
    </div>
@endif

<div class="form-container">
    <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="name" style="display: block; color: white; margin-bottom: 8px; font-weight: 600;">Tên Category <span style="color: #ff4444;">*</span></label>
            <input type="text" name="name" id="name" class="form-control" 
                   style="width: 100%; padding: 10px; background: #2a3f5f; border: 1px solid #3d5a80; color: white; border-radius: 5px; font-size: 14px;"
                   value="{{ old('name', $category->name) }}" required>
            @error('name')
                <div class="error-message" style="color: #ff6b6b; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="slug" style="display: block; color: white; margin-bottom: 8px; font-weight: 600;">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" 
                   style="width: 100%; padding: 10px; background: #2a3f5f; border: 1px solid #3d5a80; color: white; border-radius: 5px; font-size: 14px;"
                   value="{{ old('slug', $category->slug) }}">
            @error('slug')
                <div class="error-message" style="color: #ff6b6b; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-actions" style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn btn-primary" style="flex: 1; padding: 12px 20px;">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.categories') }}" class="btn btn-secondary" style="flex: 1; padding: 12px 20px; text-align: center;">
                <i class="fas fa-times"></i> Hủy
            </a>
        </div>
    </form>
</div>
@endsection