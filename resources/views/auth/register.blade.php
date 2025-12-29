@extends('layouts.app')

@section('title', 'Đăng ký - ShopGame')

@section('content')
<div class="auth-container">
    <div class="auth-box">
        <div class="auth-header">
            <h2>Đăng ký tài khoản</h2>
            <p>Tạo tài khoản để trải nghiệm ShopGame</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf
            
            <div class="form-group">
                <label for="name">Họ và tên</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
                <small>Tối thiểu 8 ký tự</small>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Đăng ký</button>
        </form>

        <div class="auth-footer">
            <p>Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p>
        </div>
    </div>
</div>

<style>
.auth-container {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 50px 20px;
}

.auth-box {
    background: var(--steam-dark);
    border-radius: 8px;
    padding: 40px;
    max-width: 450px;
    width: 100%;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
}

.auth-header {
    text-align: center;
    margin-bottom: 30px;
}

.auth-header h2 {
    color: white;
    font-size: 28px;
    margin-bottom: 10px;
}

.auth-header p {
    color: var(--steam-text);
    font-size: 14px;
}

.alert {
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-error {
    background: rgba(220, 53, 69, 0.1);
    border: 1px solid #dc3545;
    color: #ff6b6b;
}

.alert p {
    margin: 0;
    font-size: 14px;
}

.auth-form {
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    color: var(--steam-text);
    margin-bottom: 8px;
    font-size: 14px;
}

.form-group input {
    width: 100%;
    padding: 12px 15px;
    background: var(--steam-darker);
    border: 1px solid var(--steam-border);
    border-radius: 5px;
    color: white;
    font-size: 14px;
}

.form-group input:focus {
    outline: none;
    border-color: var(--steam-blue);
}

.form-group small {
    display: block;
    color: var(--steam-text);
    font-size: 12px;
    margin-top: 5px;
}

.btn-full {
    width: 100%;
    justify-content: center;
}

.auth-footer {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid var(--steam-border);
}

.auth-footer p {
    color: var(--steam-text);
    font-size: 14px;
}

.auth-footer a {
    color: var(--steam-blue);
    text-decoration: none;
}

.auth-footer a:hover {
    color: var(--steam-hover);
}
</style>
@endsection
