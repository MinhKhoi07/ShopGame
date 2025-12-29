@extends('layouts.app')

@section('title', 'Liên hệ hỗ trợ')

@section('content')
<div class="container" style="padding: 30px 0;">
    <div style="display:grid; grid-template-columns: 1.2fr 0.8fr; gap: 24px; align-items: start;">
        <section style="background: rgba(255,255,255,0.05); border: 1px solid var(--steam-border); border-radius: 10px; padding: 24px;">
            <h1 style="margin:0 0 6px 0; color:#fff; font-size:24px;">Gửi liên hệ</h1>
            <p style="margin:0 0 16px 0; color:#8f98a0;">Bạn gặp vấn đề khi mua hàng, thanh toán, hay cần tư vấn? Hãy để lại lời nhắn cho chúng tôi.</p>

            @if(session('success'))
                <div class="settings-alert success" style="margin-bottom:16px;">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="settings-alert error" style="margin-bottom:16px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Vui lòng kiểm tra lại:</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @auth
            <form method="POST" action="{{ route('contact.store') }}" class="settings-form">
                @csrf
                <div class="form-group">
                    <label>Chủ đề</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required>
                </div>
                <div class="form-group">
                    <label>Nội dung</label>
                    <textarea name="message" rows="6" required>{{ old('message') }}</textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane"></i> Gửi liên hệ
                    </button>
                </div>
            </form>
            @else
            <div class="settings-alert" style="background: rgba(103,193,245,0.08); border: 1px solid rgba(103,193,245,0.3); color:#cfe8ff; margin-bottom:16px;">
                <i class="fas fa-info-circle"></i>
                <span>Vui lòng đăng nhập để gửi liên hệ. Điều này giúp chúng tôi hỗ trợ bạn nhanh hơn.</span>
            </div>
            <a href="{{ route('login') }}" class="btn btn-primary" style="display:inline-block; padding: 10px 16px;">
                <i class="fas fa-sign-in-alt"></i> Đăng nhập
            </a>
            <div style="margin-top:18px; color:#8f98a0; font-size:14px;">
                Bạn vẫn có thể xem thông tin liên hệ ở khung bên phải để liên hệ trực tiếp.
            </div>
            @endauth
        </section>

        <aside style="background: rgba(255,255,255,0.05); border: 1px solid var(--steam-border); border-radius: 10px; padding: 24px;">
            <h2 style="margin:0 0 12px 0; color:#fff; font-size:20px;">Thông tin liên hệ</h2>
            <ul style="list-style:none; padding:0; margin:0; display:grid; gap:12px;">
                <li style="display:flex; gap:12px; align-items:flex-start; color:#c6d4df;">
                    <i class="fas fa-envelope" style="color: var(--steam-blue); margin-top:4px;"></i>
                    <div>
                        <div style="color:#8f98a0; font-size:13px;">Email hỗ trợ</div>
                        <div>{{ config('contact.email') }}</div>
                    </div>
                </li>
                <li style="display:flex; gap:12px; align-items:flex-start; color:#c6d4df;">
                    <i class="fas fa-phone" style="color: var(--steam-blue); margin-top:4px;"></i>
                    <div>
                        <div style="color:#8f98a0; font-size:13px;">Hotline</div>
                        <div>{{ config('contact.hotline') }} ({{ config('contact.hours') }})</div>
                    </div>
                </li>
                <li style="display:flex; gap:12px; align-items:flex-start; color:#c6d4df;">
                    <i class="fas fa-clock" style="color: var(--steam-blue); margin-top:4px;"></i>
                    <div>
                        <div style="color:#8f98a0; font-size:13px;">Thời gian phản hồi</div>
                        <div>Trong vòng 24 giờ làm việc</div>
                    </div>
                </li>
                <li style="display:flex; gap:12px; align-items:flex-start; color:#c6d4df;">
                    <i class="fab fa-discord" style="color: var(--steam-blue); margin-top:4px;"></i>
                    <div>
                        <div style="color:#8f98a0; font-size:13px;">Cộng đồng</div>
                        <div><a href="{{ config('contact.discord') }}" style="color: var(--steam-blue); text-decoration: none;">Tham gia Discord</a></div>
                    </div>
                </li>
            </ul>

            <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.08); margin: 18px 0;">

            <div style="color:#8f98a0; font-size:13px;">Mẹo nhanh</div>
            <ul style="list-style: disc; margin:8px 0 0 20px; color:#c6d4df; display:grid; gap:6px;">
                <li>Mô tả chi tiết vấn đề và mã đơn hàng (nếu có).</li>
                <li>Đính kèm hình ảnh lỗi (sẽ hỗ trợ trong phản hồi).</li>
                <li>Ưu tiên dùng email đăng ký để chúng tôi xác thực nhanh.</li>
            </ul>
        </aside>
    </div>
</div>
@endsection
