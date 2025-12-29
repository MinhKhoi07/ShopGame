@extends('layouts.app')

@section('title', 'Thư Viện Game')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-white mb-8">
        <i class="fas fa-book-open mr-2"></i> Thư Viện Game Của Bạn
    </h1>

    <style>
        .library-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .library-card {
            display: grid;
            grid-template-columns: 140px 1fr auto;
            gap: 16px;
            align-items: center;
            background: #1f2937;
            border-radius: 12px;
            padding: 12px 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .library-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgba(0,0,0,0.35);
        }
        .library-thumb img {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            background: #111827;
        }
        .library-info h3 {
            margin: 0 0 6px;
            font-size: 18px;
        }
        .price-row {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
        }
        .old-price {
            text-decoration: line-through;
            color: #9ca3af;
        }
        .new-price {
            color: #eab308;
            font-weight: 700;
        }
        .sale-badge {
            background: #16a34a;
            color: #0f172a;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 13px;
        }
        .meta {
            color: #9ca3af;
            font-size: 13px;
            display: flex;
            gap: 12px;
        }
        .library-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .library-actions a {
            text-align: center;
            display: inline-block;
            min-width: 110px;
        }
    </style>

    @if($library->isEmpty())
        <div class="bg-gray-800 rounded-lg p-12 text-center">
            <i class="fas fa-folder-open text-6xl text-gray-600 mb-4"></i>
            <h2 class="text-xl text-gray-400 mb-4">Thư viện của bạn đang trống</h2>
            <p class="text-gray-500 mb-6">Hãy khám phá và mua game yêu thích của bạn!</p>
            <a href="{{ route('games.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                <i class="fas fa-shopping-cart mr-2"></i> Khám Phá Game
            </a>
        </div>
    @else
        <div class="library-list">
            @foreach($library as $item)
                @if($item->game)
                @php
                    $game = $item->game;
                    $price = $game->price;
                    $sale = $game->price_sale;
                    $hasSale = $sale && $sale < $price;
                    $final = $hasSale ? $sale : $price;
                    $discountPercent = $hasSale ? round((1 - ($sale / $price)) * 100) : 0;
                @endphp
                <div class="library-card">
                    <div class="library-thumb">
                        @if($game->image_url)
                            <img src="{{ $game->image_url }}" alt="{{ $game->name }}">
                        @else
                            <div class="w-full h-full bg-gray-700 flex items-center justify-center text-gray-400">No image</div>
                        @endif
                    </div>

                    <div class="library-info">
                        <h3 class="text-white">
                            <a href="{{ route('games.show', $game->slug) }}" class="hover:text-blue-400 transition-colors">
                                {{ $game->name }}
                            </a>
                        </h3>
                        <div class="meta mb-2">
                            <span><i class="far fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($item->purchased_at)->format('d/m/Y') }}</span>
                            @if($game->category)
                                <span>{{ $game->category->name }}</span>
                            @endif
                        </div>
                        <div class="price-row">
                            @if($hasSale)
                                <span class="sale-badge">-{{ $discountPercent }}%</span>
                                <span class="old-price">{{ number_format($price) }}đ</span>
                            @endif
                            <span class="new-price">{{ number_format($final) }}đ</span>
                        </div>

                        @if($item->orderItem && $item->orderItem->gameKey)
                        <div class="mt-3 bg-gray-900 border border-gray-800 rounded px-3 py-2 text-sm text-gray-200 key-panel" id="key-panel-{{ $item->id }}" style="display:none;">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold">Key game:</span>
                                <button class="text-blue-400 hover:text-blue-300 text-xs" onclick="copyKey('{{ $item->orderItem->gameKey->key_code }}')">
                                    <i class="far fa-copy mr-1"></i> Sao chép
                                </button>
                            </div>
                            <code class="text-green-400 text-base block select-all">{{ $item->orderItem->gameKey->key_code }}</code>
                            <div class="mt-2 text-xs text-gray-400 leading-relaxed">
                                <div class="font-semibold text-gray-300 mb-1">Hướng dẫn kích hoạt:</div>
                                <ul class="list-disc ml-4 space-y-1">
                                    <li>Mở launcher/Steam của {{ $game->name }}</li>
                                    <li>Chọn mục Redeem/Activate a product</li>
                                    <li>Dán key và xác nhận</li>
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="library-actions">
                        @if($item->orderItem && $item->orderItem->gameKey)
                        <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors text-sm" onclick="toggleKey('{{ $item->id }}')">
                            <i class="fas fa-key mr-1"></i> Xem game key
                        </button>
                        @else
                        <button type="button" class="bg-gray-600 text-gray-300 px-4 py-2 rounded text-sm cursor-not-allowed" disabled>
                            <i class="fas fa-key mr-1"></i> Chưa có key
                        </button>
                        @endif
                        @if($game->download_url)
                        <a href="{{ $game->download_url }}" target="_blank"
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition-colors text-sm">
                            <i class="fas fa-download mr-1"></i> Tải về
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        <div class="mt-8 text-center text-gray-400">
            <p>Tổng số game: <span class="text-white font-semibold">{{ $library->count() }}</span></p>
        </div>
    @endif
</div>

<script>
function copyKey(key) {
    navigator.clipboard.writeText(key).then(() => {
        const msg = document.createElement('div');
        msg.className = 'fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded shadow-lg z-50';
        msg.innerHTML = '<i class="fas fa-check mr-1"></i> Đã sao chép key';
        document.body.appendChild(msg);
        setTimeout(() => msg.remove(), 1800);
    });
}

function toggleKey(id) {
    const panel = document.getElementById(`key-panel-${id}`);
    if (!panel) return;
    const visible = panel.style.display !== 'none';
    panel.style.display = visible ? 'none' : 'block';
}
</script>
@endsection
