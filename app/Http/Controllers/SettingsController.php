<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SettingsController extends Controller
{
    public function index()
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }
        $settings = $this->loadSettings($user->id);

        return view('settings.index', compact('user', 'settings'));
    }

    public function updateSecurity(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }
        $settings = $this->loadSettings($user->id);

        $data = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'confirmed', Password::min(8)],
            'two_factor_enabled' => ['nullable', 'boolean'],
            'login_alerts' => ['nullable', 'boolean'],
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng'])->withInput();
        }

        // Gán trực tiếp để Intelephense không cảnh báo phương thức update
        $user->password = $data['new_password'];
        $user->save();

        $settings['security']['two_factor_enabled'] = (bool)($data['two_factor_enabled'] ?? false);
        $settings['security']['login_alerts'] = (bool)($data['login_alerts'] ?? false);
        $this->saveSettings($user->id, $settings);

        return back()->with('success_security', 'Đã cập nhật bảo mật tài khoản');
    }

    public function updateBilling(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }
        $settings = $this->loadSettings($user->id);

        $data = $request->validate([
            'billing_name' => ['required', 'string', 'max:255'],
            'billing_phone' => ['nullable', 'string', 'max:50'],
            'billing_address' => ['nullable', 'string', 'max:255'],
            'billing_city' => ['nullable', 'string', 'max:120'],
            'payment_method' => ['required', 'string', 'in:visa,momo,bank'],
        ]);

        $settings['billing'] = [
            'name' => $data['billing_name'],
            'phone' => $data['billing_phone'] ?? '',
            'address' => $data['billing_address'] ?? '',
            'city' => $data['billing_city'] ?? '',
            'method' => $data['payment_method'],
        ];

        $this->saveSettings($user->id, $settings);

        return back()->with('success_billing', 'Đã lưu phương thức thanh toán mặc định');
    }

    public function updateNotifications(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }
        $settings = $this->loadSettings($user->id);

        $data = $request->validate([
            'notify_orders' => ['nullable', 'boolean'],
            'notify_promotions' => ['nullable', 'boolean'],
            'notify_updates' => ['nullable', 'boolean'],
            'notify_push' => ['nullable', 'boolean'],
        ]);

        $settings['notifications'] = [
            'orders' => (bool)($data['notify_orders'] ?? false),
            'promotions' => (bool)($data['notify_promotions'] ?? false),
            'updates' => (bool)($data['notify_updates'] ?? false),
            'push' => (bool)($data['notify_push'] ?? false),
        ];

        $this->saveSettings($user->id, $settings);

        return back()->with('success_notifications', 'Đã cập nhật tùy chọn thông báo');
    }

    private function loadSettings(int $userId): array
    {
        $path = $this->settingsPath($userId);
        if (Storage::exists($path)) {
            $json = json_decode(Storage::get($path), true);
            if (is_array($json)) {
                return array_replace_recursive($this->defaults(), $json);
            }
        }
        return $this->defaults();
    }

    private function saveSettings(int $userId, array $settings): void
    {
        $path = $this->settingsPath($userId);
        Storage::put($path, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function settingsPath(int $userId): string
    {
        return "user_settings/{$userId}.json";
    }

    private function defaults(): array
    {
        return [
            'security' => [
                'two_factor_enabled' => false,
                'login_alerts' => false,
            ],
            'billing' => [
                'name' => '',
                'phone' => '',
                'address' => '',
                'city' => '',
                'method' => 'visa',
            ],
            'notifications' => [
                'orders' => true,
                'promotions' => false,
                'updates' => true,
                'push' => false,
            ],
        ];
    }
}
