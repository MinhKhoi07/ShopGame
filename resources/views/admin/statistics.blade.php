@extends('admin.layout')

@section('page-title', 'Thống Kê Doanh Thu')

@section('content')
<h1 style="color: var(--steam-text); margin-bottom: 30px;">Thống Kê Doanh Thu</h1>

<!-- Bộ lọc -->
<div style="background: var(--steam-dark); padding: 20px; border-radius: 8px; margin-bottom: 30px;">
    <form method="GET" action="{{ route('admin.statistics') }}" style="display: grid; grid-template-columns: auto auto auto; gap: 15px; align-items: flex-end;">
        <div>
            <label style="color: var(--steam-text); font-size: 12px; text-transform: uppercase; display: block; margin-bottom: 5px;">Tháng</label>
            <input type="month" name="month" value="{{ $month }}" style="padding: 8px 12px; border: 1px solid var(--steam-border); background: var(--steam-darker); color: white; border-radius: 4px;">
        </div>
        <div>
            <label style="color: var(--steam-text); font-size: 12px; text-transform: uppercase; display: block; margin-bottom: 5px;">Tuần</label>
            <input type="week" name="week" value="{{ $week }}" style="padding: 8px 12px; border: 1px solid var(--steam-border); background: var(--steam-darker); color: white; border-radius: 4px;">
        </div>
        <button type="submit" style="padding: 8px 20px; background: var(--steam-blue); color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            <i class="fas fa-search"></i> Lọc
        </button>
    </form>
</div>

<!-- Thống kê doanh thu chi tiết -->
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px;">
    <div style="background: var(--steam-dark); padding: 25px; border-radius: 8px; border-left: 4px solid #1a9fff;">
        <h4 style="color: var(--steam-text); margin: 0 0 15px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">Doanh Thu Hôm Nay</h4>
        <div style="color: #beee11; font-size: 36px; font-weight: bold;">{{ number_format($revenueToday) }}đ</div>
        <div style="color: #888; font-size: 12px; margin-top: 10px;">{{ now()->format('d/m/Y') }}</div>
    </div>
    <div style="background: var(--steam-dark); padding: 25px; border-radius: 8px; border-left: 4px solid #67c1f5;">
        <h4 style="color: var(--steam-text); margin: 0 0 15px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">Doanh Thu Tuần Này</h4>
        <div style="color: #67c1f5; font-size: 36px; font-weight: bold;">{{ number_format($revenueThisWeek) }}đ</div>
        <div style="color: #888; font-size: 12px; margin-top: 10px;">Từ {{ now()->startOfWeek()->format('d/m') }} - {{ now()->endOfWeek()->format('d/m/Y') }}</div>
    </div>
    <div style="background: var(--steam-dark); padding: 25px; border-radius: 8px; border-left: 4px solid #5ba32b;">
        <h4 style="color: var(--steam-text); margin: 0 0 15px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">Doanh Thu Tháng Này</h4>
        <div style="color: #5ba32b; font-size: 36px; font-weight: bold;">{{ number_format($revenueThisMonth) }}đ</div>
        <div style="color: #888; font-size: 12px; margin-top: 10px;">{{ now()->format('m/Y') }}</div>
    </div>
</div>

<!-- Biểu đồ doanh thu theo tuần -->
<div style="background: var(--steam-dark); padding: 25px; border-radius: 8px; margin-bottom: 30px;">
    <h2 style="color: var(--steam-text); margin-top: 0;">Doanh Thu Theo Ngày ({{ $weekStart->format('d/m') }} - {{ $weekEnd->format('d/m/Y') }})</h2>
    <div style="position: relative; height: 400px;">
        <canvas id="weeklyChart"></canvas>
    </div>
</div>

<!-- Biểu đồ doanh thu theo tháng -->
<div style="background: var(--steam-dark); padding: 25px; border-radius: 8px; margin-bottom: 30px;">
    <h2 style="color: var(--steam-text); margin-top: 0;">Doanh Thu Theo Ngày ({{ $monthStart->format('m/Y') }})</h2>
    <div style="position: relative; height: 400px;">
        <canvas id="monthlyChart"></canvas>
    </div>
</div>

<!-- Thống kê đơn hàng -->
<h2 style="color: var(--steam-text); margin-bottom: 20px; margin-top: 40px;">Tình Trạng Đơn Hàng</h2>
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px;">
    <div style="background: var(--steam-dark); padding: 30px; border-radius: 8px; text-align: center; border-top: 3px solid #5ba32b;">
        <h4 style="color: #5ba32b; margin: 0 0 15px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">Đã Thanh Toán</h4>
        <div style="color: white; font-size: 48px; font-weight: bold;">{{ $orderStats['paid'] }}</div>
        <div style="color: #888; font-size: 12px; margin-top: 10px;">Đơn hàng</div>
    </div>
    <div style="background: var(--steam-dark); padding: 30px; border-radius: 8px; text-align: center; border-top: 3px solid #ff9800;">
        <h4 style="color: #ff9800; margin: 0 0 15px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">Chờ Thanh Toán</h4>
        <div style="color: white; font-size: 48px; font-weight: bold;">{{ $orderStats['pending'] }}</div>
        <div style="color: #888; font-size: 12px; margin-top: 10px;">Đơn hàng</div>
    </div>
    <div style="background: var(--steam-dark); padding: 30px; border-radius: 8px; text-align: center; border-top: 3px solid #ff6b6b;">
        <h4 style="color: #ff6b6b; margin: 0 0 15px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">Đã Hủy</h4>
        <div style="color: white; font-size: 48px; font-weight: bold;">{{ $orderStats['cancelled'] }}</div>
        <div style="color: #888; font-size: 12px; margin-top: 10px;">Đơn hàng</div>
    </div>
</div>

<!-- Tổng doanh thu tất cả -->
<div style="background: var(--steam-dark); padding: 30px; border-radius: 8px; border-left: 4px solid var(--steam-sale); margin-top: 30px;">
    <h3 style="color: var(--steam-text); margin: 0 0 15px 0;">Tổng Doanh Thu (Tất Cả Thời Gian)</h3>
    <div style="color: var(--steam-sale); font-size: 40px; font-weight: bold;">{{ number_format($totalRevenue) }}đ</div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="application/json" id="stats-data">
{!! json_encode([
    'dailyLabels' => $dailyLabels,
    'dailyRevenue' => $dailyRevenue,
    'dailyOrders' => $dailyOrders,
    'monthlyLabels' => $monthlyLabels,
    'monthlyRevenue' => $monthlyRevenue,
    'monthlyOrders' => $monthlyOrders,
]) !!}
</script>
<script>
    // @ts-nocheck
    const statsData = JSON.parse(document.getElementById('stats-data').textContent);
    const dailyLabels = statsData.dailyLabels;
    const dailyRevenue = statsData.dailyRevenue;
    const dailyOrders = statsData.dailyOrders;
    const monthlyLabels = statsData.monthlyLabels;
    const monthlyRevenue = statsData.monthlyRevenue;
    const monthlyOrders = statsData.monthlyOrders;
</script>
<script>
    // @ts-nocheck
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [
                {
                    label: 'Doanh Thu (đ)',
                    data: dailyRevenue,
                    borderColor: '#beee11',
                    backgroundColor: 'rgba(190, 238, 17, 0.1)',
                    borderWidth: 2,
                    pointRadius: 5,
                    pointBackgroundColor: '#beee11',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y'
                },
                {
                    label: 'Số Đơn Hàng',
                    data: dailyOrders,
                    borderColor: '#67c1f5',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    pointRadius: 5,
                    pointBackgroundColor: '#67c1f5',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    fill: false,
                    tension: 0.4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#b2b2b2',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                }
            },
            scales: {
                y: {
                    position: 'left',
                    beginAtZero: true,
                    ticks: {
                        color: '#888',
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + 'đ';
                        }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    },
                    title: {
                        display: true,
                        text: 'Doanh Thu (đ)',
                        color: '#888'
                    }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Số Đơn Hàng',
                        color: '#888'
                    },
                    ticks: {
                        color: '#888'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                },
                x: {
                    ticks: {
                        color: '#888'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: monthlyLabels,
            datasets: [
                {
                    label: 'Doanh Thu (đ)',
                    data: monthlyRevenue,
                    backgroundColor: 'rgba(190, 238, 17, 0.6)',
                    borderColor: '#beee11',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    label: 'Số Đơn Hàng',
                    data: monthlyOrders,
                    backgroundColor: 'rgba(103, 193, 245, 0.6)',
                    borderColor: '#67c1f5',
                    borderWidth: 1,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#b2b2b2',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                }
            },
            scales: {
                y: {
                    position: 'left',
                    beginAtZero: true,
                    ticks: {
                        color: '#888',
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + 'đ';
                        }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    },
                    title: {
                        display: true,
                        text: 'Doanh Thu (đ)',
                        color: '#888'
                    }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Số Đơn Hàng',
                        color: '#888'
                    },
                    ticks: {
                        color: '#888'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                },
                x: {
                    ticks: {
                        color: '#888'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endsection
