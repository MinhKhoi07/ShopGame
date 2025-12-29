@extends('layouts.app')

@section('title', $category->name . ' - Thể Loại')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Category Header -->
    <div class="mb-8">
        <nav class="text-sm text-gray-400 mb-4">
            <a href="{{ route('categories.index') }}" class="hover:text-white">
                <i class="fas fa-list mr-1"></i> Thể loại
            </a>
            <span class="mx-2">/</span>
            <span class="text-white">{{ $category->name }}</span>
        </nav>
        
        <div class="bg-gradient-to-r from-blue-900 to-purple-900 rounded-lg p-6">
            <h1 class="text-3xl font-bold text-white mb-2">
                <i class="fas fa-gamepad mr-2"></i> {{ $category->name }}
            </h1>
            @if($category->description)
            <p class="text-gray-300">{{ $category->description }}</p>
            @endif
            <p class="text-gray-400 mt-2">{{ $games->total() }} game</p>
        </div>
    </div>

    <!-- Games Grid -->
    @if($games->isEmpty())
        <div class="bg-gray-800 rounded-lg p-12 text-center">
            <i class="fas fa-ghost text-6xl text-gray-600 mb-4"></i>
            <p class="text-xl text-gray-400">Chưa có game nào trong thể loại này</p>
        </div>
    @else
        <style>
            .game-list {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }
            .game-card {
                display: grid;
                grid-template-columns: 120px 1fr auto;
                gap: 16px;
                align-items: center;
                background: #1f2937;
                border-radius: 12px;
                padding: 12px 16px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.25);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            .game-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 14px 30px rgba(0,0,0,0.35);
            }
            .game-thumb {
                flex-shrink: 0;
            }
            .game-thumb img {
                width: 120px;
                height: 80px;
                object-fit: cover;
                border-radius: 8px;
                background: #111827;
            }
            .game-info h3 {
                margin: 0 0 6px;
                font-size: 16px;
                font-weight: 600;
            }
            .game-info a {
                color: #fff;
                text-decoration: none;
            }
            .game-info a:hover {
                color: #3b82f6;
            }
            .price-info {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 14px;
                color: #9ca3af;
                margin-top: 4px;
            }
            .old-price {
                text-decoration: line-through;
            }
            .new-price {
                color: #fbbf24;
                font-weight: 700;
                font-size: 16px;
            }
            .game-actions {
                display: flex;
                gap: 8px;
                flex-shrink: 0;
            }
        </style>

        <div class="game-list">
            @foreach($games as $game)
            <div class="game-card">
                <div class="game-thumb">
                    <a href="{{ route('games.show', $game->slug) }}">
                        <img src="{{ $game->image_url }}" alt="{{ $game->name }}">
                    </a>
                </div>
                
                <div class="game-info">
                    <h3>
                        <a href="{{ route('games.show', $game->slug) }}">{{ $game->name }}</a>
                    </h3>
                    <div class="price-info">
                        @if($game->isOnSale())
                            <span class="old-price">{{ number_format($game->price) }}đ</span>
                            <span class="new-price">{{ number_format($game->price_sale) }}đ</span>
                            @php
                                $discount = round((($game->price - $game->price_sale) / $game->price) * 100);
                            @endphp
                            <span style="background: #dc2626; color: #fff; padding: 2px 6px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                -{{ $discount }}%
                            </span>
                        @else
                            <span class="new-price">{{ number_format($game->price) }}đ</span>
                        @endif
                    </div>
                </div>
                
                <div class="game-actions">
                    <form action="{{ route('cart.add', $game->id) }}" method="POST" class="add-to-cart-form" data-game-id="{{ $game->id }}">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors text-sm">
                            <i class="fas fa-shopping-cart mr-1"></i> Thêm
                        </button>
                    </form>
                    <a href="{{ route('games.show', $game->slug) }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded transition-colors text-sm">
                        <i class="fas fa-eye mr-1"></i> Chi tiết
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($games->hasPages())
        <div class="mt-8">
            {{ $games->links() }}
        </div>
        @endif
    @endif
</div>

<script>
// Add to cart handling
document.querySelectorAll('.add-to-cart-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const button = this.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Đang xử lý...';
        
        const gameId = this.dataset.gameId;
        const action = this.action;
        
        console.log('Form action:', action);
        console.log('Game ID:', gameId);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        fetch(action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Update cart count
                if (window.updateCartCount) {
                    window.updateCartCount();
                }
                
                // Show success message
                const message = document.createElement('div');
                message.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                message.innerHTML = '<i class="fas fa-check mr-2"></i> Đã thêm vào giỏ hàng!';
                document.body.appendChild(message);
                
                setTimeout(() => message.remove(), 2000);
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(err => {
            console.error('Lỗi chi tiết:', err);
            alert('Không thể thêm vào giỏ hàng. Vui lòng kiểm tra console để xem chi tiết lỗi!');
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
    });
});
</script>
@endsection
