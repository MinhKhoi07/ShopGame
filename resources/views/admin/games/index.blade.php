@extends('admin.layout')

@section('page-title', 'Quản lý Games')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 12px; flex-wrap: wrap;">
    <h2 style="color: white; margin: 0;">Games</h2>
    <a href="{{ route('admin.games.create') }}" class="btn btn-primary" style="padding: 10px 20px;">
        <i class="fas fa-plus"></i> Thêm Game
    </a>
</div>

<form method="GET" action="{{ route('admin.games') }}" style="background: rgba(255,255,255,0.04); padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; align-items: end;">
    <div>
        <label style="color: #c7d5e0; font-weight: 600; display: block; margin-bottom: 6px;">Từ khóa</label>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Tên game" style="width: 100%; padding: 9px 10px; border-radius: 6px; border: 1px solid #2a475e; background: #0f172a; color: white;">
    </div>
    <div>
        <label style="color: #c7d5e0; font-weight: 600; display: block; margin-bottom: 6px;">Danh mục</label>
        <select name="category_id" style="width: 100%; padding: 9px 10px; border-radius: 6px; border: 1px solid #2a475e; background: #0f172a; color: white;">
            <option value="">Tất cả</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ (string)request('category_id') === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label style="color: #c7d5e0; font-weight: 600; display: block; margin-bottom: 6px;">Trạng thái</label>
        <select name="status" style="width: 100%; padding: 9px 10px; border-radius: 6px; border: 1px solid #2a475e; background: #0f172a; color: white;">
            <option value="">Tất cả</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div style="display: flex; gap: 8px;">
        <button type="submit" class="btn btn-primary" style="flex: 1;">Lọc</button>
        <a href="{{ route('admin.games') }}" class="btn btn-secondary" style="flex: 1; text-align: center;">Reset</a>
    </div>
</form>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Ảnh</th>
                <th>Tên Game</th>
                <th>Category</th>
                <th>Giá</th>
                <th>Giá Sale</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($games as $game)
                <tr>
                    <td>#{{ $game->id }}</td>
                    <td>
                        @if($game->thumbnail)
                            <img src="{{ asset('storage/' . $game->thumbnail) }}" alt="{{ $game->name }}" style="width: 60px; height: 35px; object-fit: cover; border-radius: 3px;">
                        @else
                            <div style="width: 60px; height: 35px; background: var(--steam-border); border-radius: 3px;"></div>
                        @endif
                    </td>
                    <td>{{ $game->name }}</td>
                    <td>{{ $game->category->name }}</td>
                    <td>
                        @if($game->is_free || $game->price == 0)
                            <span style="color: #beee11; font-weight: bold;">🎁 Miễn Phí</span>
                        @else
                            {{ number_format($game->price) }}đ
                        @endif
                    </td>
                    <td>
                        @php
                            $effectiveSale = $game->effectiveSale();
                            $effectivePrice = $game->effectiveSalePrice();
                        @endphp

                        @if($effectivePrice)
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="color: var(--steam-sale); font-weight: bold;">{{ number_format($effectivePrice) }}đ</span>
                                <span style="color: var(--steam-text); font-size: 12px; text-decoration: line-through;">{{ number_format($game->price) }}đ</span>
                                <span style="background: var(--steam-sale); color: black; padding: 2px 6px; border-radius: 3px; font-size: 11px; font-weight: bold;">-{{ $effectiveSale->discount_percent }}%</span>

                                @if($game->hasConflictingSales())
                                    <span title="Game có sale vừa theo game vừa theo danh mục (chọn cao nhất)" style="background: #ff9800; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px; font-weight: bold;">⚠ TRÙNG</span>
                                @endif
                            </div>
                        @else
                            <span style="color: var(--steam-text);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($game->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.games.edit', $game->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('admin.games.delete', $game->id) }}" method="POST" style="display: inline-block; margin-left: 5px;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa game này?');">
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
<div style="margin-top: 30px; text-align: center; position: sticky; bottom: 0; padding: 14px 0; background: rgba(15, 23, 42, 0.95); z-index: 10;">
    <style>
        /* Reset Bootstrap pagination overlap completely */
        nav ul.pagination {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            flex-wrap: nowrap !important;
            white-space: nowrap !important;
            background: #0f172a;
            padding: 8px 12px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.45);
            margin: 0 !important;
        }
        nav ul.pagination li,
        nav ul.pagination .page-item {
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        nav ul.pagination .page-item:not(:first-child) .page-link {
            margin-left: 0 !important;
        }
        nav ul.pagination .page-link,
        nav ul.pagination span {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 44px;
            height: 38px;
            padding: 0 14px;
            border-radius: 10px !important;
            background: #ffffff;
            color: #1f2937;
            text-decoration: none;
            font-weight: 600;
            border: 1px solid #e5e7eb !important;
            box-sizing: border-box;
            margin: 0 !important;
            line-height: 1 !important;
        }
        nav ul.pagination .active .page-link,
        nav ul.pagination .active span {
            background: #2563eb;
            color: #ffffff;
            border-color: #1d4ed8 !important;
        }
        nav ul.pagination .disabled .page-link,
        nav ul.pagination .disabled span {
            background: #f1f5f9;
            color: #94a3b8;
            border-color: #e2e8f0 !important;
        }
    </style>
    {{ $games->withQueryString()->links() }}
</div>
@endsection
