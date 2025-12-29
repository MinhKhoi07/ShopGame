@extends('admin.layout')

@section('page-title', 'Quản lý Categories')

@section('content')
@if(session('success'))
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background: #c84b31; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h2 style="color: white; margin: 0;">Categories</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary" style="padding: 10px 20px;">
        <i class="fas fa-plus"></i> Thêm Category
    </a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Category</th>
                <th>Slug</th>
                <th>Số Games</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>#{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td><span class="badge badge-success">{{ $category->games_count }}</span></td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form method="POST" action="{{ route('admin.categories.delete', $category->id) }}" style="display:inline; margin-left: 6px;" onsubmit="return confirm('Xóa category này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background: #ff6b6b; color: white; padding: 6px 12px; border: none; border-radius: 3px; cursor: pointer;">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div style="margin-top: 30px; color: var(--steam-text); text-align: center;">
    {{ $categories->links() }}
</div>
@endsection
