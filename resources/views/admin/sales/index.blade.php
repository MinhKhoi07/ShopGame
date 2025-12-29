@extends('admin.layout')

@section('page-title', 'Quản lý Sales')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h2 style="color: white; margin: 0;">Sales / Khuyến Mãi</h2>
    <div style="display:flex; gap:10px;">
        <a href="{{ route('admin.sales.create') }}" class="btn btn-primary" style="padding: 10px 20px;">
            <i class="fas fa-plus"></i> Thêm Sale
        </a>
        <form action="{{ route('admin.sales.import') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-secondary" style="padding: 10px 20px;">
                <i class="fas fa-upload"></i> Nhập game đang sale
            </button>
        </form>
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên Sale</th>
                <th>Loại</th>
                <th>Áp dụng</th>
                <th>Giảm giá</th>
                <th>Thời gian</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $index => $sale)
                <tr>
                    <td>{{ ($sales->currentPage() - 1) * $sales->perPage() + $index + 1 }}</td>
                    <td>
                        <strong>{{ $sale->name }}</strong>
                        @if($sale->description)
                            <br><small style="color: var(--steam-text);">{{ Str::limit($sale->description, 50) }}</small>
                        @endif
                    </td>
                    <td>
                        @if($sale->game_id)
                            <span class="badge" style="background: rgba(45, 115, 255, 0.2); color: #2d73ff;">
                                <i class="fas fa-gamepad"></i> Game
                            </span>
                        @else
                            <span class="badge" style="background: rgba(190, 238, 17, 0.2); color: #beee11;">
                                <i class="fas fa-folder"></i> Danh mục
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($sale->game_id)
                            {{ $sale->game ? $sale->game->name : 'N/A' }}
                        @else
                            {{ $sale->category ? $sale->category->name : 'N/A' }}
                        @endif
                    </td>
                    <td>
                        <span style="color: var(--steam-sale); font-weight: bold; font-size: 16px;">
                            -{{ $sale->discount_percent }}%
                        </span>
                    </td>
                    <td>
                        <div style="font-size: 12px;">
                            <div>Từ: {{ $sale->start_date->format('d/m/Y H:i') }}</div>
                            <div>Đến: {{ $sale->end_date->format('d/m/Y H:i') }}</div>
                        </div>
                    </td>
                    <td>
                        <form action="{{ route('admin.sales.toggle', $sale->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @if($sale->is_active)
                                <button type="submit" class="badge badge-success" style="border: none; cursor: pointer;">
                                    <i class="fas fa-toggle-on"></i> Bật
                                </button>
                            @else
                                <button type="submit" class="badge badge-danger" style="border: none; cursor: pointer;">
                                    <i class="fas fa-toggle-off"></i> Tắt
                                </button>
                            @endif
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('admin.sales.edit', $sale->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <form action="{{ route('admin.sales.delete', $sale->id) }}" method="POST" 
                              style="display: inline-block; margin-left: 5px;" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa sale này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background: #ff6b6b; color: white; padding: 6px 12px; border: none; border-radius: 3px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: var(--steam-text);">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 10px; opacity: 0.3;"></i>
                        <p>Chưa có sale nào được tạo</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div style="margin-top: 30px; color: var(--steam-text); text-align: center;">
    {{ $sales->links() }}
</div>
@endsection
