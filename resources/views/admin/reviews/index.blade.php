@extends('admin.layout')

@section('title', 'Quản lý đánh giá - Admin')
@section('page-title', 'Quản lý đánh giá')

@section('content')
<div class="admin-stats">
    <div class="stat-card">
        <h3>Tổng đánh giá</h3>
        <div class="number">{{ number_format($stats['total']) }}</div>
    </div>
    @for($i=5;$i>=1;$i--)
    <div class="stat-card {{ $i >= 4 ? 'border-green' : ($i==3 ? 'border-yellow' : 'border-red') }}">
        <h3>{{ $i }} sao</h3>
        <div class="number">{{ number_format($stats[$i]) }}</div>
    </div>
    @endfor
</div>

<div class="table-container" style="margin-bottom:20px; padding: 16px;">
    <form method="GET" action="{{ route('admin.reviews') }}" style="display:grid; grid-template-columns: 1fr 180px 180px auto; gap:12px; align-items:end;">
        <div>
            <label style="display:block; color: var(--steam-text); margin-bottom:6px;">Tìm kiếm</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="User/Game" style="width:100%; padding:10px; background: var(--steam-darker); border:1px solid var(--steam-border); color:#fff; border-radius:6px;">
        </div>
        <div>
            <label style="display:block; color: var(--steam-text); margin-bottom:6px;">Số sao</label>
            <select name="rating" style="width:100%; padding:10px; background: var(--steam-darker); border:1px solid var(--steam-border); color:#fff; border-radius:6px;">
                <option value="">Tất cả</option>
                @for($i=5;$i>=1;$i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                @endfor
            </select>
        </div>
        <div>
            <label style="display:block; color: var(--steam-text); margin-bottom:6px;">Bộ lọc</label>
            <label style="display:flex; align-items:center; gap:8px; color:#c6d4df;">
                <input type="checkbox" name="has_comment" value="1" {{ request('has_comment') ? 'checked' : '' }}> Chỉ có nhận xét
            </label>
        </div>
        <div style="display:flex; gap:10px;">
            <button class="btn btn-primary" style="padding:10px 16px;">Lọc</button>
            <a href="{{ route('admin.reviews') }}" class="btn btn-secondary" style="padding:10px 16px;">Reset</a>
        </div>
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Game</th>
                <th>Người dùng</th>
                <th>Đánh giá</th>
                <th>Nhận xét</th>
                <th>Thời gian</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr>
                <td>#{{ $review->id }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:8px;">
                        @if($review->game && $review->game->image_url)
                            <img src="{{ $review->game->image_url }}" alt="{{ $review->game->name }}" style="width:60px; height:34px; object-fit:cover; border-radius:4px;">
                        @endif
                        <div>
                            <div style="color:#fff; font-weight:600;">{{ $review->game->name ?? 'N/A' }}</div>
                            @if($review->game)
                                <a href="{{ route('games.show', $review->game->slug) }}" style="color: var(--steam-blue); font-size:12px;" target="_blank">Xem trang</a>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    <div style="color:#fff;">{{ $review->user->name ?? 'N/A' }}</div>
                    <div style="color:#8f98a0; font-size:12px;">{{ $review->user->email ?? '' }}</div>
                </td>
                <td>
                    <div class="text-yellow-400">
                        @for($i=0;$i<5;$i++)
                            <i class="fa{{ $i < $review->rating ? 's' : 'r' }} fa-star"></i>
                        @endfor
                        <span style="color:#c6d4df; margin-left:6px;">{{ $review->rating }}/5</span>
                    </div>
                </td>
                <td style="max-width:380px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color:#c6d4df;">{{ $review->comment }}</td>
                <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST" onsubmit="return confirm('Xóa đánh giá này?');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; color:#8f98a0;">Không có đánh giá nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px;">
    {{ $reviews->links() }}
</div>
@endsection
