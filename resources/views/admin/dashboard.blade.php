@extends('admin.layout')

@section('page-title', 'Dashboard')

@section('content')
<div class="admin-stats">
    <div class="stat-card">
        <h3>Tổng Games</h3>
        <div class="number">{{ $totalGames }}</div>
    </div>
    <div class="stat-card" style="border-left-color: #5ba32b;">
        <h3>Tổng Orders</h3>
        <div class="number">{{ $totalOrders }}</div>
    </div>
    <div class="stat-card" style="border-left-color: #beee11;">
        <h3>Tổng Users</h3>
        <div class="number">{{ $totalUsers }}</div>
    </div>
    <div class="stat-card" style="border-left-color: #ff6b6b;">
        <h3>Tổng Doanh Thu</h3>
        <div class="number">{{ number_format($totalRevenue) }}đ</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
    <!-- Recent Orders -->
    <div>
        <h2 style="color: white; margin-bottom: 20px;">
            <i class="fas fa-clock"></i> Đơn hàng gần đây
        </h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Tổng tiền</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ number_format($order->total_amount) }}đ</td>
                            <td>
                                @if($order->status === 'paid')
                                    <span class="badge badge-success">Đã thanh toán</span>
                                @elseif($order->status === 'pending')
                                    <span class="badge badge-warning">Chờ thanh toán</span>
                                @else
                                    <span class="badge badge-danger">Đã hủy</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Games -->
    <div>
        <h2 style="color: white; margin-bottom: 20px;">
            <i class="fas fa-gamepad"></i> Games mới nhất
        </h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tên Game</th>
                        <th>Giá</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentGames as $game)
                        <tr>
                            <td>{{ $game->name }}</td>
                            <td>{{ number_format($game->price) }}đ</td>
                            <td>
                                @if($game->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
