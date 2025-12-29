<!-- Banner Slider Component -->
@props(['banners'])

@if($banners && $banners->count() > 0)
<section class="hero-banner">
    <div class="banner-slider">
        @foreach($banners as $index => $banner)
        <div class="banner-slide {{ $index === 0 ? 'active' : '' }}">
            @if($banner->media_type === 'video' && $banner->video_path)
                <video autoplay muted loop playsinline>
                    <source src="{{ asset('storage/' . $banner->video_path) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @elseif($banner->image_path || $banner->image)
                <img src="{{ asset('storage/' . ($banner->image_path ?? $banner->image)) }}" alt="{{ $banner->title }}">
            @else
                <img src="https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=1920&h=600&fit=crop" alt="{{ $banner->title }}">
            @endif
            
            <div class="banner-overlay">
                <div class="container">
                    <div class="banner-content">
                        <h1 class="banner-title">{{ $banner->title }}</h1>
                        
                        @if($banner->description)
                            <p class="banner-desc">{{ $banner->description }}</p>
                        @endif
                        
                        <div class="banner-actions">
                            @if($banner->link)
                                <a href="{{ $banner->link }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-cart"></i>
                                    Xem chi tiết
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif
