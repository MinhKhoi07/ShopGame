@extends('layouts.app')

@section('title', 'Thanh toán bằng QR')

@section('content')
<div class="container" style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
    <h1 style="color: white; font-size: 28px; margin-bottom: 20px;">
        <i class="fas fa-qrcode"></i> Quét mã QR để thanh toán
    </h1>

    @if(session('error'))
        <div style="background: #c84b31; color: white; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 360px; gap: 30px;">
        <div style="background: var(--steam-dark); padding: 25px; border-radius: 8px;">
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="{{ $qrUrl }}" alt="VietQR" style="max-width: 320px; width: 100%; border-radius: 8px;">
            </div>
            <div style="color: var(--steam-text); text-align: center;">
                Vui lòng quét mã với app ngân hàng hoặc ví điện tử để chuyển khoản.
            </div>
        </div>

        <div style="background: var(--steam-dark); padding: 25px; border-radius: 8px;">
            <h2 style="color: white; font-size: 18px; margin-bottom: 15px;">Thông tin chuyển khoản</h2>
            <div style="display: grid; gap: 10px;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--steam-text);">Ngân hàng:</span>
                    <span style="color: white; font-weight: 600;">{{ $bankInfo['bank_name'] }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--steam-text);">Chi nhánh:</span>
                    <span style="color: white; font-weight: 600;">{{ $bankInfo['bank_branch'] }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--steam-text);">Số tài khoản:</span>
                    <span style="color: white; font-weight: 600;">{{ $bankInfo['account_no'] }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--steam-text);">Chủ tài khoản:</span>
                    <span style="color: white; font-weight: 600;">{{ $bankInfo['account_name'] }}</span>
                </div>
                <hr style="border-color: var(--steam-border);">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--steam-text);">Số tiền:</span>
                    <span style="color: var(--steam-blue); font-weight: bold;">{{ number_format($amount) }}đ</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--steam-text);">Nội dung:</span>
                    <span style="color: white; font-weight: 600;">{{ $transferContent }}</span>
                </div>
            </div>

            <div style="margin-top: 20px; color: var(--steam-text);">
                Thời hạn thanh toán: <strong id="countdown">15 phút</strong>
            </div>

            <div id="status-message" style="margin-top: 15px; padding: 12px; border-radius: 5px; display: none;"></div>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <a href="{{ route('checkout') }}" style="flex: 1; text-align: center; padding: 12px; background: var(--steam-dark); border: 2px solid var(--steam-border); border-radius: 5px; color: white; text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <button id="checkStatusBtn" onclick="checkOrderStatus()" style="flex: 1; padding: 12px; background: var(--steam-gradient); border: none; border-radius: 5px; color: white; cursor: pointer; font-weight: 600;">
                    <i class="fas fa-sync-alt"></i> Kiểm tra trạng thái
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Auto check status every 10 seconds
let checkInterval;
let countdownInterval;
let timeLeft = 15 * 60; // 15 minutes in seconds

function checkOrderStatus() {
    const btn = document.getElementById('checkStatusBtn');
    const statusMsg = document.getElementById('status-message');
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang kiểm tra...';
    
    console.log('Checking order status...');
    
    fetch('{{ route("checkout.checkStatus", $order->id) }}')
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Order status:', data);
            
            if (data.status === 'paid') {
                statusMsg.style.display = 'block';
                statusMsg.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                statusMsg.style.color = 'white';
                statusMsg.innerHTML = '<i class="fas fa-check-circle"></i> Thanh toán thành công! Đang chuyển hướng...';
                
                clearInterval(checkInterval);
                clearInterval(countdownInterval);
                
                setTimeout(() => {
                    window.location.href = '{{ route("checkout.success", $order->id) }}';
                }, 2000);
            } else if (data.status === 'cancelled') {
                statusMsg.style.display = 'block';
                statusMsg.style.background = '#c84b31';
                statusMsg.style.color = 'white';
                statusMsg.innerHTML = '<i class="fas fa-times-circle"></i> Đơn hàng đã bị hủy';
                
                clearInterval(checkInterval);
                clearInterval(countdownInterval);
            } else {
                statusMsg.style.display = 'block';
                statusMsg.style.background = '#1e2837';
                statusMsg.style.color = 'var(--steam-text)';
                statusMsg.innerHTML = '<i class="fas fa-clock"></i> Đơn hàng đang chờ xác nhận thanh toán';
                
                setTimeout(() => {
                    statusMsg.style.display = 'none';
                }, 3000);
            }
            
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sync-alt"></i> Kiểm tra trạng thái';
        })
        .catch(error => {
            console.error('Error:', error);
            statusMsg.style.display = 'block';
            statusMsg.style.background = '#c84b31';
            statusMsg.style.color = 'white';
            statusMsg.innerHTML = '<i class="fas fa-exclamation-circle"></i> Có lỗi xảy ra. Vui lòng thử lại.';
            
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sync-alt"></i> Kiểm tra trạng thái';
        });
}

function updateCountdown() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    document.getElementById('countdown').textContent = `${minutes} phút ${seconds} giây`;
    
    if (timeLeft <= 0) {
        clearInterval(countdownInterval);
        clearInterval(checkInterval);
        document.getElementById('status-message').style.display = 'block';
        document.getElementById('status-message').style.background = '#c84b31';
        document.getElementById('status-message').style.color = 'white';
        document.getElementById('status-message').innerHTML = '<i class="fas fa-clock"></i> Hết thời gian thanh toán!';
    }
    
    timeLeft--;
}

// Start auto check every 10 seconds
checkInterval = setInterval(checkOrderStatus, 10000);

// Start countdown
countdownInterval = setInterval(updateCountdown, 1000);
updateCountdown();
</script>
@endsection