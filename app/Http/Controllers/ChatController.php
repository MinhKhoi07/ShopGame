<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * Gửi tin nhắn từ khách hàng
     */
    public function sendMessage(Request $request)
    {
        try {
            Log::info('sendMessage called', ['request_body' => $request->getContent(), 'user' => Auth::id()]);
            
            $validated = $request->validate([
                'message' => 'required|string|max:1000',
            ]);

            if (!Auth::check()) {
                return response()->json(['error' => 'Vui lòng đăng nhập để gửi tin nhắn'], 401);
            }

            $msg = Message::create([
                'user_id' => Auth::id(),
                'message' => trim($validated['message']),
                'is_from_admin' => false,
                'is_read' => false,
            ]);

            Log::info('Chat message created', ['id' => $msg->id, 'user_id' => Auth::id()]);
            return response()->json(['success' => true, 'message' => 'Tin nhắn đã được gửi']);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            Log::warning('Validation error in sendMessage', ['errors' => $ve->errors()]);
            return response()->json(['error' => 'Validation error', 'details' => $ve->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Chat sendMessage error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Lỗi server: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Lấy tin nhắn của user hiện tại
     */
    public function getMessages()
    {
        try {
            if (!Auth::check()) {
                return response()->json(['messages' => []]);
            }

            $messages = Message::where('user_id', Auth::id())
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($msg) {
                    return [
                        'id' => $msg->id,
                        'message' => $msg->message,
                        'is_from_admin' => $msg->is_from_admin,
                        'is_read' => $msg->is_read,
                        'created_at' => $msg->created_at->format('H:i'),
                    ];
                });

            // Đánh dấu tất cả tin nhắn từ admin là đã đọc
            Message::where('user_id', Auth::id())
                ->where('is_from_admin', true)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json(['messages' => $messages]);
        } catch (\Exception $e) {
            return response()->json(['messages' => [], 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Admin: Danh sách conversations
     */
    public function adminChats()
    {
        // Lấy danh sách users có tin nhắn
        $conversations = User::whereHas('messages')
            ->with(['messages' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(1);
            }])
            ->withCount(['messages as unread_count' => function ($query) {
                $query->where('is_read', false)->where('is_from_admin', false);
            }])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.chats.index', compact('conversations'));
    }

    /**
     * Admin: Chi tiết conversation với user
     */
    public function adminChatDetail($userId)
    {
        $user = User::findOrFail($userId);
        $messages = Message::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Đánh dấu tin nhắn từ khách hàng là đã đọc
        Message::where('user_id', $userId)
            ->where('is_from_admin', false)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.chats.detail', compact('user', 'messages'));
    }

    /**
     * Admin: Gửi tin nhắn cho user
     */
    public function adminSendMessage(Request $request, $userId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        User::findOrFail($userId);

        Message::create([
            'user_id' => $userId,
            'message' => $request->message,
            'is_from_admin' => true,
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Tin nhắn đã được gửi');
    }
}
