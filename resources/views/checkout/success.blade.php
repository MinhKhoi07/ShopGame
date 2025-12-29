@extends('layouts.app')

@section('title', 'Thanh toán thành công')

@section('content')
<div class="container" style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
    <!-- Success header -->
    <div style="text-align: center; margin-bottom: 40px;">
        <div style="display: inline-block; width: 100px; height: 100px; background: #4c6b22; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
            <i class="fas fa-check" style="font-size: 50px; color: white;"></i>
        </div>
        <h1 style="color: white; font-size: 32px; margin-bottom: 10px;">
            Thanh toán thành công!
        </h1>
        <p style="color: var(--steam-text); font-size: 16px;">
            Cảm ơn bạn đã mua hàng tại ShopGame
        </p>
    </div>

    <!-- Order info -->
    <div style="background: var(--steam-dark); padding: 30px; border-radius: 8px; margin-bottom: 25px;">
        <h2 style="color: white; margin-bottom: 20px; font-size: 20px; border-bottom: 2px solid var(--steam-border); padding-bottom: 10px;">
            <i class="fas fa-receipt"></i> Thông tin đơn hàng
        </h2>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div>
                <div style="color: var(--steam-text); font-size: 13px; margin-bottom: 5px;">Mã đơn hàng:</div>
                <div style="color: white; font-weight: 600; font-size: 16px;">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div>
                <div style="color: var(--steam-text); font-size: 13px; margin-bottom: 5px;">Ngày đặt:</div>
                <div style="color: white; font-weight: 600; font-size: 16px;">{{ $order->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div>
                <div style="color: var(--steam-text); font-size: 13px; margin-bottom: 5px;">Khách hàng:</div>
                <div style="color: white; font-weight: 600; font-size: 16px;">{{ $order->invoice?->customer_name ?? $order->user?->name ?? 'N/A' }}</div>
            </div>
            <div>
                <div style="color: var(--steam-text); font-size: 13px; margin-bottom: 5px;">Email:</div>
                <div style="color: white; font-weight: 600; font-size: 16px;">{{ $order->invoice?->customer_email ?? $order->user?->email ?? 'N/A' }}</div>
            </div>
            <div>
                <div style="color: var(--steam-text); font-size: 13px; margin-bottom: 5px;">Phương thức:</div>
                <div style="color: white; font-weight: 600; font-size: 16px;">
                    @if($order->payment_method == 'bank_transfer')
                        <i class="fas fa-university"></i> Chuyển khoản
                    @elseif($order->payment_method == 'momo')
                        <i class="fas fa-mobile-alt"></i> MoMo
                    @elseif($order->payment_method == 'zalopay')
                        <i class="fas fa-mobile-alt"></i> ZaloPay
                    @else
                        <i class="fas fa-credit-card"></i> Thẻ tín dụng
                    @endif
                </div>
            </div>
            <div>
                <div style="color: var(--steam-text); font-size: 13px; margin-bottom: 5px;">Tổng tiền:</div>
                <div style="color: var(--steam-blue); font-weight: bold; font-size: 18px;">{{ number_format($order->total_amount) }}đ</div>
            </div>
        </div>
    </div>

    <!-- Game keys -->
    <div style="background: var(--steam-dark); padding: 30px; border-radius: 8px; margin-bottom: 25px;">
        <h2 style="color: white; margin-bottom: 20px; font-size: 20px; border-bottom: 2px solid var(--steam-border); padding-bottom: 10px;">
            <i class="fas fa-key"></i> Game Keys của bạn
        </h2>

        <div style="background: #2a3f5f; border-left: 4px solid var(--steam-blue); padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            <i class="fas fa-info-circle" style="color: var(--steam-blue);"></i>
            <span style="color: white; margin-left: 8px;">Vui lòng lưu lại các key bên dưới. Bạn có thể xem lại trong phần "Đơn hàng" của tài khoản.</span>
        </div>

        @foreach($order->orderItems as $item)
            <div style="background: var(--steam-darker); padding: 20px; border-radius: 8px; margin-bottom: 15px; border: 1px solid var(--steam-border);">
                <div style="display: flex; gap: 15px; align-items: center; margin-bottom: 15px;">
                    <img src="{{ asset('storage/' . $item->game->thumbnail) }}" 
                         alt="{{ $item->game->name }}"
                         style="width: 100px; height: 75px; object-fit: cover; border-radius: 5px;">
                    <div style="flex: 1;">
                        <h3 style="color: white; font-size: 18px; margin-bottom: 5px; font-weight: 600;">
                            {{ $item->game->name }}
                        </h3>
                        <div style="color: var(--steam-text); font-size: 14px;">
                            <i class="fas fa-tag"></i> {{ $item->game->category->name ?? 'N/A' }}
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="color: white; font-size: 18px; font-weight: bold;">
                            {{ number_format($item->price) }}đ
                        </div>
                    </div>
                </div>

                <!-- Game Key -->
                <div style="background: #1a2332; padding: 15px; border-radius: 5px; border: 2px dashed var(--steam-blue);">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="color: var(--steam-text); font-size: 13px; margin-bottom: 5px;">
                                <i class="fas fa-key"></i> ACTIVATION KEY:
                            </div>
                            <div style="color: white; font-family: 'Courier New', monospace; font-size: 16px; font-weight: bold; letter-spacing: 2px;">
                                {{ $item->gameKey->key_code }}
                            </div>
                        </div>
                        <button onclick="copyKey('{{ $item->gameKey->key_code }}', this)" 
                                style="padding: 10px 20px; background: var(--steam-blue); border: none; border-radius: 4px; color: white; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                </div>

                <!-- Hướng dẫn kích hoạt -->
                <div style="margin-top: 15px; padding: 12px; background: rgba(255,255,255,0.05); border-radius: 4px;">
                    <div style="color: var(--steam-text); font-size: 13px; line-height: 1.6;">
                        <strong style="color: white;">Cách kích hoạt:</strong><br>
                        1. Mở Steam client và đăng nhập<br>
                        2. Vào menu "Games" → "Activate a Product on Steam"<br>
                        3. Nhập key code phía trên và làm theo hướng dẫn
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Actions -->
    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('home') }}" 
           style="display: inline-block; padding: 12px 30px; background: var(--steam-gradient); border: none; border-radius: 5px; color: white; font-weight: bold; text-decoration: none; margin-right: 15px;">
            <i class="fas fa-home"></i> Về trang chủ
        </a>
        <a href="{{ route('games.index') }}" 
           style="display: inline-block; padding: 12px 30px; background: var(--steam-dark); border: 2px solid var(--steam-blue); border-radius: 5px; color: white; font-weight: bold; text-decoration: none;">
            <i class="fas fa-gamepad"></i> Tiếp tục mua sắm
        </a>
    </div>
</div>

<script>
function copyKey(keyCode, button) {
    navigator.clipboard.writeText(keyCode).then(() => {
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i> Đã copy!';
        button.style.background = '#4c6b22';
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.style.background = 'var(--steam-blue)';
        }, 2000);
    });
}
</script>
@endsection
