@extends('layouts.app')

@section('title', 'Giỏ hàng của bạn')

@section('content')
<div class="container" style="max-width: 1400px; margin: 40px auto; padding: 0 20px;">
    
    <!-- Breadcrumb -->
    <div style="margin-bottom: 20px;">
        <a href="{{ route('home') }}" style="color: var(--steam-blue); text-decoration: none;">Trang chủ</a>
        <span style="color: var(--steam-text); margin: 0 10px;">></span>
        <span style="color: white;">Giỏ hàng của bạn</span>
    </div>

    <h1 style="color: white; margin-bottom: 30px; font-size: 32px;">Giỏ hàng của bạn</h1>

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

    @if($cartItems->isEmpty())
        <!-- Giỏ hàng trống -->
        <div style="background: var(--steam-dark); padding: 60px; text-align: center; border-radius: 8px;">
            <i class="fas fa-shopping-cart" style="font-size: 64px; color: var(--steam-text); margin-bottom: 20px;"></i>
            <h2 style="color: white; margin-bottom: 15px;">Giỏ hàng của bạn đang trống</h2>
            <p style="color: var(--steam-text); margin-bottom: 25px;">Hãy thêm game vào giỏ hàng để tiếp tục mua sắm!</p>
            <a href="{{ route('home') }}" class="btn btn-primary" style="padding: 12px 30px;">
                <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
            </a>
        </div>
    @else
        <!-- Nếu có vàng cảnh báo về giá thay đổi -->
        @if(session('price_changed'))
            <div style="background: #FFA500; color: #000; padding: 15px 20px; border-radius: 5px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-exclamation-triangle"></i>
                <span><strong>Lưu ý:</strong> Một hoặc nhiều sản phẩm dưới đây đã thay đổi giá kể từ khi bạn thêm vào giỏ hàng.</span>
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 1fr 400px; gap: 30px;">
            <!-- Danh sách sản phẩm -->
            <div>
                <div style="color: var(--steam-text); margin-bottom: 15px;">
                    {{ $cartItems->count() }} sản phẩm
                </div>

                @foreach($cartItems as $item)
                    @php
                        $game = $item->game;
                        $currentPrice = $game->price_sale ?? $game->price;
                        $originalPrice = $game->price;
                        $hasDiscount = $game->price_sale && $game->price_sale < $game->price;
                        $discountPercent = $hasDiscount ? round((($originalPrice - $currentPrice) / $originalPrice) * 100) : 0;
                    @endphp

                    <div class="cart-item" style="background: var(--steam-dark); padding: 20px; margin-bottom: 15px; border-radius: 5px; display: flex; gap: 20px; align-items: center;">
                        <!-- Ảnh game -->
                        <div style="flex-shrink: 0;">
                            <a href="{{ route('games.show', $game->slug) }}">
                                <img src="{{ asset('storage/' . $game->thumbnail) }}" 
                                     alt="{{ $game->name }}" 
                                     style="width: 180px; height: 100px; object-fit: cover; border-radius: 3px;">
                            </a>
                        </div>

                        <!-- Thông tin game -->
                        <div style="flex: 1;">
                            <h3 style="margin: 0 0 10px 0;">
                                <a href="{{ route('games.show', $game->slug) }}" style="color: white; text-decoration: none; font-size: 18px;">
                                    {{ $game->name }}
                                </a>
                            </h3>

                            <!-- Platform icons -->
                            <div style="margin-bottom: 10px;">
                                <i class="fab fa-windows" style="color: var(--steam-text);"></i>
                                <i class="fab fa-apple" style="color: var(--steam-text); margin-left: 5px;"></i>
                                <i class="fab fa-linux" style="color: var(--steam-text); margin-left: 5px;"></i>
                            </div>
                        </div>

                        <!-- Giá và nút -->
                        <div style="text-align: right; min-width: 200px;">
                            @if($hasDiscount)
                                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; margin-bottom: 10px;">
                                    <div style="background: #4c6b22; color: #a4d007; padding: 4px 8px; border-radius: 3px; font-weight: bold;">
                                        -{{ $discountPercent }}%
                                    </div>
                                    <div>
                                        <div style="color: var(--steam-text); text-decoration: line-through; font-size: 13px;">
                                            {{ number_format($originalPrice) }}đ
                                        </div>
                                        <div style="color: #a4d007; font-size: 18px; font-weight: bold;">
                                            {{ number_format($currentPrice) }}đ
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div style="color: white; font-size: 18px; font-weight: bold; margin-bottom: 10px;">
                                    {{ number_format($currentPrice) }}đ
                                </div>
                            @endif

                            <!-- Nút Gỡ bỏ -->
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: var(--steam-text); cursor: pointer; text-decoration: underline; font-size: 13px;">
                                    Gỡ bỏ
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach

                <!-- Nút xóa tất cả -->
                <div style="margin-top: 20px;">
                    <form action="{{ route('cart.clear') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa tất cả sản phẩm?')" 
                                style="background: var(--steam-darker); border: 1px solid var(--steam-border); color: var(--steam-text); padding: 10px 20px; border-radius: 3px; cursor: pointer;">
                            Gỡ bỏ tất cả sản phẩm
                        </button>
                    </form>
                </div>
            </div>

            <!-- Tổng ước tính -->
            <div>
                <div style="background: var(--steam-dark); padding: 25px; border-radius: 5px; position: sticky; top: 80px;">
                    <h3 style="color: white; margin: 0 0 20px 0; font-size: 18px;">Tổng ước tính</h3>
                    
                    <div style="color: var(--steam-text); font-size: 13px; margin-bottom: 20px; line-height: 1.6;">
                        Thuế tiêu thu sẽ được tính trong quá trình thanh toán nếu có
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                        <span style="color: var(--steam-text);">Tạm tính:</span>
                        <span style="color: white; font-weight: bold;">{{ number_format($subtotal) }}đ</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--steam-border);">
                        <span style="color: var(--steam-text);">Thuế (10%):</span>
                        <span style="color: white; font-weight: bold;">{{ number_format($tax) }}đ</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 25px;">
                        <span style="color: white; font-size: 18px; font-weight: bold;">Tổng cộng:</span>
                        <span style="color: white; font-size: 20px; font-weight: bold;">{{ number_format($total) }}đ</span>
                    </div>

                    <a href="{{ route('checkout') }}" class="btn btn-primary" style="width: 100%; text-align: center; padding: 15px; font-size: 16px; display: block; text-decoration: none;">
                        Tiếp tục tới bước thanh toán
                    </a>

                    <div style="margin-top: 15px; text-align: center;">
                        <a href="{{ route('home') }}" style="color: var(--steam-blue); text-decoration: none; font-size: 14px;">
                            Tiếp tục mua sắm
                        </a>
                    </div>

                    <!-- Hình minh họa -->
                    <div style="margin-top: 30px; text-align: center;">
                        <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=400&h=300&fit=crop" 
                             alt="Gaming" 
                             style="width: 100%; border-radius: 5px; opacity: 0.7;">
                    </div>

                    <div style="color: var(--steam-text); font-size: 12px; margin-top: 20px; line-height: 1.5;">
                        Đơn hàng sản phẩm kỹ thuật số sẽ trao giấy phép sử dụng sản phẩm trên Steam.
                        <br><br>
                        Để biết đầy đủ các điều khoản và điều kiện, vui lòng đọc 
                        <a href="#" style="color: var(--steam-blue);">Thỏa thuận người dùng kỹ thuật số</a>.
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Khuyến nghị cho bạn -->
    @if($recommendedGames->count() > 0)
        <div style="margin-top: 60px;">
            <h2 style="color: white; margin-bottom: 25px; font-size: 24px; text-transform: uppercase; letter-spacing: 2px;">
                Khuyến nghị cho bạn
            </h2>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                @foreach($recommendedGames as $game)
                    @php
                        $currentPrice = $game->price_sale ?? $game->price;
                        $originalPrice = $game->price;
                        $hasDiscount = $game->price_sale && $game->price_sale < $game->price;
                        $discountPercent = $hasDiscount ? round((($originalPrice - $currentPrice) / $originalPrice) * 100) : 0;
                    @endphp

                    <div style="background: var(--steam-dark); border-radius: 5px; overflow: hidden; transition: transform 0.2s;">
                        <a href="{{ route('games.show', $game->slug) }}" style="text-decoration: none; color: inherit;">
                            <div style="position: relative;">
                                <img src="{{ asset('storage/' . $game->thumbnail) }}" 
                                     alt="{{ $game->name }}" 
                                     style="width: 100%; height: 200px; object-fit: cover;">
                            </div>
                            
                            <div style="padding: 15px;">
                                <h3 style="color: white; margin: 0 0 15px 0; font-size: 16px; min-height: 40px;">
                                    {{ $game->name }}
                                </h3>

                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <!-- Platform icons -->
                                    <div>
                                        <i class="fab fa-windows" style="color: var(--steam-text); font-size: 14px;"></i>
                                    </div>

                                    <!-- Giá -->
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        @if($hasDiscount)
                                            <div style="background: #4c6b22; color: #a4d007; padding: 4px 8px; border-radius: 3px; font-weight: bold; font-size: 14px;">
                                                -{{ $discountPercent }}%
                                            </div>
                                            <div>
                                                <div style="color: var(--steam-text); text-decoration: line-through; font-size: 12px;">
                                                    {{ number_format($originalPrice) }}đ
                                                </div>
                                                <div style="color: #a4d007; font-size: 16px; font-weight: bold;">
                                                    {{ number_format($currentPrice) }}đ
                                                </div>
                                            </div>
                                        @else
                                            <div style="color: white; font-size: 16px; font-weight: bold;">
                                                {{ number_format($currentPrice) }}đ
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    
    <!-- Đơn hàng chờ xác nhận -->
    @if($pendingOrders->isNotEmpty())
        <div style="margin-top: 60px;">
            <h2 style="color: white; margin-bottom: 20px; font-size: 24px;">
                <i class="fas fa-clock"></i> Chờ xác nhận thanh toán
            </h2>
            
            @foreach($pendingOrders as $order)
                <div style="background: var(--steam-dark); padding: 20px; margin-bottom: 15px; border-radius: 8px; border-left: 4px solid #f5a623;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                        <div>
                            <div style="color: white; font-size: 16px; font-weight: bold; margin-bottom: 5px;">
                                Đơn hàng #{{ $order->id }}
                            </div>
                            <div style="color: var(--steam-text); font-size: 13px;">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="color: var(--steam-blue); font-size: 20px; font-weight: bold;">
                                {{ number_format($order->total_amount) }}đ
                            </div>
                            <div style="background: #f5a623; color: #000; padding: 4px 12px; border-radius: 3px; font-size: 12px; font-weight: 600; margin-top: 5px;">
                                Chờ xác nhận
                            </div>
                        </div>
                    </div>
                    
                    <div style="border-top: 1px solid var(--steam-border); padding-top: 15px; margin-bottom: 15px;">
                        <div style="color: var(--steam-text); font-size: 13px; margin-bottom: 10px;">
                            Sản phẩm:
                        </div>
                        @foreach($order->orderItems as $item)
                            <div style="color: white; margin-bottom: 5px; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-gamepad" style="color: var(--steam-blue);"></i>
                                {{ $item->game->name }}
                            </div>
                        @endforeach
                    </div>
                    
                    <div style="display: flex; gap: 10px;">
                        @if($order->payment_method === 'bank_transfer')
                            <a href="{{ route('checkout.success', $order->id) }}" 
                               style="flex: 1; padding: 10px 20px; background: var(--steam-gradient); color: white; text-decoration: none; border-radius: 5px; text-align: center; font-weight: 600;">
                                <i class="fas fa-qrcode"></i> Xem QR thanh toán
                            </a>
                        @endif
                        <a href="{{ route('checkout.success', $order->id) }}" 
                           style="flex: 1; padding: 10px 20px; background: rgba(103, 193, 245, 0.1); color: var(--steam-blue); text-decoration: none; border-radius: 5px; text-align: center; border: 1px solid var(--steam-blue);">
                            <i class="fas fa-eye"></i> Chi tiết
                        </a>
                        <form method="POST" action="{{ route('order.cancel', $order->id) }}" style="flex: 1;" 
                              onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">
                            @csrf
                            <button type="submit" 
                                    style="width: 100%; padding: 10px 20px; background: transparent; color: #c84b31; border: 1px solid #c84b31; border-radius: 5px; cursor: pointer; font-weight: 600;">
                                <i class="fas fa-times"></i> Hủy đơn
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .cart-item:hover {
        background: rgba(103, 193, 245, 0.05) !important;
    }
</style>
@endsection
