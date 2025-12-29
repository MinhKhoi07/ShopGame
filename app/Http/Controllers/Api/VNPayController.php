<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VNPayController extends Controller
{
    /**
     * Build a VNPay payment URL for Postman testing.
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1000',
            'order_code' => 'required|string|max:64',
            'order_info' => 'nullable|string|max:255',
            'bank_code' => 'nullable|string|max:20',
            'locale' => 'nullable|in:vn,en',
        ]);

        $config = config('payment.vnpay');

        if (empty($config['tmn_code']) || empty($config['hash_secret']) || empty($config['return_url'])) {
            return response()->json([
                'message' => 'VNPay config missing tmn_code/hash_secret/return_url in .env',
            ], 500);
        }

        $input = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => $config['tmn_code'],
            'vnp_Amount' => (int) ($data['amount'] * 100), // VNPay expects amount * 100
            'vnp_CurrCode' => 'VND',
            'vnp_TxnRef' => $data['order_code'],
            'vnp_OrderInfo' => $data['order_info'] ?? ('Thanh toan don hang ' . $data['order_code']),
            'vnp_Locale' => $data['locale'] ?? 'vn',
            'vnp_BankCode' => $data['bank_code'] ?? null,
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_IpAddr' => $request->ip(),
            'vnp_ReturnUrl' => $config['return_url'],
        ];

        // Remove nulls and sort for signing
        $input = array_filter($input, fn ($value) => !is_null($value));
        ksort($input);

        $query = http_build_query($input, '', '&', PHP_QUERY_RFC3986);
        $hashData = urldecode($query);
        $secureHash = hash_hmac('sha512', $hashData, $config['hash_secret']);
        $paymentUrl = rtrim($config['url'], '?') . '?' . $query . '&vnp_SecureHash=' . $secureHash;

        return response()->json([
            'payment_url' => $paymentUrl,
            'txn_ref' => $data['order_code'],
            'amount' => $data['amount'],
            'currency' => 'VND',
            'return_url' => $config['return_url'],
        ]);
    }

    /**
     * VNPay callback validator; echoes payload and signature status.
     */
    public function callback(Request $request)
    {
        $config = config('payment.vnpay');
        $params = $request->all();

        $receivedHash = $params['vnp_SecureHash'] ?? ($params['vnp_SecureHashType'] ?? null);
        unset($params['vnp_SecureHash'], $params['vnp_SecureHashType']);

        $verified = false;
        if ($config['hash_secret'] && $receivedHash) {
            ksort($params);
            $hashData = urldecode(http_build_query($params, '', '&', PHP_QUERY_RFC3986));
            $computedHash = hash_hmac('sha512', $hashData, $config['hash_secret']);
            $verified = hash_equals($computedHash, $receivedHash);
        }

        Log::info('vnpay.callback', [
            'verified' => $verified,
            'payload' => $request->all(),
        ]);

        return response()->json([
            'verified' => $verified,
            'payload' => $request->all(),
        ]);
    }
}
