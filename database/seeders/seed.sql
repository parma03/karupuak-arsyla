-- ============================================================
-- KARUPUAK Kulit ARSYLA — Seeder (Data Awal)
-- Jalankan SETELAH create_tables.sql
-- ============================================================

USE `karupuak_arsyla`;

-- ── USERS ──────────────────────────────────────────────────
-- Password: admin123  → hash bcrypt
INSERT INTO `users` (`name`, `email`, `password`, `phone`, `role`) VALUES
('Administrator',   'admin@arsyla.com', '$2y$10$WJ6xQZ8X2CHMjjYNi1pMGeNA2GjDcwNT/x6FK1RZW4ngmXWkxBK2e', '081234567890', 'admin'),
('Pemilik Toko',    'owner@arsyla.com', '$2y$10$B8ra/rGIuGMNHfR5GlHeoOspiFGgR1e95nbTO5kdN18cxVGWi3iY.', '081234567891', 'owner'),
('Demo Customer',   'demo@arsyla.com',  '$2y$10$bj2IMjK4KYLMcJdO7CRXCO/F4ab3y.WFBq23Smdgp7WgqG8Ky5GXO', '081234567892', 'customer');
-- NOTE: Password di atas adalah hash dari 'admin123' / 'owner123' / 'demo123'
-- Semua sama: 'password' (contoh hash dari Laravel default)
-- Ganti dengan hash real jika diperlukan

-- ── CATEGORIES ─────────────────────────────────────────────
INSERT INTO `categories` (`name`, `slug`, `description`, `sort_order`) VALUES
('Karupuak Original',   'karupuak-original',   'Kerupuk Kulit sapi dengan rasa original gurih', 1),
('Karupuak Pedas',      'karupuak-pedas',      'Kerupuk Kulit dengan bumbu pedas level 1-5',   2),
('Karupuak Balado',     'karupuak-balado',     'Kerupuk Kulit dengan bumbu balado Padang asli', 3),
('Karupuak Barbeque',   'karupuak-barbeque',   'Kerupuk Kulit rasa barbeque smoky',            4),
('Paket Hampers',       'paket-hampers',       'Paket gift box karupuak Kulit premium',        5);

-- ── PRODUCTS ───────────────────────────────────────────────
INSERT INTO `products`
  (`category_id`,`name`,`slug`,`description`,`price`,`price_sale`,`stock`,`weight`,`is_active`,`is_featured`,`rating_avg`,`rating_count`)
VALUES
-- Kategori 1: Original
(1, 'Karupuak Kulit Original 100gr',    'karupuak-Kulit-original-100gr',
 'Kerupuk Kulit sapi berkualitas premium, digoreng dengan minyak kelapa murni. Renyah, gurih, dan tahan lama. Cocok sebagai camilan atau pelengkap makan.',
 25000, NULL, 100, 100, 1, 1, 4.8, 24),

(1, 'Karupuak Kulit Original 250gr',    'karupuak-Kulit-original-250gr',
 'Versi ekonomis karupuak Kulit original dalam kemasan 250 gram. Ideal untuk keluarga.',
 55000, 50000, 80, 250, 1, 0, 4.7, 18),

(1, 'Karupuak Kulit Original 500gr',    'karupuak-Kulit-original-500gr',
 'Kemasan besar 500 gram karupuak Kulit original. Hemat dan bisa tahan lama.',
 100000, 90000, 50, 500, 1, 1, 4.9, 31),

-- Kategori 2: Pedas
(2, 'Karupuak Kulit Pedas Level 1',     'karupuak-Kulit-pedas-l1',
 'Karupuak Kulit dengan tambahan cabai merah pilihan. Level pedas ringan, cocok untuk semua usia.',
 28000, NULL, 90, 100, 1, 0, 4.5, 12),

(2, 'Karupuak Kulit Pedas Level 3',     'karupuak-Kulit-pedas-l3',
 'Level pedas menengah. Sensasi pedas yang pas tanpa rasa terbakar berlebihan.',
 28000, NULL, 70, 100, 1, 1, 4.6, 20),

(2, 'Karupuak Kulit Extra Pedas L5',    'karupuak-Kulit-extra-pedas-l5',
 'Level pedas tertinggi! Hanya untuk pencinta pedas sejati. Berani coba?',
 32000, NULL, 40, 100, 1, 1, 4.4, 15),

-- Kategori 3: Balado
(3, 'Karupuak Kulit Balado 100gr',      'karupuak-Kulit-balado-100gr',
 'Bumbu balado Padang asli yang kaya rempah. Perpaduan sempurna antara pedas dan gurih.',
 30000, NULL, 85, 100, 1, 1, 4.9, 42),

(3, 'Karupuak Kulit Balado 250gr',      'karupuak-Kulit-balado-250gr',
 'Balado dalam kemasan besar untuk keluarga pecinta rasa Padang.',
 65000, 60000, 60, 250, 1, 0, 4.8, 28),

-- Kategori 4: BBQ
(4, 'Karupuak Kulit Barbeque 100gr',    'karupuak-Kulit-barbeque-100gr',
 'Rasa barbeque smoky yang unik pada kerupuk Kulit. Cocok untuk camilan modern.',
 32000, NULL, 75, 100, 1, 0, 4.3, 9),

-- Kategori 5: Hampers
(5, 'Hampers Premium Box 3 Rasa',        'hampers-premium-box-3-rasa',
 'Paket gift box premium berisi 3 varian rasa: Original, Balado, dan Pedas. Cocok untuk hadiah.',
 150000, 135000, 30, 400, 1, 1, 5.0, 7),

(5, 'Hampers Lebaran Special Edition',   'hampers-lebaran-special',
 'Paket spesial lebaran dengan 5 varian rasa dalam kemasan eksklusif. Limited edition.',
 250000, 220000, 15, 600, 1, 1, 5.0, 3);