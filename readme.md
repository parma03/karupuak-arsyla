# KARUPUAK Kulit ARSYLA — E-Commerce Platform

> Platform e-commerce kerupuk Kulit premium dengan arsitektur **CQRS + Clean Architecture**, dibangun menggunakan **PHP Native**, **Bootstrap 5**, dan **Midtrans Payment Gateway**.

---

## 📁 Struktur Direktori Lengkap

```
C:/xampp/htdocs/karupuak-arsyla/
│
├── index.php                          ← Entry point utama (Katalog Produk)
├── .htaccess                          ← URL routing (auto-detect, tanpa config Apache manual)
├── README.md
│
├── config/
│   ├── app.php                        ← Konfigurasi umum aplikasi
│   ├── database.php                   ← Koneksi MySQL (XAMPP)
│   └── midtrans.php                   ← Konfigurasi Midtrans
│
├── core/                              ← Framework mini (Clean Architecture)
│   ├── Application.php                ← Bootstrap aplikasi & DI Container
│   ├── Router.php                     ← Front controller router
│   ├── Request.php                    ← HTTP Request wrapper
│   ├── Response.php                   ← HTTP Response wrapper
│   ├── Session.php                    ← Session manager
│   ├── Validator.php                  ← Input validator
│   └── Middleware.php                 ← Auth middleware
│
├── src/                               ← Domain Layer (Clean Architecture)
│   │
│   ├── Domain/                        ← Enterprise Business Rules
│   │   ├── Product/
│   │   │   ├── Entities/
│   │   │   │   └── Product.php
│   │   │   ├── ValueObjects/
│   │   │   │   ├── ProductId.php
│   │   │   │   └── Price.php
│   │   │   └── Repositories/
│   │   │       └── ProductRepositoryInterface.php
│   │   │
│   │   ├── Order/
│   │   │   ├── Entities/
│   │   │   │   ├── Order.php
│   │   │   │   └── OrderItem.php
│   │   │   ├── ValueObjects/
│   │   │   │   ├── OrderId.php
│   │   │   │   └── OrderStatus.php
│   │   │   └── Repositories/
│   │   │       └── OrderRepositoryInterface.php
│   │   │
│   │   ├── User/
│   │   │   ├── Entities/
│   │   │   │   └── User.php
│   │   │   ├── ValueObjects/
│   │   │   │   ├── UserId.php
│   │   │   │   ├── Email.php
│   │   │   │   └── Role.php
│   │   │   └── Repositories/
│   │   │       └── UserRepositoryInterface.php
│   │   │
│   │   ├── Cart/
│   │   │   ├── Entities/
│   │   │   │   ├── Cart.php
│   │   │   │   └── CartItem.php
│   │   │   └── Repositories/
│   │   │       └── CartRepositoryInterface.php
│   │   │
│   │   ├── Review/
│   │   │   ├── Entities/
│   │   │   │   └── Review.php
│   │   │   └── Repositories/
│   │   │       └── ReviewRepositoryInterface.php
│   │   │
│   │   └── Category/
│   │       ├── Entities/
│   │       │   └── Category.php
│   │       └── Repositories/
│   │           └── CategoryRepositoryInterface.php
│   │
│   ├── Application/                   ← Application Business Rules (CQRS)
│   │   │
│   │   ├── Commands/                  ← WRITE side (Commands)
│   │   │   ├── CommandBus.php
│   │   │   ├── CommandHandlerInterface.php
│   │   │   │
│   │   │   ├── Auth/
│   │   │   │   ├── RegisterUserCommand.php
│   │   │   │   ├── RegisterUserHandler.php
│   │   │   │   ├── LoginCommand.php
│   │   │   │   └── LoginHandler.php
│   │   │   │
│   │   │   ├── Cart/
│   │   │   │   ├── AddToCartCommand.php
│   │   │   │   ├── AddToCartHandler.php
│   │   │   │   ├── RemoveFromCartCommand.php
│   │   │   │   ├── RemoveFromCartHandler.php
│   │   │   │   └── UpdateCartQtyHandler.php
│   │   │   │
│   │   │   ├── Order/
│   │   │   │   ├── CreateOrderCommand.php
│   │   │   │   ├── CreateOrderHandler.php
│   │   │   │   ├── UpdateOrderStatusCommand.php
│   │   │   │   └── UpdateOrderStatusHandler.php
│   │   │   │
│   │   │   ├── Product/
│   │   │   │   ├── CreateProductCommand.php
│   │   │   │   ├── CreateProductHandler.php
│   │   │   │   ├── UpdateProductCommand.php
│   │   │   │   ├── UpdateProductHandler.php
│   │   │   │   ├── DeleteProductCommand.php
│   │   │   │   └── DeleteProductHandler.php
│   │   │   │
│   │   │   ├── Review/
│   │   │   │   ├── CreateReviewCommand.php
│   │   │   │   └── CreateReviewHandler.php
│   │   │   │
│   │   │   └── Payment/
│   │   │       ├── CreatePaymentCommand.php
│   │   │       ├── CreatePaymentHandler.php
│   │   │       ├── VerifyPaymentCommand.php
│   │   │       └── VerifyPaymentHandler.php
│   │   │
│   │   └── Queries/                   ← READ side (Queries)
│   │       ├── QueryBus.php
│   │       ├── QueryHandlerInterface.php
│   │       │
│   │       ├── Product/
│   │       │   ├── GetProductListQuery.php
│   │       │   ├── GetProductListHandler.php
│   │       │   ├── GetProductDetailQuery.php
│   │       │   └── GetProductDetailHandler.php
│   │       │
│   │       ├── Order/
│   │       │   ├── GetOrderListQuery.php
│   │       │   ├── GetOrderListHandler.php
│   │       │   ├── GetOrderDetailQuery.php
│   │       │   └── GetOrderDetailHandler.php
│   │       │
│   │       ├── Cart/
│   │       │   ├── GetCartQuery.php
│   │       │   └── GetCartHandler.php
│   │       │
│   │       ├── Report/
│   │       │   ├── GetSalesReportQuery.php
│   │       │   ├── GetSalesReportHandler.php
│   │       │   ├── GetSalesTrendQuery.php
│   │       │   └── GetSalesTrendHandler.php
│   │       │
│   │       └── Review/
│   │           ├── GetReviewsQuery.php
│   │           └── GetReviewsHandler.php
│   │
│   └── Infrastructure/                ← Implementasi konkret (DB, API, dll)
│       ├── Persistence/
│       │   ├── MySQLProductRepository.php
│       │   ├── MySQLOrderRepository.php
│       │   ├── MySQLUserRepository.php
│       │   ├── MySQLCartRepository.php
│       │   ├── MySQLReviewRepository.php
│       │   └── MySQLCategoryRepository.php
│       │
│       ├── Payment/
│       │   └── MidtransService.php
│       │
│       └── Shipping/
│           └── SimulatedShippingService.php   ← Cek ongkir otomatis
│
├── app/                               ← Interface/Presentation Layer
│   │
│   ├── Controllers/
│   │   ├── HomeController.php         ← Katalog & landing page
│   │   ├── AuthController.php         ← Login & Register
│   │   ├── CartController.php         ← Keranjang belanja
│   │   ├── OrderController.php        ← Checkout & tracking
│   │   ├── PaymentController.php      ← Midtrans integration
│   │   ├── ReviewController.php       ← Ulasan & rating
│   │   │
│   │   └── Admin/
│   │       ├── AdminDashboardController.php
│   │       ├── AdminUserController.php
│   │       ├── AdminProductController.php
│   │       ├── AdminCategoryController.php
│   │       ├── AdminOrderController.php
│   │       ├── AdminPaymentController.php
│   │       ├── AdminReviewController.php
│   │       ├── AdminReportController.php
│   │       └── AdminShippingController.php
│   │
│   └── Views/
│       │
│       ├── layouts/
│       │   ├── main.php               ← Layout utama pelanggan
│       │   ├── auth.php               ← Layout login/register
│       │   └── admin.php              ← Layout admin/owner
│       │
│       ├── home/
│       │   └── index.php              ← Katalog produk (halaman utama)
│       │
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       │
│       ├── cart/
│       │   └── index.php
│       │
│       ├── checkout/
│       │   ├── index.php
│       │   └── success.php
│       │
│       ├── order/
│       │   ├── list.php
│       │   ├── detail.php
│       │   └── tracking.php
│       │
│       ├── review/
│       │   └── form.php
│       │
│       ├── product/
│       │   └── detail.php
│       │
│       └── admin/
│           ├── dashboard.php
│           ├── users/
│           │   ├── index.php
│           │   └── form.php
│           ├── products/
│           │   ├── index.php
│           │   └── form.php
│           ├── categories/
│           │   ├── index.php
│           │   └── form.php
│           ├── orders/
│           │   ├── index.php
│           │   └── detail.php
│           ├── payments/
│           │   └── index.php
│           ├── reviews/
│           │   └── index.php
│           ├── shipping/
│           │   └── index.php
│           └── reports/
│               ├── index.php
│               └── print.php
│
├── database/
│   ├── migrations/
│   │   └── create_tables.sql          ← Semua DDL database
│   └── seeders/
│       └── seed.sql                   ← Data awal (kategori, admin, produk)
│
├── public/
│   ├── css/
│   │   └── custom.css
│   ├── js/
│   │   ├── app.js
│   │   ├── cart.js
│   │   └── payment.js
│   └── uploads/
│       └── products/                  ← Folder upload gambar produk
│
└── storage/
    └── logs/
        └── app.log
```

---

## 🚀 Cara Instalasi

### Prasyarat

- XAMPP (PHP 8.0+, MySQL 5.7+)
- Browser modern
- Akun Midtrans (Sandbox untuk testing)

### Langkah Instalasi

#### 1. Clone / Extract Project

```bash
# Letakkan folder di:
C:/xampp/htdocs/karupuak-arsyla/
```

#### 2. Buat Database

- Buka phpMyAdmin → `http://localhost/phpmyadmin`
- Buat database baru: `karupuak_arsyla`
- Import file: `database/migrations/create_tables.sql`
- Import file: `database/seeders/seed.sql`

#### 3. Konfigurasi Database

Edit file `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'karupuak_arsyla');
define('DB_USER', 'root');
define('DB_PASS', '');      // Isi password jika ada
```

#### 4. Konfigurasi Midtrans

ssh -R 80:localhost:80 nokey@localhost.run

Edit file `config/midtrans.php`:

```php
define('MIDTRANS_SERVER_KEY', 'SB-Mid-server-XXXX'); // Dari dashboard Midtrans
define('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-XXXX');
define('MIDTRANS_IS_PRODUCTION', false);              // true jika sudah live
```

#### 5. Set Permission Upload

```
Pastikan folder public/uploads/products/ bisa ditulis (writable)
Di Windows XAMPP: klik kanan → Properties → Security → beri akses Full Control
```

#### 6. Jalankan Aplikasi

Buka browser: `http://localhost/karupuak-arsyla/`

> **Note:** File `.htaccess` sudah dikonfigurasi untuk bekerja otomatis dengan XAMPP tanpa perlu mengubah setting Apache secara manual. Pastikan mod_rewrite aktif (default aktif di XAMPP).

---

## 👤 Akun Default (Setelah Seed)

| Role            | Email            | Password |
| --------------- | ---------------- | -------- |
| Admin           | admin@arsyla.com | admin123 |
| Pemilik         | owner@arsyla.com | owner123 |
| Customer (demo) | demo@arsyla.com  | demo123  |

---

## 🏗️ Arsitektur: CQRS + Clean Architecture

```
┌─────────────────────────────────────────────────────────┐
│                   Presentation Layer                     │
│         (Controllers + Views + Bootstrap UI)            │
└──────────────────────┬──────────────────────────────────┘
                       │
        ┌──────────────┴──────────────┐
        │                             │
        ▼                             ▼
┌──────────────┐            ┌──────────────────┐
│  COMMANDS    │            │    QUERIES        │
│  (Write)     │            │    (Read)         │
│              │            │                   │
│ - Register   │            │ - GetProducts     │
│ - AddToCart  │            │ - GetOrders       │
│ - CreateOrder│            │ - GetSalesReport  │
│ - PayOrder   │            │ - GetCartItems    │
└──────┬───────┘            └────────┬──────────┘
       │                             │
       ▼                             ▼
┌─────────────────────────────────────────────────────────┐
│                   Domain Layer                           │
│    (Entities, Value Objects, Repository Interfaces)     │
└──────────────────────┬──────────────────────────────────┘
                       │
┌──────────────────────▼──────────────────────────────────┐
│                Infrastructure Layer                      │
│     (MySQL Repositories, Midtrans)         │
└─────────────────────────────────────────────────────────┘
```

### Prinsip CQRS yang Diterapkan:

- **Commands** → Mengubah state (write): Register, Login, AddToCart, Checkout, UpdateStatus
- **Queries** → Membaca data (read): GetProducts, GetOrders, GetSalesReport
- **CommandBus** → Dispatch command ke handler yang tepat
- **QueryBus** → Dispatch query ke handler yang tepat

---

## 💳 Alur Pembayaran Midtrans

```
Pelanggan Checkout
       ↓
CreatePaymentCommand
       ↓
MidtransService::createTransaction()
       ↓
Snap.js popup terbuka
       ↓
Pelanggan bayar (Transfer/QRIS/dll)
       ↓
Midtrans Webhook → /payment/callback
       ↓
VerifyPaymentHandler → Update order status
       ↓
Email konfirmasi dikirim
```

---

## 🔐 Role & Akses

| Fitur                | Customer | Admin | Pemilik |
| -------------------- | -------- | ----- | ------- |
| Lihat Katalog        | ✅       | ✅    | ✅      |
| Register/Login       | ✅       | -     | -       |
| Keranjang & Checkout | ✅       | -     | -       |
| Cek Ongkir           | ✅       | -     | -       |
| Tracking Order       | ✅       | -     | -       |
| Ulasan & Rating      | ✅       | -     | -       |
| Kelola Produk        | -        | ✅    | -       |
| Kelola User          | -        | ✅    | -       |
| Verifikasi Bayar     | -        | ✅    | -       |
| Update Pengiriman    | -        | ✅    | -       |
| Kelola Invoice       | -        | ✅    | -       |
| Monitoring Ulasan    | -        | ✅    | -       |
| Cetak Laporan        | -        | ✅    | -       |
| Analisa & Tren Pasar | -        | ✅    | ✅      |

---

## 📊 Database Schema (Ringkasan)

| Tabel            | Keterangan                     |
| ---------------- | ------------------------------ |
| `users`          | Data pelanggan, admin, pemilik |
| `categories`     | Kategori produk                |
| `products`       | Data produk karupuak           |
| `product_images` | Gambar produk (multi)          |
| `carts`          | Keranjang belanja              |
| `cart_items`     | Item dalam keranjang           |
| `orders`         | Header transaksi               |
| `order_items`    | Detail item transaksi          |
| `payments`       | Data pembayaran Midtrans       |
| `shipments`      | Data pengiriman & tracking     |
| `reviews`        | Ulasan & rating pelanggan      |
| `cities`         | Data kota untuk ongkir         |

---

## 📋 Daftar File yang Perlu Dibuat

### BATCH 1 — Core & Config (Prioritas Utama)

- [ ] `index.php`
- [ ] `.htaccess`
- [ ] `config/app.php`
- [ ] `config/database.php`
- [ ] `config/midtrans.php`
- [ ] `core/Router.php`
- [ ] `core/Application.php`
- [ ] `core/Request.php`
- [ ] `core/Response.php`
- [ ] `core/Session.php`
- [ ] `core/Validator.php`
- [ ] `core/Middleware.php`

### BATCH 2 — Domain Layer

- [ ] `src/Domain/Product/Entities/Product.php`
- [ ] `src/Domain/Order/Entities/Order.php`
- [ ] `src/Domain/Order/Entities/OrderItem.php`
- [ ] `src/Domain/User/Entities/User.php`
- [ ] `src/Domain/Cart/Entities/Cart.php`
- [ ] `src/Domain/Cart/Entities/CartItem.php`
- [ ] `src/Domain/Review/Entities/Review.php`
- [ ] `src/Domain/Category/Entities/Category.php`
- [ ] Semua Repository Interface

### BATCH 3 — CQRS Commands & Handlers

- [ ] `src/Application/Commands/CommandBus.php`
- [ ] Auth Commands (Register, Login)
- [ ] Cart Commands (Add, Remove, Update)
- [ ] Order Commands (Create, UpdateStatus)
- [ ] Product Commands (Create, Update, Delete)
- [ ] Payment Commands (Create, Verify)
- [ ] Review Commands (Create)

### BATCH 4 — CQRS Queries & Handlers

- [ ] `src/Application/Queries/QueryBus.php`
- [ ] Product Queries
- [ ] Order Queries
- [ ] Cart Queries
- [ ] Report Queries
- [ ] Review Queries

### BATCH 5 — Infrastructure

- [ ] `src/Infrastructure/Persistence/MySQLProductRepository.php`
- [ ] `src/Infrastructure/Persistence/MySQLOrderRepository.php`
- [ ] `src/Infrastructure/Persistence/MySQLUserRepository.php`
- [ ] `src/Infrastructure/Persistence/MySQLCartRepository.php`
- [ ] `src/Infrastructure/Persistence/MySQLReviewRepository.php`
- [ ] `src/Infrastructure/Payment/MidtransService.php`
- [ ] `src/Infrastructure/Shipping/SimulatedShippingService.php`

### BATCH 6 — Controllers

- [ ] `app/Controllers/HomeController.php`
- [ ] `app/Controllers/AuthController.php`
- [ ] `app/Controllers/CartController.php`
- [ ] `app/Controllers/OrderController.php`
- [ ] `app/Controllers/PaymentController.php`
- [ ] `app/Controllers/ReviewController.php`
- [ ] Semua Admin Controllers

### BATCH 7 — Views & UI (Bootstrap)

- [ ] `app/Views/layouts/main.php`
- [ ] `app/Views/layouts/auth.php`
- [ ] `app/Views/layouts/admin.php`
- [ ] `app/Views/home/index.php` (Katalog)
- [ ] `app/Views/auth/login.php`
- [ ] `app/Views/auth/register.php`
- [ ] `app/Views/cart/index.php`
- [ ] `app/Views/checkout/index.php`
- [ ] `app/Views/checkout/success.php`
- [ ] `app/Views/order/list.php`
- [ ] `app/Views/order/detail.php`
- [ ] `app/Views/order/tracking.php`
- [ ] `app/Views/product/detail.php`
- [ ] `app/Views/review/form.php`
- [ ] Semua Admin Views

### BATCH 8 — Database & Assets

- [ ] `database/migrations/create_tables.sql`
- [ ] `database/seeders/seed.sql`
- [ ] `public/css/custom.css`
- [ ] `public/js/app.js`
- [ ] `public/js/cart.js`
- [ ] `public/js/payment.js`

---

## 🛠️ Tech Stack

| Layer        | Teknologi                  |
| ------------ | -------------------------- |
| Backend      | PHP 8.0+ Native/Vanilla    |
| Database     | MySQL (XAMPP)              |
| Frontend     | Bootstrap 5.3              |
| Icons        | Bootstrap Icons            |
| Charts       | Chart.js (Admin Dashboard) |
| Payment      | Midtrans Snap              |
| Architecture | CQRS + Clean Architecture  |

---

## ❓ Cara Melanjutkan ke Sesi Berikutnya

Jika limit tercapai, lanjutkan dengan mengatakan:

> "Lanjutkan dari BATCH X — [nama batch]"

Urutan pengerjaan yang direkomendasikan:

1. BATCH 1 → BATCH 2 → BATCH 3 → BATCH 4 → BATCH 5 → BATCH 6 → BATCH 7 → BATCH 8

---

_Dibuat untuk: KARUPUAK Kulit ARSYLA © 2026_
