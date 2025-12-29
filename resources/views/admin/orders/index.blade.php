@extends('admin.layout')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<div class="admin-header">
    <h1><i class="fas fa-shopping-cart"></i> Quản lý Đơn hàng</h1>
</div>

<!-- Success/Error Messages -->
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

<!-- Stats Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 8px;">
        <div style="color: rgba(255,255,255,0.8); font-size: 13px; margin-bottom: 5px;">Tổng đơn</div>
        <div style="color: white; font-size: 28px; font-weight: bold;">{{ $stats['total'] }}</div>
    </div>
    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 8px;">
        <div style="color: rgba(255,255,255,0.8); font-size: 13px; margin-bottom: 5px;">Chờ xác nhận</div>
        <div style="color: white; font-size: 28px; font-weight: bold;">{{ $stats['pending'] }}</div>
    </div>
    <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 20px; border-radius: 8px;">
        <div style="color: rgba(255,255,255,0.8); font-size: 13px; margin-bottom: 5px;">Hoàn thành</div>
        <div style="color: white; font-size: 28px; font-weight: bold;">{{ $stats['completed'] }}</div>
    </div>
    <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); padding: 20px; border-radius: 8px;">
        <div style="color: rgba(255,255,255,0.8); font-size: 13px; margin-bottom: 5px;">Đã hủy</div>
        <div style="color: white; font-size: 28px; font-weight: bold;">{{ $stats['cancelled'] }}</div>
    </div>
</div>

<!-- Filters -->
<div style="background: #1e2837; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
    <form method="GET" action="{{ route('admin.orders') }}" style="display: flex; gap: 15px; align-items: end;">
        <div style="flex: 1;">
            <label style="display: block; color: var(--steam-text); font-size: 13px; margin-bottom: 5px;">Trạng thái</label>
            <select name="status" class="form-control">
                <option value="">-- Tất cả --</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
        </div>
        <div style="flex: 1;">
            <label style="display: block; color: var(--steam-text); font-size: 13px; margin-bottom: 5px;">Phương thức</label>
            <select name="payment_method" class="form-control">
                <option value="">-- Tất cả --</option>
                <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản</option>
                <option value="momo" {{ request('payment_method') === 'momo' ? 'selected' : '' }}>MoMo</option>
                <option value="zalopay" {{ request('payment_method') === 'zalopay' ? 'selected' : '' }}>ZaloPay</option>
                <option value="credit_card" {{ request('payment_method') === 'credit_card' ? 'selected' : '' }}>Thẻ tín dụng</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Lọc
            </button>
            <a href="{{ route('admin.orders') }}" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Khách hàng</th>
                <th>Games</th>
                <th>Tổng tiền</th>
                <th>Phương thức</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td><strong>#{{ $order->id }}</strong></td>
                    <td>
                        <div style="color: white;">{{ $order->user->name ?? 'Khách' }}</div>
                        <div style="color: var(--steam-text); font-size: 12px;">{{ $order->user->email ?? '-' }}</div>
                    </td>
                    <td>
                        @foreach($order->orderItems as $item)
                            <div style="color: var(--steam-text); font-size: 13px;">
                                {{ $item->game->name }}
                            </div>
                        @endforeach
                    </td>
                    <td><strong style="color: var(--steam-blue);">{{ number_format($order->total_amount) }}đ</strong></td>
                    <td>
                        @if($order->payment_method === 'bank_transfer')
                            <span class="badge" style="background: #1e88e5;">Chuyển khoản</span>
                        @elseif($order->payment_method === 'momo')
                            <span class="badge" style="background: #a50064;">MoMo</span>
                        @elseif($order->payment_method === 'zalopay')
                            <span class="badge" style="background: #0068ff;">ZaloPay</span>
                        @else
                            <span class="badge" style="background: #666;">{{ $order->payment_method }}</span>
                        @endif
                    </td>
                    <td>
                        @if($order->status === 'paid')
                            <span class="badge badge-success">Hoàn thành</span>
                        @elseif($order->status === 'pending')
                            <span class="badge badge-warning">Chờ xác nhận</span>
                        @else
                            <span class="badge badge-danger">Đã hủy</span>
                        @endif
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($order->status === 'pending')
                            <form method="POST" action="{{ route('admin.orders.confirm', $order->id) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" 
                                        onclick="return confirm('Xác nhận đã nhận được tiền cho đơn hàng này?')">
                                    <i class="fas fa-check"></i> Xác nhận
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.orders.cancel', $order->id) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hủy đơn hàng này?')">
                                    <i class="fas fa-times"></i> Hủy
                                </button>
                            </form>
                        @else
                            <span style="color: var(--steam-text); font-size: 12px;">Đã xử lý</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 30px; color: var(--steam-text);">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 10px; opacity: 0.3;"></i>
                        <div>Chưa có đơn hàng nào</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div style="margin-top: 30px;">
    {{ $orders->links() }}
</div>
@endsection
