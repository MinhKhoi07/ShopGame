# Tổng Quan Dự Án ShopGame

Tài liệu này liệt kê và trình bày cấu trúc, chức năng, kiến trúc, các điểm kỹ thuật chính của dự án để hỗ trợ hệ thống AI (Gemini) viết báo cáo chuẩn xác.

## 1. Mục Tiêu & Phạm Vi
- Bán game dạng key (digital goods) với giỏ hàng, thanh toán và thư viện game đã mua.
- Hỗ trợ khuyến mãi theo game/nhóm danh mục, đánh giá, tin tức, nhắn tin hỗ trợ (chat), và trang quản trị.
- Thanh toán qua mã QR ngân hàng (VietQR) và tích hợp VNPay (sandbox).

## 2. Công Nghệ & Thư Viện
- Backend: Laravel (PHP) – cấu hình khởi tạo ở [bootstrap/app.php](bootstrap/app.php), cấu hình ứng dụng ở [config](config).
- Frontend: Vite/Tailwind (theo [vite.config.js](vite.config.js), [resources](resources/)).
- CSDL: MySQL (migrations ở [database/migrations](database/migrations)).
- Dịch vụ thanh toán: VietQR ([app/Providers/AppServiceProvider.php](app/Providers/AppServiceProvider.php), [app/Services/VietQRService.php](app/Services/VietQRService.php)), VNPay ([config/payment.php](config/payment.php)).

## 3. Cấu Trúc Thư Mục Chính
- Controllers: [app/Http/Controllers](app/Http/Controllers) (Auth, Cart, Category, Chat, Checkout, Game, Home, Library, News, Settings, Admin).
- Models: [app/Models](app/Models) (Banner, CartItem, Category, Game, GameKey, Invoice, Library, Message, Order, OrderItem, Review, Sale, User).
- Middleware: [app/Http/Middleware](app/Http/Middleware) (AdminMiddleware, CheckUserActive).
- Routes: web ở [routes/web.php](routes/web.php), API ở [routes/api.php](routes/api.php), console ở [routes/console.php](routes/console.php).
- Cấu hình: [config](config) (auth, cache, database, filesystems, logging, mail, payment, queue, services, session).
- Tài liệu: [Tailieu](Tailieu/) (hướng dẫn báo cáo, tổng quan dự án).

## 4. Chức Năng Nổi Bật
- Người dùng: đăng ký/đăng nhập, cập nhật thông tin, trạng thái `is_active` ([database/migrations](database/migrations)).
- Sản phẩm (Game): danh sách, chi tiết, hình ảnh, giá, giá khuyến mãi; quản lý key và tồn khả dụng.
- Giỏ hàng & Đơn hàng: thêm/xóa, thanh toán, xuất hóa đơn.
- Thư viện game (Library): lưu các game đã mua.
- Khuyến mãi (Sale): theo game và theo danh mục – chọn mức giảm hiệu quả nhất (xem logic ở [app/Models/Game.php](app/Models/Game.php)).
- Đánh giá (Review), Tin tức (News), Banner/truyền thông.
- Chat/Nhắn tin hỗ trợ: các flags chat, cấu trúc ở migrations/messages.
- Quản trị: middleware `admin` ([bootstrap/app.php](bootstrap/app.php)), trang quản trị (AdminController).

## 5. Kiến Trúc & Quy Ước
- MVC chuẩn Laravel.
- Định tuyến: web/UI dùng [routes/web.php](routes/web.php); API REST ở [routes/api.php](routes/api.php).
- Middleware alias khai báo ở [bootstrap/app.php](bootstrap/app.php) (`admin`, `check.active`).
- Eloquent quan hệ đầy đủ giữa `Game`, `Category`, `GameKey`, `Order`, `OrderItem`, `Library`, `Review`, `Sale`.

## 6. Cơ Sở Dữ Liệu (Tóm Tắt)
- Migrations trong [database/migrations](database/migrations), bao gồm: `users`, `games`, `categories`, `game_keys`, `orders`, `order_items`, `libraries`, `reviews`, `sales`, `messages`, `banners`, `cart_items`, v.v.
- Ví dụ model `Game` ([app/Models/Game.php](app/Models/Game.php)) có các trường: `category_id`, `name`, `slug`, `description`, `price`, `price_sale`, `thumbnail`, `images (JSON)`, `system_requirements`, `developer`, `is_active`, `is_free`.
- Tính giá khuyến mãi hiệu quả: ưu tiên mức giảm cao nhất, nếu bằng nhau ưu tiên giảm theo game (xem `effectiveSale()` và `effectiveSalePrice()` trong [app/Models/Game.php](app/Models/Game.php)).

## 7. API (Cho Postman/Gemini)
- Định tuyến API được bật tại [bootstrap/app.php](bootstrap/app.php) và định nghĩa ở [routes/api.php](routes/api.php).
- Phiên bản `v1`:
  - `GET /api/v1/health` – kiểm tra trạng thái.
  - `GET /api/v1/games?per_page=10` – danh sách game đang hoạt động; trả về phân trang.
  - `GET /api/v1/games/{id}` – chi tiết game (404 nếu `is_active` = false).
  - `POST /api/v1/payments/vnpay/create` – tạo URL thanh toán VNPay (sandbox).
  - `GET|POST /api/v1/payments/vnpay/callback` – xác thực chữ ký callback VNPay, trả về `verified` và payload.
- Controllers API:
  - Game: [app/Http/Controllers/Api/GameApiController.php](app/Http/Controllers/Api/GameApiController.php)
  - VNPay: [app/Http/Controllers/Api/VNPayController.php](app/Http/Controllers/Api/VNPayController.php)

## 8. Thiết Lập & Chạy
- Cấu hình `.env` (ví dụ các biến VNPay):
  - `VNPAY_TMN_CODE`, `VNPAY_HASH_SECRET`, `VNPAY_RETURN_URL`, `VNPAY_URL` (tuỳ chọn; mặc định sandbox).
- Cấu hình thanh toán ở [config/payment.php](config/payment.php) (bank info, `qr_template`, thời gian hết hạn, `vnpay` block).
- Cài đặt & build:
```bash
composer install
php artisan key:generate
php artisan migrate
php artisan storage:link
npm install
npm run build
php artisan serve
```

## 9. Kiểm Thử Nhanh
- Liệt kê routes:
```bash
php artisan route:list
```
- Postman:
  - Tạo thanh toán VNPay:
    - Endpoint: `POST /api/v1/payments/vnpay/create`
    - Body JSON: `{ "amount": 150000, "order_code": "ORDER123", "order_info": "Test via Postman" }`
  - Callback kiểm tra chữ ký: gửi payload từ VNPay sandbox đến `GET/POST /api/v1/payments/vnpay/callback`.
  - Danh sách game: `GET /api/v1/games?per_page=10`
  - Chi tiết game: `GET /api/v1/games/{id}`

## 10. Bảo Mật & Middleware
- `admin` và `check.active` alias ở [bootstrap/app.php](bootstrap/app.php).
- Kiểm soát truy cập trang quản trị (AdminController); kiểm tra người dùng hoạt động (`CheckUserActive`).

## 11. Ghi Chú & Hạn Chế Hiện Tại
- API hiện cung cấp các endpoint công khai đọc dữ liệu game và tích hợp VNPay cơ bản; các hành động có trạng thái (giỏ hàng/đơn hàng) chủ yếu qua web routes.
- Cần cấu hình đầy đủ thông tin VNPay trong `.env` để tạo URL thanh toán hợp lệ.

## 12. Định Hướng Phát Triển
- Mở rộng API (auth, giỏ hàng, đơn hàng, thư viện) để phục vụ mobile.
- Hoàn thiện webhook/payment state cho VNPay và đối soát đơn hàng.
- Bổ sung rate limiting, validation nâng cao, và logs/observability.
- Viết tests đầy đủ cho controllers và models trong [tests](tests/).
