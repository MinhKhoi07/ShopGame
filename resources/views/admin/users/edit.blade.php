@extends('admin.layout')

@section('page-title', 'Chỉnh sửa User')

@section('content')
<div style="max-width: 800px;">
    <h2 style="color: white; margin-bottom: 30px;">Chỉnh sửa User: {{ $user->name }}</h2>

    @if ($errors->any())
        <div style="background: rgba(255,107,107,0.1); border-left: 4px solid #ff6b6b; padding: 15px; margin-bottom: 20px; color: #ff6b6b;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" style="background: rgba(255,255,255,0.05); padding: 30px; border-radius: 8px;">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 20px;">
            <label for="name" style="display: block; margin-bottom: 8px; color: white; font-weight: 600;">
                Tên người dùng
            </label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="{{ old('name', $user->name) }}" 
                required
                style="width: 100%; padding: 10px; background: #2a3f5f; border: 1px solid #3d5a80; border-radius: 4px; color: white; font-size: 14px;"
            >
        </div>

        <div style="margin-bottom: 20px;">
            <label for="email" style="display: block; margin-bottom: 8px; color: white; font-weight: 600;">
                Email
            </label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email', $user->email) }}" 
                required
                style="width: 100%; padding: 10px; background: #2a3f5f; border: 1px solid #3d5a80; border-radius: 4px; color: white; font-size: 14px;"
            >
        </div>

        <div style="margin-bottom: 30px;">
            <label for="is_admin" style="display: block; margin-bottom: 8px; color: white; font-weight: 600;">
                Vai trò
            </label>
            <select 
                id="is_admin" 
                name="is_admin" 
                required
                style="width: 100%; padding: 10px; background: #2a3f5f; border: 1px solid #3d5a80; border-radius: 4px; color: white; font-size: 14px;"
            >
                <option value="0" {{ old('is_admin', $user->is_admin) == 0 ? 'selected' : '' }}>User</option>
                <option value="1" {{ old('is_admin', $user->is_admin) == 1 ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                Lưu thay đổi
            </button>
            <a href="{{ route('admin.users') }}" class="btn btn-secondary" style="flex: 1; text-align: center;">
                Hủy
            </a>
        </div>
    </form>
</div>
@endsection
