<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình các thông tin thanh toán cho ứng dụng
    |
    */

    // Thông tin tài khoản ngân hàng nhận tiền
    'bank_bin' => env('BANK_BIN', '970436'), // VCB: 970436, ACB: 970416, VietinBank: 970415
    'bank_name' => env('BANK_NAME', 'Vietcombank'),
    'bank_branch' => env('BANK_BRANCH', 'Chi nhánh Thành phố Hồ Chí Minh'),
    'bank_account' => env('BANK_ACCOUNT', '1062858994'),
    'bank_account_name' => env('BANK_ACCOUNT_NAME', 'NGUYEN MINH KHOI'),

    // Template QR code: compact, compact2, qr_only, print
    'qr_template' => env('QR_TEMPLATE', 'compact2'),

    // Thời gian hết hạn thanh toán (phút)
    'payment_timeout' => env('PAYMENT_TIMEOUT', 15),

    // VNPay configuration (để sau)
    'vnpay' => [
        'tmn_code' => env('VNPAY_TMN_CODE', ''),
        'hash_secret' => env('VNPAY_HASH_SECRET', ''),
        'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'return_url' => env('VNPAY_RETURN_URL', ''),
        'api_url' => env('VNPAY_API_URL', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'),
    ],

];
