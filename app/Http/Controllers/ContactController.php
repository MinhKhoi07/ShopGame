<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Hiển thị trang liên hệ.
     */
    public function index()
    {
        return view('contact.index');
    }

    /**
     * Xử lý gửi liên hệ.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        // Đảm bảo user đăng nhập (route đã gắn middleware('auth'))
        $userId = Auth::id();

        // Lưu vào bảng messages như một ticket hỗ trợ
        $msg = new Message();
        $msg->user_id = $userId;
        $msg->subject = $validated['subject'];
        $msg->message = trim($validated['message']);
        $msg->status = 'open';
        $msg->is_from_admin = false;
        $msg->is_read = false;
        $msg->save();

        return redirect()->route('contact.index')->with('success', 'Đã gửi liên hệ. Chúng tôi sẽ phản hồi sớm nhất!');
    }
}
