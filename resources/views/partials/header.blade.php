<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-content">
                <div class="header-left">
                    <a href="/" class="logo">
                        <i class="fas fa-gamepad"></i>
                        <span>SHOPGAME</span>
                    </a>
                    <nav class="main-nav">
                        <a href="/" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Cửa hàng</a>
                        <a href="/library" class="nav-link {{ request()->routeIs('library.index') ? 'active' : '' }}">Thư viện</a>
                        <a href="/categories" class="nav-link {{ request()->routeIs('categories.index', 'categories.show') ? 'active' : '' }}">Thể loại</a>
                        <a href="/news" class="nav-link {{ request()->routeIs('news.index') ? 'active' : '' }}">Tin tức</a>
                    </nav>
                </div>
                <div class="header-right">
                    <form class="search-bar" method="GET" action="{{ route('games.index') }}" autocomplete="off">
                        <input id="header-search" type="text" name="search" placeholder="Tìm kiếm game..." class="search-input" value="{{ request('search') }}">
                        <button type="submit" class="search-submit" aria-label="Tìm kiếm">
                            <i class="fas fa-search"></i>
                        </button>
                        <div id="search-suggestions" class="search-suggestions" style="display: none;"></div>
                    </form>
                    <div class="user-menu">
                        <a href="{{ route('cart.index') }}" class="icon-btn">
                            <i class="fas fa-shopping-cart"></i>
                            @php
                                $cartCount = \App\Models\CartItem::where(function($query) {
                                    if (auth()->check()) {
                                        $query->where('user_id', auth()->id());
                                    } else {
                                        $query->where('session_id', session()->getId());
                                    }
                                })->count();
                            @endphp
                            @if($cartCount > 0)
                                <span class="badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                        
                        @auth
                        <div class="user-dropdown">
                            <button class="user-btn">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=1b2838&color=fff" alt="User" class="user-avatar">
                                <span>{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" style="color: var(--steam-blue); font-weight: bold;">
                                        <i class="fas fa-shield-alt"></i> Quản trị
                                    </a>
                                    <hr>
                                @endif
                                <a href="/profile">Hồ sơ</a>
                                <a href="/orders">Đơn hàng</a>
                                <a href="/library">Thư viện</a>
                                <a href="/wishlist">Yêu thích</a>
                                <hr>
                                <a href="/settings">Cài đặt</a>
                                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="width: 100%; text-align: left; background: none; border: none; padding: 12px 20px; color: var(--steam-text); cursor: pointer;">Đăng xuất</button>
                                </form>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-secondary" style="margin-left: 10px; padding: 8px 20px;">
                            <i class="fas fa-sign-in-alt"></i>
                            Đăng nhập
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const input = document.getElementById('header-search');
                            const box = document.getElementById('search-suggestions');
                            const form = input ? input.closest('form') : null;
                            let timer = null;

                            if (!input || !box) return;

                            function hideBox() {
                                box.style.display = 'none';
                                box.innerHTML = '';
                            }

                            function render(items) {
                                if (!items || !items.length) {
                                    hideBox();
                                    return;
                                }
                                const html = items.map(item => {
                                    const price = item.hasSale
                                        ? `<div class="search-suggestion-price"><span class="discount-badge">-${item.discount}%</span><span class="old-price">${item.price}đ</span><span class="sale-price">${item.sale_price}đ</span></div>`
                                        : `<div class="search-suggestion-price"><span class="sale-price">${item.price}đ</span></div>`;
                                    return `
                                        <a class="search-suggestion-item" href="${item.url}">
                                            <img class="search-suggestion-thumb" src="${item.thumbnail}" alt="${item.name}">
                                            <div>
                                                <div class="search-suggestion-title">${item.name}</div>
                                                ${price}
                                            </div>
                                        </a>
                                    `;
                                }).join('');
                                box.innerHTML = html;
                                box.style.display = 'block';
                            }

                            input.addEventListener('input', function() {
                                const q = this.value.trim();
                                if (q.length < 2) {
                                    hideBox();
                                    return;
                                }
                                clearTimeout(timer);
                                timer = setTimeout(() => {
                                    fetch(`{{ route('search.suggest') }}?q=${encodeURIComponent(q)}`)
                                        .then(res => res.json())
                                        .then(render)
                                        .catch(() => hideBox());
                                }, 250);
                            });

                            input.addEventListener('focus', function() {
                                if (box.innerHTML.trim()) {
                                    box.style.display = 'block';
                                }
                            });

                            document.addEventListener('click', function(e) {
                                if (!form.contains(e.target)) {
                                    hideBox();
                                }
                            });
                        });
                        </script>
