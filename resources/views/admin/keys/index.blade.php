@extends('admin.layout')

@section('title', 'Quản lý Game Keys')

@section('content')
<div class="admin-header">
    <h1><i class="fas fa-key"></i> Quản lý Game Keys</h1>
    <a href="{{ route('admin.keys.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm Keys
    </a>
</div>

<!-- Thống kê -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #1a9fff;"><i class="fas fa-key"></i></div>
        <div class="stat-info">
            <div class="stat-label">Tổng Keys</div>
            <div class="stat-value">{{ number_format($stats['total']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #4c6b22;"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <div class="stat-label">Còn lại</div>
            <div class="stat-value">{{ number_format($stats['available']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #c84b31;"><i class="fas fa-shopping-cart"></i></div>
        <div class="stat-info">
            <div class="stat-label">Đã bán</div>
            <div class="stat-value">{{ number_format($stats['sold']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #ffa500;"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <div class="stat-label">Đặt trước</div>
            <div class="stat-value">{{ number_format($stats['reserved']) }}</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filter-section" style="background: var(--steam-dark); padding: 20px; border-radius: 8px; margin-bottom: 20px;">
    <form method="GET" action="{{ route('admin.keys') }}">
        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: var(--steam-text);">Game</label>
                <select name="game_id" class="form-control">
                    <option value="">Tất cả games</option>
                    @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>
                            {{ $game->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: var(--steam-text);">Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="">Tất cả</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Còn lại</option>
                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Đã bán</option>
                    <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Đặt trước</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Lọc
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Table -->
<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Game</th>
                <th>Key Code</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($keys as $key)
                <tr>
                    <td>{{ $key->id }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            @if($key->game->thumbnail)
                                <img src="{{ asset('storage/' . $key->game->thumbnail) }}" 
                                     style="width: 50px; height: 30px; object-fit: cover; border-radius: 3px;">
                            @endif
                            <span>{{ $key->game->name }}</span>
                        </div>
                    </td>
                    <td>
                        <code style="background: #1a2332; padding: 5px 10px; border-radius: 4px; font-family: 'Courier New', monospace;">
                            {{ $key->key_code }}
                        </code>
                    </td>
                    <td>
                        @if($key->status == 'available')
                            <span class="badge badge-success">Còn lại</span>
                        @elseif($key->status == 'sold')
                            <span class="badge badge-danger">Đã bán</span>
                        @else
                            <span class="badge badge-warning">Đặt trước</span>
                        @endif
                    </td>
                    <td>{{ $key->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($key->status == 'available')
                            <form action="{{ route('admin.keys.delete', $key->id) }}" method="POST" 
                                  onsubmit="return confirm('Xác nhận xóa key này?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @else
                            <span style="color: var(--steam-text); font-size: 12px;">Không thể xóa</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--steam-text);">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 10px; opacity: 0.5;"></i>
                        <div>Chưa có game keys nào</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="pagination-container">
    {{ $keys->links() }}
</div>
@endsection
