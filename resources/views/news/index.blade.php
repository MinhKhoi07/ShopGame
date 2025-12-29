@extends('layouts.app')

@section('title', 'Tin Tức Game')

@section('content')
<div class="news-page">
    <div class="news-hero">
        <div>
            <p class="news-kicker">Tin tức & sự kiện</p>
            <h1 class="news-heading"><i class="fas fa-newspaper"></i> Tin Tức Game</h1>
            <p class="news-subtitle">Cập nhật nhanh khuyến mãi, sự kiện và thông báo mới nhất</p>
        </div>
        <button class="news-subscribe-btn">
            <i class="fas fa-bell"></i>
            Nhận thông báo
        </button>
    </div>

    @if($news->isEmpty())
        <div class="news-empty">
            <i class="fas fa-rss"></i>
            <p>Chưa có tin tức nào</p>
        </div>
    @else
        <div class="news-grid">
            @foreach($news as $item)
            @php
                $date = \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i');
                $image = $item->getMediaPath();
            @endphp
            <article class="news-card">
                <div class="news-thumb">
                    @if($image)
                        <img src="{{ $image }}" alt="{{ $item->title }}">
                    @else
                        <div class="news-thumb-fallback">{{ $item->title }}</div>
                    @endif
                    <span class="news-badge">Tin tức</span>
                </div>

                <div class="news-body">
                    <div class="news-meta">
                        <span><i class="far fa-calendar"></i>{{ $date }}</span>
                    </div>
                    <h2 class="news-title">{{ $item->title }}</h2>
                    @if($item->description)
                    <p class="news-desc">{{ $item->description }}</p>
                    @endif

                    <div class="news-footer">
                        @if($item->link)
                            <a href="{{ $item->link }}" target="_blank" class="news-link">
                                Đọc chi tiết <i class="fas fa-arrow-right"></i>
                            </a>
                        @else
                            <span class="news-note">Không có liên kết ngoài</span>
                        @endif
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        @if($news->hasPages())
        <div class="news-pagination">
            {{ $news->links() }}
        </div>
        @endif
    @endif

    <div class="news-cta">
        <div>
            <p class="news-cta-kicker">Đăng ký nhận tin</p>
            <h3>Không bỏ lỡ game mới và ưu đãi hot</h3>
            <p>Nhận email về khuyến mãi, bản cập nhật và sự kiện đặc biệt mỗi tuần.</p>
        </div>
        <button class="news-cta-btn"><i class="fas fa-envelope"></i> Đăng ký ngay</button>
    </div>
</div>
@endsection
