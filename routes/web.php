<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ReviewController;

// Khi vào trang chủ -> Gọi HomeController
Route::get('/', [HomeController::class, 'index'])->name('home');

// Khi vào danh sách game
Route::get('/games', [GameController::class, 'index'])->name('games.index');

// Chi tiết game
Route::get('/games/{slug}', [GameController::class, 'show'])->name('games.show');

// Game reviews
Route::post('/games/{game}/reviews', [ReviewController::class, 'store'])
    ->middleware('auth')
    ->name('reviews.store');

// Search suggestions
Route::get('/search/suggest', [GameController::class, 'suggest'])->name('search.suggest');

// Library route
Route::get('/library', [LibraryController::class, 'index'])->name('library.index');

// Categories routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');

// News route
Route::get('/news', [NewsController::class, 'index'])->name('news.index');

// Contact page
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('auth')
    ->name('contact.store');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Cart routes
Route::middleware('check.active')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{gameId}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{itemId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{orderId}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/check-status/{orderId}', [CheckoutController::class, 'checkStatus'])->name('checkout.checkStatus');
    Route::post('/order/{orderId}/cancel', [CheckoutController::class, 'cancel'])->name('order.cancel');
});

// User settings (bảo mật, thanh toán, thông báo)
Route::middleware('auth')->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/security', [SettingsController::class, 'updateSecurity'])->name('settings.security');
    Route::post('/settings/billing', [SettingsController::class, 'updateBilling'])->name('settings.billing');
    Route::post('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications');
});

// Admin routes
Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');
    
    // Games management
    Route::get('/games', [AdminController::class, 'games'])->name('admin.games');
    Route::get('/games/create', [AdminController::class, 'createGame'])->name('admin.games.create');
    Route::post('/games', [AdminController::class, 'storeGame'])->name('admin.games.store');
    Route::get('/games/{id}/edit', [AdminController::class, 'editGame'])->name('admin.games.edit');
    Route::put('/games/{id}', [AdminController::class, 'updateGame'])->name('admin.games.update');
    Route::delete('/games/{id}', [AdminController::class, 'deleteGame'])->name('admin.games.delete');
    
    // Banners management
    Route::get('/banners', [AdminController::class, 'banners'])->name('admin.banners');
    Route::get('/banners/create', [AdminController::class, 'createBanner'])->name('admin.banners.create');
    Route::post('/banners', [AdminController::class, 'storeBanner'])->name('admin.banners.store');
    Route::get('/banners/{id}/edit', [AdminController::class, 'editBanner'])->name('admin.banners.edit');
    Route::put('/banners/{id}', [AdminController::class, 'updateBanner'])->name('admin.banners.update');
    Route::delete('/banners/{id}', [AdminController::class, 'deleteBanner'])->name('admin.banners.delete');
    Route::post('/banners/{id}/toggle', [AdminController::class, 'toggleBanner'])->name('admin.banners.toggle');
    
    // Sales management
    Route::get('/sales', [AdminController::class, 'sales'])->name('admin.sales');
    Route::get('/sales/create', [AdminController::class, 'createSale'])->name('admin.sales.create');
    Route::post('/sales', [AdminController::class, 'storeSale'])->name('admin.sales.store');
    Route::get('/sales/{id}/edit', [AdminController::class, 'editSale'])->name('admin.sales.edit');
    Route::put('/sales/{id}', [AdminController::class, 'updateSale'])->name('admin.sales.update');
    Route::delete('/sales/{id}', [AdminController::class, 'deleteSale'])->name('admin.sales.delete');
    Route::post('/sales/{id}/toggle', [AdminController::class, 'toggleSale'])->name('admin.sales.toggle');
    Route::post('/sales/import-legacy', [AdminController::class, 'importLegacySales'])->name('admin.sales.import');

    // Reviews management
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('admin.reviews');
    Route::delete('/reviews/{id}', [AdminController::class, 'deleteReview'])->name('admin.reviews.delete');
    
    // Game Keys management
    Route::get('/keys', [AdminController::class, 'gameKeys'])->name('admin.keys');
    Route::get('/keys/create', [AdminController::class, 'createGameKey'])->name('admin.keys.create');
    Route::post('/keys', [AdminController::class, 'storeGameKey'])->name('admin.keys.store');
    Route::delete('/keys/{id}', [AdminController::class, 'deleteGameKey'])->name('admin.keys.delete');
    
    // Categories management
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('admin.categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::get('/categories/{id}/edit', [AdminController::class, 'editCategory'])->name('admin.categories.edit');
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');
    
    // Orders management
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::post('/orders/{id}/confirm', [AdminController::class, 'confirmPayment'])->name('admin.orders.confirm');
    Route::post('/orders/{id}/cancel', [AdminController::class, 'cancelOrder'])->name('admin.orders.cancel');
    
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::post('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle');
    
    // Chat management
    Route::get('/chats', [ChatController::class, 'adminChats'])->name('admin.chats');
    Route::get('/chats/{userId}', [ChatController::class, 'adminChatDetail'])->name('admin.chats.detail');
    Route::post('/chats/{userId}/send', [ChatController::class, 'adminSendMessage'])->name('admin.chats.send');
});

// Chat API routes for customers
Route::post('/api/messages', [ChatController::class, 'sendMessage'])->name('api.messages.send');
Route::get('/api/messages', [ChatController::class, 'getMessages'])->name('api.messages.get');