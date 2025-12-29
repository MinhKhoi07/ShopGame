@extends('admin.layout')

@section('page-title', 'Quản lý Users')

@section('content')
<h2 style="color: white; margin-bottom: 30px;">Tất cả Users</h2>

@if(session('success'))
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 15px; margin-bottom: 20px; border-radius: 8px; color: white;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 15px; margin-bottom: 20px; border-radius: 8px; color: white;">
        {{ session('error') }}
    </div>
@endif

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Role</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>#{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->isAdmin())
                            <span class="badge badge-danger">Admin</span>
                        @else
                            <span class="badge badge-success">User</span>
                        @endif
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="badge badge-success">Hoạt động</span>
                        @else
                            <span class="badge badge-danger">Đã khóa</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    <td style="display: flex; gap: 5px;">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        
                        @php
                            $action = $user->is_active ? 'Khóa' : 'Mở khóa';
                        @endphp
                        
                        <form method="POST" action="{{ route('admin.users.toggle', $user->id) }}" style="display: inline;" onsubmit="return confirm('{{ $action }} tài khoản?')">
                            @csrf
                            <button 
                                type="submit" 
                                class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}"
                            >
                                {{ $user->is_active ? 'Khóa' : 'Mở' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px;">Không có user nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div style="margin-top: 30px; color: var(--steam-text); text-align: center;">
    {{ $users->links() }}
</div>
@endsection
