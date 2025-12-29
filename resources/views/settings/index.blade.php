@extends('layouts.app')

@section('title', 'Cài đặt tài khoản')

@section('content')
<div class="settings-page">
    <div class="settings-hero">
        <div>
            <p class="settings-kicker">Trung tâm cài đặt</p>
            <h1 class="settings-heading">Quản lý bảo mật, thanh toán, thông báo</h1>
            <p class="settings-subtitle">Ưu tiên an toàn và trải nghiệm mua hàng của bạn</p>
        </div>
    </div>

    @if($errors->any())
        <div class="settings-alert error">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Có lỗi cần kiểm tra:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @foreach(['success_security','success_billing','success_notifications'] as $flash)
        @if(session($flash))
            <div class="settings-alert success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session($flash) }}</span>
            </div>
        @endif
    @endforeach

    <div class="settings-grid">
        <!-- Bảo mật -->
        <section class="settings-card">
            <div class="settings-card-header">
                <div>
                    <p class="settings-card-kicker">Bảo mật</p>
                    <h2>Đổi mật khẩu & 2FA</h2>
                    <p class="settings-card-sub">Tăng cường an toàn tài khoản</p>
                </div>
            </div>
            <form action="{{ route('settings.security') }}" method="POST" class="settings-form">
                @csrf
                <div class="form-group">
                    <label>Email đăng nhập</label>
                    <input type="email" value="{{ $user->email }}" disabled>
                </div>
                <div class="form-group">
                    <label>Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Mật khẩu mới</label>
                        <input type="password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label>Nhập lại mật khẩu</label>
                        <input type="password" name="new_password_confirmation" required>
                    </div>
                </div>
                <div class="form-switches">
                    <label class="switch">
                        <input type="checkbox" name="two_factor_enabled" value="1" {{ $settings['security']['two_factor_enabled'] ? 'checked' : '' }}>
                        <span></span>
                        Bật xác thực 2 bước (tạm thời lưu tùy chọn)
                    </label>
                    <label class="switch">
                        <input type="checkbox" name="login_alerts" value="1" {{ $settings['security']['login_alerts'] ? 'checked' : '' }}>
                        <span></span>
                        Nhận cảnh báo khi có đăng nhập lạ
                    </label>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Lưu bảo mật</button>
                </div>
            </form>
        </section>

        <!-- Thanh toán & hóa đơn -->
        <section class="settings-card">
            <div class="settings-card-header">
                <div>
                    <p class="settings-card-kicker">Thanh toán</p>
                    <h2>Phương thức mặc định</h2>
                    <p class="settings-card-sub">Lưu địa chỉ và cách thanh toán</p>
                </div>
            </div>
            <form action="{{ route('settings.billing') }}" method="POST" class="settings-form">
                @csrf
                <div class="form-group">
                    <label>Tên hiển thị trên hóa đơn</label>
                    <input type="text" name="billing_name" value="{{ $settings['billing']['name'] }}" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="billing_phone" value="{{ $settings['billing']['phone'] }}">
                    </div>
                    <div class="form-group">
                        <label>Thành phố</label>
                        <input type="text" name="billing_city" value="{{ $settings['billing']['city'] }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>Địa chỉ thanh toán</label>
                    <input type="text" name="billing_address" value="{{ $settings['billing']['address'] }}">
                </div>
                <div class="form-group">
                    <label>Phương thức mặc định</label>
                    <select name="payment_method" required>
                        <option value="visa" {{ $settings['billing']['method'] === 'visa' ? 'selected' : '' }}>Thẻ Visa/Master</option>
                        <option value="momo" {{ $settings['billing']['method'] === 'momo' ? 'selected' : '' }}>Momo</option>
                        <option value="bank" {{ $settings['billing']['method'] === 'bank' ? 'selected' : '' }}>Chuyển khoản ngân hàng</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Lưu phương thức</button>
                </div>
            </form>
        </section>

        <!-- Thông báo -->
        <section class="settings-card">
            <div class="settings-card-header">
                <div>
                    <p class="settings-card-kicker">Thông báo</p>
                    <h2>Tùy chọn nhận tin</h2>
                    <p class="settings-card-sub">Kiểm soát email và push</p>
                </div>
            </div>
            <form action="{{ route('settings.notifications') }}" method="POST" class="settings-form">
                @csrf
                <div class="form-switches">
                    <label class="switch">
                        <input type="checkbox" name="notify_orders" value="1" {{ $settings['notifications']['orders'] ? 'checked' : '' }}>
                        <span></span>
                        Thông báo đơn hàng và hóa đơn
                    </label>
                    <label class="switch">
                        <input type="checkbox" name="notify_promotions" value="1" {{ $settings['notifications']['promotions'] ? 'checked' : '' }}>
                        <span></span>
                        Nhận khuyến mãi & flash sale
                    </label>
                    <label class="switch">
                        <input type="checkbox" name="notify_updates" value="1" {{ $settings['notifications']['updates'] ? 'checked' : '' }}>
                        <span></span>
                        Cập nhật game, sự kiện
                    </label>
                    <label class="switch">
                        <input type="checkbox" name="notify_push" value="1" {{ $settings['notifications']['push'] ? 'checked' : '' }}>
                        <span></span>
                        Thử nghiệm nhận push (nếu bật trên thiết bị)
                    </label>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Lưu thông báo</button>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection
