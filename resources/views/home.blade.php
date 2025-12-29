@extends('layouts.app')

@section('title', 'ShopGame - Mua bán game và phần mềm')

@section('content')
<!-- Hero Banner Slider -->
@if(isset($sliderBanners) && $sliderBanners->count() > 0)
    @include('partials.banner-slider', ['banners' => $sliderBanners])
@else
<section class="hero-banner">
    <div class="banner-slider">
        <div class="banner-slide active">
            <img src="https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=1920&h=600&fit=crop" alt="Featured Game">
            <div class="banner-overlay">
                <div class="container">
                    <div class="banner-content">
                        <h1 class="banner-title">Chào mừng đến ShopGame</h1>
                        <p class="banner-desc">Khám phá hàng ngàn game và phần mềm chất lượng cao</p>
                        <div class="banner-tags">
                            <span class="tag">Game AAA</span>
                            <span class="tag">Phần mềm</span>
                            <span class="tag">Giá tốt</span>
                        </div>
                        <div class="banner-actions">
                            <a href="/games" class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i>
                                Khám phá ngay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Special Offers -->
@if(isset($saleGames) && $saleGames->count() > 0)
<section class="section special-offers">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Ưu đãi đặc biệt</h2>
            <a href="/deals" class="view-all">Xem tất cả <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="game-grid">
            @foreach($saleGames as $game)
                @include('partials.game-card', ['game' => $game])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Games -->
@if(isset($latestGames) && $latestGames->count() > 0)
<section class="section featured-games">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Game mới nhất</h2>
            <a href="/games" class="view-all">Xem tất cả <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="game-grid">
            @foreach($latestGames as $game)
                @include('partials.game-card', ['game' => $game])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Free Games -->
@if(isset($freeGames) && $freeGames->count() > 0)
<section class="section free-games">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title" style="color: #beee11;"><i class="fas fa-gift"></i> Game Miễn Phí</h2>
            <a href="/games?is_free=1" class="view-all">Xem tất cả <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="game-grid">
            @foreach($freeGames as $game)
                @include('partials.game-card', ['game' => $game])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Hot Games -->
@if(isset($hotGames) && $hotGames->count() > 0)
<section class="section hot-games">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title" style="color: #ff6b6b;"><i class="fas fa-fire"></i> Game Hot Nhất</h2>
            <a href="/games" class="view-all">Xem tất cả <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="game-grid">
            @foreach($hotGames as $game)
                @include('partials.game-card', ['game' => $game])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Chat Bubble -->
@include('partials.chat-bubble')

@endsection
