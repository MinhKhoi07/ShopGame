@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    <h1 style="color: white; margin-bottom: 30px; font-size: 32px;">
        <i class="fas fa-credit-card"></i> Thanh toán
    </h1>

    @if(session('error'))
        <div style="background: #c84b31; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 400px; gap: 30px;">
        <!-- Form bên trái -->
        <div>
            <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                @csrf
                
                <!-- Thông tin khách hàng -->
                <div style="background: var(--steam-dark); padding: 25px; border-radius: 8px; margin-bottom: 20px;">
                    <h2 style="color: white; margin-bottom: 20px; font-size: 20px; border-bottom: 2px solid var(--steam-border); padding-bottom: 10px;">
                        <i class="fas fa-user"></i> Thông tin khách hàng
                    </h2>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: var(--steam-text); margin-bottom: 8px; font-weight: 500;">
                            Họ tên <span style="color: #ff4444;">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" 
                               required
                               style="width: 100%; padding: 12px; background: var(--steam-darker); border: 1px solid var(--steam-border); border-radius: 4px; color: white; font-size: 15px;">
                        @error('name')
                            <span style="color: #ff4444; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: var(--steam-text); margin-bottom: 8px; font-weight: 500;">
                            Email <span style="color: #ff4444;">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" 
                               required
                               style="width: 100%; padding: 12px; background: var(--steam-darker); border: 1px solid var(--steam-border); border-radius: 4px; color: white; font-size: 15px;">
                        @error('email')
                            <span style="color: #ff4444; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; color: var(--steam-text); margin-bottom: 8px; font-weight: 500;">
                            Số điện thoại
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" 
                               style="width: 100%; padding: 12px; background: var(--steam-darker); border: 1px solid var(--steam-border); border-radius: 4px; color: white; font-size: 15px;">
                        @error('phone')
                            <span style="color: #ff4444; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Phương thức thanh toán -->
                <div style="background: var(--steam-dark); padding: 25px; border-radius: 8px;">
                    <h2 style="color: white; margin-bottom: 20px; font-size: 20px; border-bottom: 2px solid var(--steam-border); padding-bottom: 10px;">
                        <i class="fas fa-wallet"></i> Phương thức thanh toán
                    </h2>

                    <div style="display: grid; gap: 15px;">
                        <!-- Chuyển khoản ngân hàng -->
                        <label style="display: flex; align-items: center; padding: 15px; background: var(--steam-darker); border: 2px solid var(--steam-border); border-radius: 8px; cursor: pointer; transition: all 0.3s;"
                               class="payment-method"
                               onclick="selectPayment(this, 'bank_transfer')">
                            <input type="radio" name="payment_method" value="bank_transfer" required
                                   style="margin-right: 15px; width: 20px; height: 20px;">
                            <div style="flex: 1;">
                                <div style="color: white; font-weight: 600; font-size: 16px; margin-bottom: 4px;">
                                    <i class="fas fa-university" style="color: var(--steam-blue);"></i> Chuyển khoản ngân hàng
                                </div>
                                <div style="color: var(--steam-text); font-size: 13px;">
                                    Chuyển khoản qua VCB, ACB, Vietcombank...
                                </div>
                            </div>
                        </label>

                        <!-- MoMo -->
                        <label style="display: flex; align-items: center; padding: 15px; background: var(--steam-darker); border: 2px solid var(--steam-border); border-radius: 8px; cursor: pointer; transition: all 0.3s;"
                               class="payment-method"
                               onclick="selectPayment(this, 'momo')">
                            <input type="radio" name="payment_method" value="momo" required
                                   style="margin-right: 15px; width: 20px; height: 20px;">
                            <div style="flex: 1;">
                                <div style="color: white; font-weight: 600; font-size: 16px; margin-bottom: 4px;">
                                    <i class="fas fa-mobile-alt" style="color: #d82d8b;"></i> Ví MoMo
                                </div>
                                <div style="color: var(--steam-text); font-size: 13px;">
                                    Thanh toán qua ví điện tử MoMo
                                </div>
                            </div>
                        </label>

                        <!-- ZaloPay -->
                        <label style="display: flex; align-items: center; padding: 15px; background: var(--steam-darker); border: 2px solid var(--steam-border); border-radius: 8px; cursor: pointer; transition: all 0.3s;"
                               class="payment-method"
                               onclick="selectPayment(this, 'zalopay')">
                            <input type="radio" name="payment_method" value="zalopay" required
                                   style="margin-right: 15px; width: 20px; height: 20px;">
                            <div style="flex: 1;">
                                <div style="color: white; font-weight: 600; font-size: 16px; margin-bottom: 4px;">
                                    <i class="fas fa-mobile-alt" style="color: #0180C7;"></i> ZaloPay
                                </div>
                                <div style="color: var(--steam-text); font-size: 13px;">
                                    Thanh toán qua ví điện tử ZaloPay
                                </div>
                            </div>
                        </label>

                        <!-- Thẻ tín dụng -->
                        <label style="display: flex; align-items: center; padding: 15px; background: var(--steam-darker); border: 2px solid var(--steam-border); border-radius: 8px; cursor: pointer; transition: all 0.3s;"
                               class="payment-method"
                               onclick="selectPayment(this, 'credit_card')">
                            <input type="radio" name="payment_method" value="credit_card" required
                                   style="margin-right: 15px; width: 20px; height: 20px;">
                            <div style="flex: 1;">
                                <div style="color: white; font-weight: 600; font-size: 16px; margin-bottom: 4px;">
                                    <i class="fas fa-credit-card" style="color: #ffa500;"></i> Thẻ tín dụng/Ghi nợ
                                </div>
                                <div style="color: var(--steam-text); font-size: 13px;">
                                    Visa, MasterCard, JCB
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tóm tắt đơn hàng bên phải -->
        <div>
            <div style="background: var(--steam-dark); padding: 25px; border-radius: 8px; position: sticky; top: 20px;">
                <h2 style="color: white; margin-bottom: 20px; font-size: 20px; border-bottom: 2px solid var(--steam-border); padding-bottom: 10px;">
                    <i class="fas fa-shopping-bag"></i> Đơn hàng của bạn
                </h2>

                <!-- Danh sách game -->
                <div style="margin-bottom: 20px; max-height: 300px; overflow-y: auto;">
                    @foreach($cartItems as $item)
                        <div style="display: flex; gap: 12px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--steam-border);">
                            <img src="{{ asset('storage/' . $item->game->thumbnail) }}" 
                                 alt="{{ $item->game->name }}"
                                 style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px;">
                            <div style="flex: 1;">
                                <div style="color: white; font-weight: 600; font-size: 14px; margin-bottom: 4px;">
                                    {{ $item->game->name }}
                                </div>
                                <div style="color: var(--steam-text); font-size: 13px;">
                                    {{ number_format($item->price) }}đ
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Tổng tiền -->
                <div style="border-top: 2px solid var(--steam-border); padding-top: 15px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: var(--steam-text);">Tạm tính:</span>
                        <span style="color: white; font-weight: 600;">{{ number_format($subtotal) }}đ</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                        <span style="color: var(--steam-text);">Thuế (10%):</span>
                        <span style="color: white; font-weight: 600;">{{ number_format($tax) }}đ</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding-top: 15px; border-top: 2px solid var(--steam-border);">
                        <span style="color: white; font-size: 18px; font-weight: bold;">Tổng cộng:</span>
                        <span style="color: var(--steam-blue); font-size: 20px; font-weight: bold;">{{ number_format($total) }}đ</span>
                    </div>
                </div>

                <!-- Nút thanh toán -->
                <button type="submit" form="checkoutForm"
                        style="width: 100%; margin-top: 20px; padding: 15px; background: var(--steam-gradient); border: none; border-radius: 5px; color: white; font-size: 16px; font-weight: bold; cursor: pointer; transition: all 0.3s;">
                    <i class="fas fa-lock"></i> Hoàn tất thanh toán
                </button>

                <div style="text-align: center; margin-top: 15px;">
                    <a href="{{ route('cart.index') }}" style="color: var(--steam-text); text-decoration: none; font-size: 14px;">
                        <i class="fas fa-arrow-left"></i> Quay lại giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectPayment(element, method) {
    // Remove active class from all
    document.querySelectorAll('.payment-method').forEach(el => {
        el.style.borderColor = 'var(--steam-border)';
        el.style.background = 'var(--steam-darker)';
    });
    
    // Add active to selected
    element.style.borderColor = 'var(--steam-blue)';
    element.style.background = '#1a2332';
}

// Auto-select first payment method
document.addEventListener('DOMContentLoaded', function() {
    const firstMethod = document.querySelector('.payment-method');
    if (firstMethod) {
        firstMethod.querySelector('input[type="radio"]').checked = true;
        selectPayment(firstMethod, firstMethod.querySelector('input[type="radio"]').value);
    }
});
</script>
@endsection
