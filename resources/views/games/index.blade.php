@extends('layouts.app')

@section('title', 'Danh sách game - ShopGame')

@section('content')
<div class="container" style="padding: 30px 0;">
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; margin-bottom: 20px;">
        <h1 style="color: var(--steam-text); margin: 0; font-size: 24px;">Danh sách game</h1>
        <form method="GET" action="{{ route('games.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm game..." class="search-input" style="width: 220px;">
            <select name="category_id" class="form-select" style="padding: 10px; background: var(--steam-darker); color: var(--steam-text); border: 1px solid var(--steam-border); border-radius: 3px;">
                <option value="">Tất cả thể loại</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            <select name="sort" class="form-select" style="padding: 10px; background: var(--steam-darker); color: var(--steam-text); border: 1px solid var(--steam-border); border-radius: 3px;">
                <option value="latest" @selected(request('sort') === 'latest')>Mới nhất</option>
                <option value="price_asc" @selected(request('sort') === 'price_asc')>Giá tăng dần</option>
                <option value="price_desc" @selected(request('sort') === 'price_desc')>Giá giảm dần</option>
                <option value="name" @selected(request('sort') === 'name')>Tên A-Z</option>
            </select>
            <button type="submit" class="btn btn-primary" style="padding: 10px 16px; background: var(--steam-blue); color: white; border: none; border-radius: 3px; cursor: pointer;">Lọc</button>
        </form>
    </div>

    @if($games->count() === 0)
        <div style="color: var(--steam-text); background: var(--steam-dark); padding: 20px; border-radius: 6px;">Không tìm thấy game phù hợp.</div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">
            @foreach($games as $game)
                <div style="background: var(--steam-dark); border: 1px solid var(--steam-border); border-radius: 6px; overflow: hidden; display: flex; flex-direction: column;">
                    <div style="position: relative; padding-top: 56.25%; background: #111;">
                        @php
                            $thumb = $game->thumbnail ? asset('storage/' . ltrim($game->thumbnail, '/')) : 'https://via.placeholder.com/400x225?text=Game';
                        @endphp
                        <img src="{{ $thumb }}" alt="{{ $game->name }}" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div style="padding: 12px 14px; display: flex; flex-direction: column; gap: 6px; flex: 1;">
                        <div style="color: var(--steam-text); font-weight: 700;">{{ $game->name }}</div>
                        <div style="color: #8f98a0; font-size: 13px;">{{ $game->category->name ?? 'Khác' }}</div>
                        @php
                            $hasSale = $game->price_sale && $game->price_sale > 0 && $game->price_sale < $game->price;
                            $discount = $hasSale ? round((($game->price - $game->price_sale) / $game->price) * 100) : 0;
                        @endphp
                        <div style="display: flex; align-items: center; gap: 8px; margin-top: auto;">
                            @if($hasSale)
                                <span style="background: #4c6b22; color: #c7d5e0; padding: 4px 8px; border-radius: 3px; font-size: 12px;">-{{ $discount }}%</span>
                                <span style="color: #8f98a0; text-decoration: line-through; font-size: 13px;">{{ number_format($game->price) }}đ</span>
                                <span style="color: #a4d007; font-weight: 700;">{{ number_format($game->price_sale) }}đ</span>
                            @else
                                <span style="color: #c7d5e0; font-weight: 700;">{{ number_format($game->price) }}đ</span>
                            @endif
                        </div>
                        <div style="display: flex; gap: 8px; margin-top: 10px;">
                            <a href="{{ route('games.show', $game->slug) }}" class="btn" style="flex: 1; text-align: center; padding: 8px 10px; background: var(--steam-border); color: var(--steam-text); border-radius: 3px; text-decoration: none;">Chi tiết</a>
                            <form action="{{ route('cart.add', $game->id) }}" method="POST" class="add-to-cart-form" style="flex: 1;">
                                @csrf
                                <button type="submit" class="add-to-cart-btn" style="width: 100%; padding: 8px 10px; background: var(--steam-blue); color: white; border: none; border-radius: 3px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                                    <i class="fas fa-cart-plus"></i>
                                    <span class="btn-text">Thêm</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 25px;">
            {{ $games->links() }}
        </div>
    @endif
</div>
@endsection
