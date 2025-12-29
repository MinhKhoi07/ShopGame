@extends('admin.layout')

<?php use Illuminate\Support\Str; ?>

@section('page-title', 'Quản lý Chat')

@section('content')
<div class="chat-list-section">
    <div class="chat-list-header">
        <h2 style="color: white; margin-bottom: 0;">
            <i class="fas fa-comments"></i> Cuộc trò chuyện
        </h2>
        <span class="chat-count" style="background: rgba(255,255,255,0.2); color: white;">{{ $conversations->count() }} cuộc trò chuyện</span>
    </div>

            @if($conversations->count() > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Người dùng</th>
                                <th>Tin nhắn gần nhất</th>
                                <th>Thời gian</th>
                                <th>Tin mới</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($conversations as $user)
                                <tr>
                                    <td><strong>{{ $user->name }}</strong></td>
                                    <td>{{ Str::limit($user->messages->first()?->message ?? 'Không có tin nhắn', 40) }}</td>
                                    <td>{{ $user->messages->first()?->created_at?->diffForHumans() ?? '-' }}</td>
                                    <td>
                                        @if($user->unread_count > 0)
                                            <span class="badge badge-danger">{{ $user->unread_count }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.chats.detail', $user->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="padding: 40px 20px; text-align: center; color: #9ca3af;">
                    <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 20px; opacity: 0.5;"></i>
                    <p>Chưa có cuộc trò chuyện nào</p>
                </div>
            @endif
        </div>
</div>

<style>
    .chat-list-section {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .chat-list-header {
        padding: 20px;
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-list-header h2 {
        margin: 0;
        font-size: 18px;
        color: white;
    }

    .chat-count {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        white-space: nowrap;
    }

    .table-container {
        overflow-x: auto;
    }

    .table-container table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-container table thead {
        background: #f3f4f6;
    }

    .table-container table th {
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
    }

    .table-container table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.2s;
    }

    .table-container table tbody tr:hover {
        background: #f9fafb;
    }

    .table-container table td {
        padding: 12px 16px;
        color: #374151;
    }

    .badge-danger {
        background: #fecaca;
        color: #7f1d1d;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    .text-muted {
        color: #9ca3af;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }
</style>
@endsection