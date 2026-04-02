<?php /* app/Views/home/index.php */ ?>

<!-- HERO -->
<section class="ka-hero">
    <div class="container">
        <div class="row align-items-center min-vh-60">
            <div class="col-lg-6">
                <span class="ka-hero-badge mb-3 d-inline-block">🏆 Kerupuk Jangek #1 Minangkabau</span>
                <h1 class="ka-hero-title">Karupuak<br><span class="ka-hero-accent">Jangek Arsyla</span></h1>
                <p class="ka-hero-desc">Kerupuk kulit sapi premium, digoreng dengan minyak kelapa murni. Renyah sempurna, gurih tiada duanya — langsung dari dapur Minang ke meja makan Anda.</p>
                <div class="d-flex gap-3 flex-wrap mt-4">
                    <a href="#katalog" class="btn ka-btn-primary btn-lg px-4">
                        <i class="bi bi-bag me-2"></i>Belanja Sekarang
                    </a>
                    <a href="#featured" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-star me-2"></i>Produk Unggulan
                    </a>
                </div>
                <div class="ka-hero-stats d-flex gap-4 mt-5">
                    <div>
                        <div class="ka-stat-num">5K+</div>
                        <div class="ka-stat-label">Pelanggan</div>
                    </div>
                    <div class="ka-stat-divider"></div>
                    <div>
                        <div class="ka-stat-num">4.9★</div>
                        <div class="ka-stat-label">Rating</div>
                    </div>
                    <div class="ka-stat-divider"></div>
                    <div>
                        <div class="ka-stat-num">10+</div>
                        <div class="ka-stat-label">Varian Rasa</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-flex justify-content-center">
                <div class="ka-hero-image-wrap">
                    <div class="ka-hero-blob"></div>
                    <div class="ka-hero-emoji-float">🥨</div>
                    <div class="ka-hero-emoji-float2">✨</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- KATEGORI -->
<section class="py-5 bg-white" id="kategori">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="ka-section-title">Kategori Produk</h2>
        </div>
        <div class="d-flex gap-3 flex-wrap">
            <a href="<?= APP_URL ?>" class="ka-cat-chip <?= !($activeCategory ?? null) ? 'active' : '' ?>">
                <i class="bi bi-grid-3x3-gap me-1"></i> Semua
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?= APP_URL ?>?category=<?= $cat->id ?>"
                    class="ka-cat-chip <?= ($activeCategory == $cat->id) ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat->name) ?>
                    <?php if ($cat->productCount > 0): ?>
                        <span class="ms-1 badge bg-light text-dark"><?= $cat->productCount ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- PRODUK UNGGULAN -->
<?php if (!empty($featured) && !($search ?? '') && !($activeCategory ?? null)): ?>
    <section class="py-5" id="featured" style="background:var(--ka-light)">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="ka-section-title"><i class="bi bi-star-fill text-warning me-2"></i>Produk Unggulan</h2>
            </div>
            <div class="row g-4">
                <?php foreach ($featured as $p): ?>
                    <?php include __DIR__ . '/../partials/product_card.php'; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- KATALOG LENGKAP -->
<section class="py-5" id="katalog">
    <div class="container">
        <!-- Header + Filter -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <h2 class="ka-section-title mb-1">
                    <?php if ($search ?? ''): ?>
                        Hasil pencarian: "<?= htmlspecialchars($search) ?>"
                    <?php elseif ($activeCategory ?? null): ?>
                        <?php foreach ($categories as $cat): if ($cat->id == $activeCategory): ?>
                                <?= htmlspecialchars($cat->name) ?>
                        <?php endif;
                        endforeach; ?>
                    <?php else: ?>
                        Semua Produk
                    <?php endif; ?>
                </h2>
                <p class="text-muted small mb-0"><?= $pagination['total'] ?> produk ditemukan</p>
            </div>
            <form method="GET" action="<?= APP_URL ?>" class="d-flex align-items-center gap-2">
                <?php if ($activeCategory ?? null): ?>
                    <input type="hidden" name="category" value="<?= $activeCategory ?>">
                <?php endif; ?>
                <?php if ($search ?? ''): ?>
                    <input type="hidden" name="q" value="<?= htmlspecialchars($search) ?>">
                <?php endif; ?>
                <select name="sort" class="form-select form-select-sm ka-sort-select" onchange="this.form.submit()">
                    <option value="created_at" <?= ($pagination['page'] ?? '') ? '' : 'selected' ?>>Terbaru</option>
                    <option value="price" <?= isset($_GET['sort']) && $_GET['sort'] === 'price' ? 'selected' : '' ?>>Harga Terendah</option>
                    <option value="rating_avg" <?= isset($_GET['sort']) && $_GET['sort'] === 'rating_avg' ? 'selected' : '' ?>>Rating Tertinggi</option>
                    <option value="sold_count" <?= isset($_GET['sort']) && $_GET['sort'] === 'sold_count' ? 'selected' : '' ?>>Terlaris</option>
                </select>
            </form>
        </div>

        <?php if (empty($products)): ?>
            <div class="text-center py-5">
                <div style="font-size:4rem">🔍</div>
                <h4 class="mt-3">Produk tidak ditemukan</h4>
                <p class="text-muted">Coba kata kunci lain atau lihat semua produk.</p>
                <a href="<?= APP_URL ?>" class="btn ka-btn-primary">Lihat Semua Produk</a>
            </div>
        <?php else: ?>
            <div class="row g-4" id="productGrid">
                <?php foreach ($products as $p): ?>
                    <?php include __DIR__ . '/../partials/product_card.php'; ?>
                <?php endforeach; ?>
            </div>

            <!-- PAGINATION -->
            <?php if ($pagination['total_pages'] > 1): ?>
                <nav class="mt-5 d-flex justify-content-center">
                    <ul class="pagination ka-pagination">
                        <?php if ($pagination['page'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['page'] - 1])) ?>">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                            <li class="page-item <?= $i == $pagination['page'] ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['page'] + 1])) ?>">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<!-- WHY US -->
<section class="py-5 ka-why-section">
    <div class="container">
        <h2 class="ka-section-title text-center mb-5">Mengapa Pilih Arsyla?</h2>
        <div class="row g-4 text-center">
            <?php $whys = [
                ['icon' => '🌿', 'title' => 'Bahan Alami', 'desc' => 'Kulit sapi pilihan tanpa pengawet buatan'],
                ['icon' => '🔥', 'title' => 'Renyah Sempurna', 'desc' => 'Digoreng dua kali untuk kerenyahan maksimal'],
                ['icon' => '🚚', 'title' => 'Kirim ke Seluruh Indonesia', 'desc' => 'Dikemas vakum agar tetap renyah saat tiba'],
                ['icon' => '⭐', 'title' => 'Rating 4.9/5', 'desc' => 'Ribuan pelanggan puas se-Nusantara'],
            ]; ?>
            <?php foreach ($whys as $w): ?>
                <div class="col-6 col-lg-3">
                    <div class="ka-why-card">
                        <div class="ka-why-icon"><?= $w['icon'] ?></div>
                        <h5 class="fw-bold mt-3"><?= $w['title'] ?></h5>
                        <p class="text-muted small mb-0"><?= $w['desc'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>