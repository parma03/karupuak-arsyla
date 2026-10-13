-- ============================================================
-- KARUPUAK Kulit ARSYLA — Database Migration
-- Import ke phpMyAdmin atau jalankan via MySQL CLI
-- ============================================================

CREATE DATABASE IF NOT EXISTS `karupuak_arsyla`
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `karupuak_arsyla`;

-- ── USERS ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(100) NOT NULL,
    `email`      VARCHAR(150) NOT NULL UNIQUE,
    `password`   VARCHAR(255) NOT NULL,
    `phone`      VARCHAR(20)  DEFAULT NULL,
    `address`    TEXT         DEFAULT NULL,
    `city_id`    INT UNSIGNED DEFAULT NULL,
    `city_name`  VARCHAR(100) DEFAULT NULL,
    `province`   VARCHAR(100) DEFAULT NULL,
    `postal_code`VARCHAR(10)  DEFAULT NULL,
    `role`       ENUM('customer','admin','owner') NOT NULL DEFAULT 'customer',
    `is_active`  TINYINT(1) NOT NULL DEFAULT 1,
    `avatar`     VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── CATEGORIES ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `categories` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(100) NOT NULL,
    `slug`        VARCHAR(120) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `image`       VARCHAR(255) DEFAULT NULL,
    `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order`  INT NOT NULL DEFAULT 0,
    `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── PRODUCTS ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `products` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id`  INT UNSIGNED NOT NULL,
    `name`         VARCHAR(200) NOT NULL,
    `slug`         VARCHAR(220) NOT NULL UNIQUE,
    `description`  TEXT DEFAULT NULL,
    `price`        DECIMAL(12,2) NOT NULL DEFAULT 0,
    `price_sale`   DECIMAL(12,2) DEFAULT NULL,
    `stock`        INT NOT NULL DEFAULT 0,
    `weight`       INT NOT NULL DEFAULT 100 COMMENT 'gram, untuk hitung ongkir',
    `image`        VARCHAR(255) DEFAULT NULL,
    `is_active`    TINYINT(1) NOT NULL DEFAULT 1,
    `is_featured`  TINYINT(1) NOT NULL DEFAULT 0,
    `sold_count`   INT NOT NULL DEFAULT 0,
    `rating_avg`   DECIMAL(3,2) NOT NULL DEFAULT 0,
    `rating_count` INT NOT NULL DEFAULT 0,
    `created_at`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── PRODUCT IMAGES ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `product_images` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `image`      VARCHAR(255) NOT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── CARTS ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `carts` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`    INT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── CART ITEMS ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `cart_items` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `cart_id`    INT UNSIGNED NOT NULL,
    `product_id` INT UNSIGNED NOT NULL,
    `qty`        INT NOT NULL DEFAULT 1,
    `price`      DECIMAL(12,2) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`cart_id`)    REFERENCES `carts`(`id`)    ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── ORDERS ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `orders` (
    `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_code`      VARCHAR(30) NOT NULL UNIQUE,
    `user_id`         INT UNSIGNED NOT NULL,
    `name`            VARCHAR(100) NOT NULL,
    `phone`           VARCHAR(20)  NOT NULL,
    `address`         TEXT NOT NULL,
    `city_id`         INT UNSIGNED NOT NULL,
    `city_name`       VARCHAR(100) NOT NULL,
    `province`        VARCHAR(100) NOT NULL,
    `postal_code`     VARCHAR(10)  DEFAULT NULL,
    `courier`         VARCHAR(20)  NOT NULL,
    `courier_service` VARCHAR(50)  NOT NULL,
    `shipping_cost`   DECIMAL(12,2) NOT NULL DEFAULT 0,
    `subtotal`        DECIMAL(12,2) NOT NULL DEFAULT 0,
    `total`           DECIMAL(12,2) NOT NULL DEFAULT 0,
    `notes`           TEXT DEFAULT NULL,
    `status`          ENUM('pending','paid','processing','shipped','delivered','cancelled','refunded')
                       NOT NULL DEFAULT 'pending',
    `payment_status`  ENUM('unpaid','paid','pending','failed') NOT NULL DEFAULT 'unpaid',
    `created_at`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── ORDER ITEMS ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `order_items` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id`     INT UNSIGNED NOT NULL,
    `product_id`   INT UNSIGNED NOT NULL,
    `product_name` VARCHAR(200) NOT NULL,
    `product_image`VARCHAR(255) DEFAULT NULL,
    `qty`          INT NOT NULL DEFAULT 1,
    `price`        DECIMAL(12,2) NOT NULL,
    `subtotal`     DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (`order_id`)   REFERENCES `orders`(`id`)   ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── PAYMENTS ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `payments` (
    `id`               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id`         INT UNSIGNED NOT NULL UNIQUE,
    `midtrans_order_id`VARCHAR(50)  NOT NULL,
    `snap_token`       VARCHAR(255) DEFAULT NULL,
    `payment_type`     VARCHAR(50)  DEFAULT NULL,
    `transaction_id`   VARCHAR(100) DEFAULT NULL,
    `gross_amount`     DECIMAL(12,2) NOT NULL DEFAULT 0,
    `status`           ENUM('pending','settlement','capture','deny','cancel','expire','failure')
                       NOT NULL DEFAULT 'pending',
    `paid_at`          TIMESTAMP NULL DEFAULT NULL,
    `response_json`    LONGTEXT DEFAULT NULL,
    `created_at`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── SHIPMENTS ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `shipments` (
    `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id`        INT UNSIGNED NOT NULL UNIQUE,
    `courier`         VARCHAR(20)  NOT NULL,
    `tracking_number` VARCHAR(100) DEFAULT NULL,
    `status`          ENUM('packing','shipped','in_transit','delivered') NOT NULL DEFAULT 'packing',
    `notes`           TEXT DEFAULT NULL,
    `shipped_at`      TIMESTAMP NULL DEFAULT NULL,
    `delivered_at`    TIMESTAMP NULL DEFAULT NULL,
    `created_at`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── REVIEWS ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `reviews` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`    INT UNSIGNED NOT NULL,
    `product_id` INT UNSIGNED NOT NULL,
    `order_id`   INT UNSIGNED NOT NULL,
    `rating`     TINYINT UNSIGNED NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
    `comment`    TEXT DEFAULT NULL,
    `is_visible` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_review` (`user_id`, `product_id`, `order_id`),
    FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`)    ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`order_id`)   REFERENCES `orders`(`id`)   ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── INDEX PERFORMA ─────────────────────────────────────────
CREATE INDEX idx_products_category   ON products(category_id);
CREATE INDEX idx_products_is_active  ON products(is_active);
CREATE INDEX idx_products_featured   ON products(is_featured);
CREATE INDEX idx_orders_user         ON orders(user_id);
CREATE INDEX idx_orders_status       ON orders(status);
CREATE INDEX idx_orders_created      ON orders(created_at);
CREATE INDEX idx_order_items_order   ON order_items(order_id);
CREATE INDEX idx_reviews_product     ON reviews(product_id);