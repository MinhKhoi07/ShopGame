<?php

namespace App\Services;

class VietQRService
{
    /**
     * Tạo URL QR code cho chuyển khoản ngân hàng
     * 
     * @param float $amount Số tiền
     * @param string $description Nội dung chuyển khoản
     * @return string URL của QR code
     */
    public static function generateQRUrl($amount, $description)
    {
        // Lấy thông tin từ config
        $bankBin = config('payment.bank_bin', '970436'); // VCB mặc định
        $bankAccount = config('payment.bank_account', '1062858994');
        $bankAccountName = config('payment.bank_account_name', 'NGUYEN MINH KHOI');
        $template = config('payment.qr_template', 'compact2'); // compact, compact2, qr_only, print

        // Format số tiền (bỏ dấu phẩy, chuyển thành số nguyên)
        $amountInt = (int) $amount;

        // Encode description để tránh lỗi URL
        $encodedDesc = urlencode($description);
        $encodedName = urlencode($bankAccountName);

        // Tạo URL VietQR
        // API: https://api.vietqr.io/image/{BANK_BIN}-{ACCOUNT_NO}-{TEMPLATE}.png
        $qrUrl = sprintf(
            'https://img.vietqr.io/image/%s-%s-%s.png?amount=%d&addInfo=%s&accountName=%s',
            $bankBin,
            $bankAccount,
            $template,
            $amountInt,
            $encodedDesc,
            $encodedName
        );

        return $qrUrl;
    }

    /**
     * Lấy thông tin ngân hàng
     * 
     * @return array
     */
    public static function getBankInfo()
    {
        return [
            'bank_name' => config('payment.bank_name', 'Vietcombank'),
            'bank_branch' => config('payment.bank_branch', 'Chi nhánh TP.HCM'),
            'account_no' => config('payment.bank_account', '1234567890'),
            'account_name' => config('payment.bank_account_name', 'NGUYEN VAN A'),
        ];
    }

    /**
     * Tạo nội dung chuyển khoản với mã đơn hàng
     * 
     * @param int $orderId
     * @return string
     */
    public static function generateTransferContent($orderId)
    {
        return sprintf('ShopGame DH%06d', $orderId);
    }
}
