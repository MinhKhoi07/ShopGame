@extends('admin.layout')

<?php use Illuminate\Support\Str; ?>

@section('page-title', "Chat với " . $user->name)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div>
        <h2 style="color: white; margin: 0; font-size: 24px;">
            <i class="fas fa-comments"></i> Chat với {{ $user->name }}
        </h2>
        <p style="color: #9ca3af; margin: 8px 0 0 0;">{{ $user->email }}</p>
    </div>
    <a href="{{ route('admin.chats') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="chat-detail-container">
    <!-- Messages -->
    <div class="chat-messages" id="chat-messages">
        @forelse($messages as $message)
            <div class="message {{ $message->is_from_admin ? 'from-admin' : 'from-user' }}">
                <div class="message-content">{{ $message->message }}</div>
                <div class="message-time">{{ $message->created_at->format('H:i d/m/Y') }}</div>
            </div>
        @empty
            <div style="display: flex; align-items: center; justify-content: center; height: 300px; color: #9ca3af;">
                <div style="text-align: center;">
                    <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                    <p>Chưa có tin nhắn nào</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Reply Form -->
    <div class="chat-reply-section">
        @if (session('success'))
            <div class="alert alert-success" style="margin-bottom: 16px;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.chats.send', $user->id) }}" method="POST">
            @csrf
            <div style="display: flex; gap: 12px; align-items: flex-end;">
                <textarea 
                    name="message" 
                    id="reply-message" 
                    class="form-control" 
                    placeholder="Nhập tin nhắn..." 
                    rows="3"
                    maxlength="1000"
                    required
                    style="flex: 1; resize: vertical;"
                ></textarea>
                <button type="submit" class="btn btn-primary" style="min-width: 120px;">
                    <i class="fas fa-paper-plane"></i> Gửi
                </button>
            </div>
            @error('message')
                <span style="color: #ef4444; font-size: 14px; display: block; margin-top: 8px;">{{ $message }}</span>
            @enderror
        </form>
    </div>
</div>

<style>
    .chat-detail-container {
        display: flex;
        flex-direction: column;
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        min-height: 600px;
        max-width: 900px;
    }

    .chat-messages {
        flex: 1;
        padding: 20px;
        background: #f9fafb;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .message {
        display: flex;
        flex-direction: column;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message.from-user {
        align-items: flex-end;
    }

    .message.from-admin {
        align-items: flex-start;
    }

    .message-content {
        max-width: 70%;
        padding: 12px 16px;
        border-radius: 8px;
        line-height: 1.5;
        word-wrap: break-word;
    }

    .message.from-user .message-content {
        background: #667eea;
        color: white;
        border-bottom-right-radius: 2px;
    }

    .message.from-admin .message-content {
        background: white;
        color: #1f2937;
        border: 1px solid #e5e7eb;
        border-bottom-left-radius: 2px;
    }

    .message-time {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 6px;
        padding: 0 4px;
    }

    .chat-reply-section {
        padding: 20px;
        border-top: 1px solid #e5e7eb;
        background: white;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    @media (max-width: 768px) {
        .chat-detail-container {
            min-height: 500px;
            max-width: 100%;
        }

        .message-content {
            max-width: 90%;
        }
    }
</style>

<script>
    // Auto scroll to bottom
    const messagesContainer = document.getElementById('chat-messages');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Auto-reload messages every 5 seconds
    setInterval(function() {
        location.reload();
    }, 5000);
</script>
@endsection
