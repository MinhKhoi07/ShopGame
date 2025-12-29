@extends('layouts.app')

@section('title', $game->name . ' - Cửa hàng Game')

@section('content')
<div class="bg-gray-900 min-h-screen">
    <!-- Breadcrumb -->
    <div class="container mx-auto px-4 py-4">
        <nav class="text-sm text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-white">Trang chủ</a>
            <span class="mx-2">/</span>
            <a href="{{ route('games.index') }}" class="hover:text-white">Game</a>
            @if($game->category)
            <span class="mx-2">/</span>
            <a href="{{ route('categories.show', $game->category->id) }}" class="hover:text-white">{{ $game->category->name }}</a>
            @endif
            <span class="mx-2">/</span>
            <span class="text-white">{{ $game->name }}</span>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Top Section: Image (Left) + Info (Right) -->
        <div class="flex flex-col lg:flex-row gap-8 mb-10 items-start">
            <!-- Left: Game Image -->
            <div class="w-full lg:w-1/2 max-w-xl shrink-0">
                <div class="bg-black rounded-lg overflow-hidden lg:sticky lg:top-16">
                    <div class="h-64 mx-auto w-full flex items-center justify-center bg-gray-800 relative">
                        @if($game->image_url)
                        <img src="{{ $game->image_url }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                        @else
                        <span class="text-gray-400 text-center">{{ $game->name }}</span>
                        @endif
                        
                        @if($game->isOnSale())
                        @php
                            $discount = round((($game->price - $game->price_sale) / $game->price) * 100);
                        @endphp
                        <div class="absolute top-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg font-bold">
                            -{{ $discount }}%
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Thumbnail Gallery -->
                @if(is_array($game->images) && count($game->images) > 1)
                <div class="mt-3 grid grid-cols-4 gap-2">
                    @foreach($game->images as $index => $image)
                    <div class="aspect-video h-16 bg-gray-800 rounded cursor-pointer overflow-hidden hover:ring-2 hover:ring-blue-500 transition-all"
                         onclick="document.querySelector('img[alt=&quot;{{ $game->name }}&quot;]').src = '{{ $image }}'">
                        <img src="{{ $image }}" alt="Ảnh {{ $index + 1 }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Right: Game Info & Purchase -->
            <div class="flex-1 w-full">
                <!-- Title -->
                <h1 class="text-4xl font-bold text-white mb-4">{{ $game->name }}</h1>

                <!-- Category & Rating -->
                <div class="flex flex-wrap gap-4 mb-6">
                    @if($game->category)
                    <div class="bg-gray-800 px-4 py-2 rounded">
                        <span class="text-gray-400 text-sm">Thể loại:</span>
                        <span class="text-white font-semibold ml-2">{{ $game->category->name }}</span>
                    </div>
                    @endif

                    <div class="bg-gray-800 px-4 py-2 rounded flex items-center gap-2">
                        <div class="text-yellow-400 flex gap-0.5">
                            @php
                                $rating = $averageRating ?? 0;
                                for($i = 0; $i < 5; $i++) {
                                    echo $i < floor($rating) ? '<i class="fas fa-star text-sm"></i>' : '<i class="far fa-star text-sm"></i>';
                                }
                            @endphp
                        </div>
                        <span class="text-white font-semibold">{{ number_format($rating ?? 0, 1) }}/5</span>
                        <span class="text-gray-400 text-sm">({{ $totalReviews }})</span>
                    </div>
                </div>

                <!-- Price Section -->
                <div class="bg-gray-800 rounded-lg p-6 mb-6">
                    <div class="mb-4">
                        @if($game->isOnSale())
                        <p class="text-gray-400 text-sm mb-2">Giá gốc: <span class="line-through">{{ number_format($game->price) }}đ</span></p>
                        <p class="text-4xl font-bold text-white">{{ number_format($game->price_sale) }}đ</p>
                        @else
                        <p class="text-4xl font-bold text-white">{{ number_format($game->price) }}đ</p>
                        @endif
                    </div>

                    <!-- Stock -->
                    @if($availableKeysCount > 0)
                    <div class="mb-6 pb-6 border-b border-gray-700">
                        <p class="text-green-400 font-semibold">✓ {{ $availableKeysCount }} bản còn lại</p>
                    </div>
                    @endif

                    <!-- Buttons -->
                    @if($hasAvailableKeys)
                    <form action="{{ route('cart.add', $game->id) }}" method="POST" class="add-to-cart-form mb-3">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng
                        </button>
                    </form>

                    <button class="w-full border-2 border-red-500 text-red-400 hover:text-red-300 font-bold py-2 px-6 rounded-lg transition-colors">
                        <i class="fas fa-heart mr-2"></i>Yêu thích
                    </button>
                    @endif
                </div>

                <!-- Game Details -->
                <div class="bg-gray-800 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-white mb-4">Thông tin game</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        @if($game->developer)
                        <div>
                            <p class="text-gray-400">Nhà phát triển</p>
                            <p class="text-white font-semibold">{{ $game->developer }}</p>
                        </div>
                        @endif

                        @if($game->created_at)
                        <div>
                            <p class="text-gray-400">Ngày phát hành</p>
                            <p class="text-white font-semibold">{{ $game->created_at->format('d/m/Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <div class="bg-gray-800 rounded-lg p-8 mb-12">
            <h2 class="text-3xl font-bold text-white mb-4">Về game</h2>
            @if($game->description)
            <div class="text-gray-300 leading-relaxed whitespace-pre-wrap">
                {!! nl2br(e($game->description)) !!}
            </div>
            @else
            <p class="text-gray-400">Chưa có mô tả chi tiết</p>
            @endif
        </div>

        <!-- System Requirements -->
        @if($game->system_requirements)
        <div class="bg-gray-800 rounded-lg p-8 mb-12">
            <h2 class="text-3xl font-bold text-white mb-4">Yêu cầu hệ thống</h2>
            <div class="text-gray-300 whitespace-pre-wrap">
                {!! nl2br(e($game->system_requirements)) !!}
            </div>
        </div>
        @endif

        <!-- Reviews Section -->
        <div class="mb-12">
            <h2 class="text-3xl font-bold text-white mb-6">Đánh giá từ người chơi</h2>

            @auth
            @if($owned)
            <div class="bg-gray-800 rounded-lg p-6 mb-6">
                @if(session('review_success'))
                    <div class="mb-4 text-green-400">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('review_success') }}
                    </div>
                @endif
                @if($errors->has('review') || $errors->has('rating') || $errors->has('comment'))
                    <div class="mb-4 text-red-400">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ $errors->first('review') ?? $errors->first('rating') ?? $errors->first('comment') }}
                    </div>
                @endif
                <h3 class="text-xl font-semibold text-white mb-3">Đánh giá của bạn</h3>
                <form method="POST" action="{{ route('reviews.store', $game->id) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-gray-300 mb-2">Chấm sao</label>
                        <div class="flex items-center gap-2">
                            @for($i=1; $i<=5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" class="hidden" {{ old('rating', $userReview->rating ?? 5) == $i ? 'checked' : '' }}>
                                    <i class="fa{{ old('rating', $userReview->rating ?? 5) >= $i ? 's' : 'r' }} fa-star text-yellow-400 text-2xl rating-star" data-value="{{ $i }}"></i>
                                </label>
                            @endfor
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-300 mb-2">Nhận xét (tuỳ chọn)</label>
                        <textarea name="comment" rows="4" class="w-full bg-gray-900 border border-gray-700 rounded p-3 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-600">{{ old('comment', $userReview->comment ?? '') }}</textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded">
                        {{ $userReview ? 'Cập nhật đánh giá' : 'Gửi đánh giá' }}
                    </button>
                </form>
            </div>
            @endif
            @endauth

            @if($totalReviews > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                @foreach($reviews->take(6) as $review)
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                            {{ substr($review->user->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-white">{{ $review->user->name ?? 'Ẩn danh' }}</h4>
                            <p class="text-gray-400 text-xs">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    
                    <div class="flex gap-1 text-yellow-400 mb-3">
                        @for($i = 0; $i < 5; $i++)
                            @if($i < ($review->rating ?? 0))
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    
                    <p class="text-gray-300 text-sm">{{ $review->comment }}</p>
                </div>
                @endforeach
            </div>

            @if($reviews->hasPages())
            <div class="flex justify-center">
                {{ $reviews->links() }}
            </div>
            @endif
            @else
            <div class="bg-gray-800/70 border border-gray-700 rounded-xl p-10 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-700 text-gray-400 mb-4">
                    <i class="fas fa-comment-slash text-2xl"></i>
                </div>
                <p class="text-gray-300 font-medium">Chưa có đánh giá</p>
                <p class="text-gray-500 text-sm mt-1">Hãy là người đầu tiên để lại nhận xét</p>
            </div>
            @endif
        </div>

        <!-- Related Games -->
        @if($relatedGames->count() > 0)
        <div class="related-section">
            <div class="related-header">
                <h2 class="related-title">Game cùng thể loại</h2>
                <span class="related-subtitle">Gợi ý dựa trên thể loại hiện tại</span>
            </div>

            <div class="related-grid">
                @foreach($relatedGames as $relatedGame)
                @php
                    $onSale = $relatedGame->isOnSale();
                    $discount = ($onSale && $relatedGame->price > 0)
                        ? round((($relatedGame->price - $relatedGame->price_sale) / $relatedGame->price) * 100)
                        : null;
                @endphp
                <a href="{{ route('games.show', $relatedGame->slug) }}" class="related-card">
                    <div class="related-thumb">
                        @if($relatedGame->image_url)
                            <img src="{{ $relatedGame->image_url }}" alt="{{ $relatedGame->name }}">
                        @else
                            <div class="related-thumb-fallback">{{ $relatedGame->name }}</div>
                        @endif

                        @if($discount)
                        <span class="related-badge">-{{ $discount }}%</span>
                        @endif
                    </div>

                    <div class="related-body">
                        <h3 class="related-name">{{ $relatedGame->name }}</h3>

                        <div class="related-platform">
                            <i class="fab fa-windows"></i>
                            <span>Windows</span>
                        </div>

                        <div class="related-price-row">
                            @if($onSale)
                                <div class="related-price">
                                    <span class="sale">{{ number_format($relatedGame->price_sale) }}đ</span>
                                    <span class="original">{{ number_format($relatedGame->price) }}đ</span>
                                </div>
                                <span class="related-save">Tiết kiệm {{ number_format($relatedGame->price - $relatedGame->price_sale) }}đ</span>
                            @else
                                <span class="related-price single">{{ number_format($relatedGame->price) }}đ</span>
                                <span class="related-note">Giá tiêu chuẩn</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
// Add to cart form
document.querySelectorAll('.add-to-cart-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const button = this.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...';
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (window.updateCartCount) window.updateCartCount();
                
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
            console.error('Lỗi:', err);
            alert('Không thể thêm vào giỏ hàng!');
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
    });
});
</script>
@endsection
@push('scripts')
<script>
// Interactive rating stars (if present)
document.querySelectorAll('.rating-star').forEach(function(star){
    star.addEventListener('click', function(){
        const val = parseInt(this.dataset.value, 10);
        const container = this.closest('form');
        if(!container) return;
        // set hidden radios
        const radios = container.querySelectorAll('input[name="rating"]');
        radios.forEach(r => r.checked = parseInt(r.value,10) === val);
        // update icons
        container.querySelectorAll('.rating-star').forEach((s, idx) => {
            s.classList.toggle('fas', idx < val);
            s.classList.toggle('far', idx >= val);
        });
    });
});
</script>
@endpush
