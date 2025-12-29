<!-- Game Card Component -->
@props(['game'])

<div class="game-card {{ isset($game->price_sale) && $game->price_sale < $game->price ? 'sale' : '' }}">
    <a href="{{ route('games.show', $game->slug) }}" class="game-link">
        <div class="game-image">
            @if($game->thumbnail)
                <img src="{{ asset('storage/' . $game->thumbnail) }}" alt="{{ $game->name }}">
            @else
                <img src="https://via.placeholder.com/400x200?text={{ urlencode($game->name) }}" alt="{{ $game->name }}">
            @endif
            
            @if(isset($game->price_sale) && $game->price_sale < $game->price)
                @php
                    $discount = round((($game->price - $game->price_sale) / $game->price) * 100);
                @endphp
                <div class="discount-badge">-{{ $discount }}%</div>
            @endif
        </div>
        
        <div class="game-info">
            <h3 class="game-title">{{ $game->name }}</h3>
            
            @if($game->category)
                <div class="game-tags">
                    <span class="tag-small">{{ $game->category->name }}</span>
                </div>
            @endif
            
            <div class="game-price">
                @if($game->is_free || $game->price == 0)
                    <span class="sale-price" style="color: #beee11; font-weight: bold;">🎁 Miễn Phí</span>
                @elseif(isset($game->price_sale) && $game->price_sale < $game->price)
                    <span class="original-price">{{ number_format($game->price) }}đ</span>
                    <span class="sale-price">{{ number_format($game->price_sale) }}đ</span>
                @else
                    <span class="sale-price">{{ number_format($game->price) }}đ</span>
                @endif
            </div>

            <!-- Nút thêm vào giỏ hàng -->
            <form action="{{ route('cart.add', $game->id) }}" method="POST" class="add-to-cart-form" style="margin-top: 10px;" onclick="event.stopPropagation();">
                @csrf
                <button type="submit" class="btn btn-sm btn-primary add-to-cart-btn" style="width: 100%; padding: 8px; font-size: 13px;" data-game-id="{{ $game->id }}">
                    <i class="fas fa-cart-plus"></i> <span class="btn-text">Thêm vào giỏ</span>
                </button>
            </form>
        </div>
    </a>
</div>
