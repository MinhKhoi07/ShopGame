<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ShopGame - Mua bán game và phần mềm')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-dark">
    @include('partials.header')
    
    <main class="main-content">
        @yield('content')
    </main>
    
    @include('partials.footer')

    <!-- Modal thêm vào giỏ hàng -->
    <div id="addToCartModal" class="cart-modal">
        <div class="cart-modal-overlay"></div>
        <div class="cart-modal-content">
            <button class="cart-modal-close">&times;</button>
            
            <h2 class="cart-modal-title">Đã thêm vào giỏ hàng!</h2>
            
            <div class="cart-modal-game">
                <img id="modalGameImage" src="" alt="" class="cart-modal-game-img">
                
                <div class="cart-modal-game-info">
                    <h3 id="modalGameName" class="cart-modal-game-name"></h3>
                    
                    <div class="cart-modal-platforms">
                        <i class="fab fa-windows"></i>
                        <i class="fab fa-apple"></i>
                    </div>
                    
                    <div class="cart-modal-game-price">
                        <span id="modalDiscount" class="cart-modal-discount"></span>
                        <div class="cart-modal-price-wrap">
                            <span id="modalOriginalPrice" class="cart-modal-original-price"></span>
                            <span id="modalSalePrice" class="cart-modal-sale-price"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="cart-modal-actions">
                <button class="cart-modal-btn cart-modal-btn-secondary" onclick="closeCartModal()">
                    Tiếp tục mua sắm
                </button>
                <a href="{{ route('cart.index') }}" class="cart-modal-btn cart-modal-btn-primary">
                    Xem giỏ hàng (<span id="modalCartCount">0</span>)
                </a>
            </div>
        </div>
    </div>

    <script>
        // AJAX Add to Cart
        document.addEventListener('DOMContentLoaded', function() {
            // Setup CSRF token for AJAX
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            // Add to cart functionality
            document.querySelectorAll('.add-to-cart-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const button = this.querySelector('.add-to-cart-btn');
                    const buttonText = button.querySelector('.btn-text');
                    const icon = button.querySelector('i');
                    const gameId = button.dataset.gameId;
                    
                    // Disable button
                    button.disabled = true;
                    button.style.opacity = '0.7';
                    
                    // Animation: Add loading
                    icon.className = 'fas fa-spinner fa-spin';
                    buttonText.textContent = 'Đang thêm...';
                    
                    // Send AJAX request
                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Success animation
                        button.style.background = '#4c6b22';
                        icon.className = 'fas fa-check';
                        buttonText.textContent = 'Đã thêm!';
                        
                        // Update cart count in header
                        updateCartCount();
                        
                        // Show modal with game info
                        showCartModal(data.game, data.cart_count);
                        
                        // Reset button after 2 seconds
                        setTimeout(() => {
                            button.disabled = false;
                            button.style.opacity = '1';
                            button.style.background = '';
                            icon.className = 'fas fa-cart-plus';
                            buttonText.textContent = 'Thêm vào giỏ';
                        }, 2000);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Error state
                        button.style.background = '#c84b31';
                        icon.className = 'fas fa-times';
                        buttonText.textContent = 'Lỗi!';
                        
                        showNotification('Có lỗi xảy ra!', 'error');
                        
                        // Reset button
                        setTimeout(() => {
                            button.disabled = false;
                            button.style.opacity = '1';
                            button.style.background = '';
                            icon.className = 'fas fa-cart-plus';
                            buttonText.textContent = 'Thêm vào giỏ';
                        }, 2000);
                    });
                });
            });

            // Update cart count
            function updateCartCount() {
                fetch('/cart/count', {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.icon-btn .badge');
                    if (data.count > 0) {
                        if (badge) {
                            badge.textContent = data.count;
                            // Animate badge
                            badge.style.transform = 'scale(1.3)';
                            setTimeout(() => {
                                badge.style.transform = 'scale(1)';
                            }, 300);
                        } else {
                            // Create badge if not exists
                            const cartBtn = document.querySelector('.icon-btn');
                            const newBadge = document.createElement('span');
                            newBadge.className = 'badge';
                            newBadge.textContent = data.count;
                            cartBtn.appendChild(newBadge);
                        }
                    }
                });
            }

            // Show cart modal with game info
            function showCartModal(game, cartCount) {
                const modal = document.getElementById('addToCartModal');
                
                // Set game image
                document.getElementById('modalGameImage').src = game.thumbnail;
                document.getElementById('modalGameImage').alt = game.name;
                
                // Set game name
                document.getElementById('modalGameName').textContent = game.name;
                
                // Set prices
                if (game.price_sale && game.price_sale > 0 && game.price_sale < game.price) {
                    const discount = Math.round(((game.price - game.price_sale) / game.price) * 100);
                    document.getElementById('modalDiscount').textContent = `-${discount}%`;
                    document.getElementById('modalDiscount').style.display = 'inline-block';
                    document.getElementById('modalOriginalPrice').textContent = formatPrice(game.price);
                    document.getElementById('modalOriginalPrice').style.display = 'inline-block';
                    document.getElementById('modalSalePrice').textContent = formatPrice(game.price_sale);
                    document.getElementById('modalSalePrice').style.color = '#a4d007';
                } else {
                    document.getElementById('modalDiscount').style.display = 'none';
                    document.getElementById('modalOriginalPrice').style.display = 'none';
                    document.getElementById('modalSalePrice').textContent = formatPrice(game.price);
                    document.getElementById('modalSalePrice').style.color = 'white';
                }
                
                // Set cart count
                document.getElementById('modalCartCount').textContent = cartCount;
                
                // Show modal
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            // Format price
            function formatPrice(price) {
                return new Intl.NumberFormat('vi-VN').format(price) + 'đ';
            }

            // Show notification
            function showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                notification.className = `notification notification-${type}`;
                notification.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    <span>${message}</span>
                `;
                
                document.body.appendChild(notification);
                
                // Trigger animation
                setTimeout(() => notification.classList.add('show'), 10);
                
                // Remove after 3 seconds
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }
        });
    </script>

    <script>
        // Close cart modal
        function closeCartModal() {
            const modal = document.getElementById('addToCartModal');
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }

        // Close modal when clicking overlay
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('addToCartModal');
            const overlay = modal.querySelector('.cart-modal-overlay');
            const closeBtn = modal.querySelector('.cart-modal-close');
            
            overlay.addEventListener('click', closeCartModal);
            closeBtn.addEventListener('click', closeCartModal);
            
            // Close on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.classList.contains('show')) {
                    closeCartModal();
                }
            });
        });
    </script>

    <style>
        .add-to-cart-btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .add-to-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(103, 193, 245, 0.3);
        }

        .add-to-cart-btn:active {
            transform: translateY(0);
        }

        /* Notification styles */
        .notification {
            position: fixed;
            top: 80px;
            right: -400px;
            background: var(--steam-dark);
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 10000;
            transition: right 0.3s ease;
            min-width: 250px;
        }

        .notification.show {
            right: 20px;
        }

        .notification-success {
            border-left: 4px solid #4c6b22;
        }

        .notification-success i {
            color: #a4d007;
        }

        .notification-error {
            border-left: 4px solid #c84b31;
        }

        .notification-error i {
            color: #ff6b6b;
        }

        .badge {
            transition: transform 0.3s ease;
        }

        /* Cart Modal Styles */
        .cart-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .cart-modal.show {
            display: flex;
        }

        .cart-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
        }

        .cart-modal-content {
            position: relative;
            background: linear-gradient(135deg, #2a475e 0%, #1b2838 100%);
            border: 1px solid #16202d;
            border-radius: 8px;
            max-width: 600px;
            width: 90%;
            padding: 30px;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.5);
            animation: modalSlideDown 0.3s ease;
        }

        @keyframes modalSlideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .cart-modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: #8f98a0;
            font-size: 28px;
            cursor: pointer;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .cart-modal-close:hover {
            color: white;
        }

        .cart-modal-title {
            color: white;
            font-size: 24px;
            margin: 0 0 25px 0;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .cart-modal-game {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            background: rgba(0, 0, 0, 0.2);
            padding: 20px;
            border-radius: 5px;
        }

        .cart-modal-game-img {
            width: 200px;
            height: 110px;
            object-fit: cover;
            border-radius: 5px;
            flex-shrink: 0;
        }

        .cart-modal-game-info {
            flex: 1;
        }

        .cart-modal-game-name {
            color: white;
            font-size: 18px;
            margin: 0 0 10px 0;
        }

        .cart-modal-platforms {
            margin-bottom: 15px;
        }

        .cart-modal-platforms i {
            color: #8f98a0;
            margin-right: 8px;
            font-size: 14px;
        }

        .cart-modal-game-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .cart-modal-discount {
            background: #4c6b22;
            color: #a4d007;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 14px;
        }

        .cart-modal-price-wrap {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .cart-modal-original-price {
            color: #8f98a0;
            text-decoration: line-through;
            font-size: 13px;
        }

        .cart-modal-sale-price {
            color: #a4d007;
            font-size: 18px;
            font-weight: bold;
        }

        .cart-modal-select {
            background: var(--steam-darker);
            border: 1px solid var(--steam-border);
            color: #8f98a0;
            padding: 10px 15px;
            border-radius: 3px;
            width: 100%;
            cursor: pointer;
        }

        .cart-modal-select:focus {
            outline: none;
            border-color: var(--steam-blue);
        }

        .cart-modal-actions {
            display: flex;
            gap: 15px;
        }

        .cart-modal-btn {
            flex: 1;
            padding: 15px 20px;
            border-radius: 3px;
            border: none;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-modal-btn-secondary {
            background: rgba(103, 193, 245, 0.1);
            color: var(--steam-blue);
            border: 1px solid rgba(103, 193, 245, 0.3);
        }

        .cart-modal-btn-secondary:hover {
            background: rgba(103, 193, 245, 0.2);
        }

        .cart-modal-btn-primary {
            background: linear-gradient(90deg, #06bfff 0%, #2d73ff 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(6, 191, 255, 0.3);
        }

        .cart-modal-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(6, 191, 255, 0.4);
        }

        @media (max-width: 768px) {
            .cart-modal-game {
                flex-direction: column;
            }

            .cart-modal-game-img {
                width: 100%;
            }

            .cart-modal-actions {
                flex-direction: column;
            }
        }
    </style>
</body>
</html>
