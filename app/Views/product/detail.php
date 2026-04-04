<?php /* app/Views/product/detail.php */ ?>
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= APP_URL ?>" class="ka-link">Beranda</a></li>
            <li class="breadcrumb-item">
                <a href="<?= APP_URL ?>?category=<?= $product->categoryId ?>" class="ka-link">
                    <?= htmlspecialchars($category?->name ?? 'Produk') ?>
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product->name) ?></li>
        </ol>
    </nav>

    <div class="row g-5 mb-5">
        <!-- Gambar -->
        <div class="col-lg-5">
            <div class="ka-product-detail-img-wrap sticky-top" style="top:90px">
                <img src="<?= $product->imageUrl() ?>" alt="<?= htmlspecialchars($product->name) ?>"
                    class="w-100 rounded-3 border" style="max-height:420px;object-fit:cover;">
                <?php if ($product->isOnSale()): ?>
                    <span class="badge bg-danger position-absolute top-0 start-0 m-3 fs-6">SALE</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Info -->
        <div class="col-lg-7">
            <?php if ($product->isFeatured): ?>
                <span class="badge bg-warning text-dark mb-2">⭐ Produk Unggulan</span>
            <?php endif; ?>

            <h1 class="fw-bold mb-2" style="font-family:'Playfair Display',serif">
                <?= htmlspecialchars($product->name) ?>
            </h1>

            <!-- Rating -->
            <?php if ($product->ratingCount > 0): ?>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="d-flex">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="bi bi-star<?= $i <= round($product->ratingAvg) ? '-fill' : ($i - 0.5 <= $product->ratingAvg ? '-half' : '') ?> text-warning"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="fw-semibold"><?= number_format($product->ratingAvg, 1) ?></span>
                    <span class="text-muted">(<?= $product->ratingCount ?> ulasan)</span>
                    <span class="text-muted">·</span>
                    <span class="text-muted"><?= $product->soldCount ?> terjual</span>
                </div>
            <?php endif; ?>

            <!-- Harga -->
            <div class="d-flex align-items-baseline gap-3 mb-3">
                <?php if ($product->isOnSale()): ?>
                    <span class="fw-bold" style="font-size:2rem;color:#dc3545">
                        Rp <?= number_format($product->priceSale, 0, ',', '.') ?>
                    </span>
                    <span class="text-muted text-decoration-line-through fs-5">
                        Rp <?= number_format($product->price, 0, ',', '.') ?>
                    </span>
                    <span class="badge bg-danger">
                        -<?= round((1 - $product->priceSale / $product->price) * 100) ?>%
                    </span>
                <?php else: ?>
                    <span class="fw-bold" style="font-size:2rem;color:var(--ka-primary)">
                        Rp <?= number_format($product->price, 0, ',', '.') ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Stok -->
            <div class="mb-4">
                <?php if ($product->stock > 10): ?>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                        <i class="bi bi-check-circle me-1"></i>Stok tersedia (<?= $product->stock ?>)
                    </span>
                <?php elseif ($product->stock > 0): ?>
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                        <i class="bi bi-exclamation-circle me-1"></i>Sisa <?= $product->stock ?>
                    </span>
                <?php else: ?>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                        <i class="bi bi-x-circle me-1"></i>Stok habis
                    </span>
                <?php endif; ?>
                <span class="text-muted small ms-2">
                    <i class="bi bi-box-seam me-1"></i>Berat: <?= $product->weight ?>g
                </span>
            </div>

            <!-- Deskripsi -->
            <?php if ($product->description): ?>
                <div class="mb-4">
                    <h6 class="fw-bold mb-2">Deskripsi Produk</h6>
                    <p class="text-muted lh-lg"><?= nl2br(htmlspecialchars($product->description)) ?></p>
                </div>
            <?php endif; ?>

            <!-- Qty + Add to cart -->
            <?php if ($product->stock > 0): ?>
                <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
                    <div class="d-flex align-items-center gap-0 border rounded-3 overflow-hidden">
                        <button class="btn btn-light px-3 py-2" onclick="changeQty(-1)">−</button>
                        <input type="number" id="detailQty" value="1" min="1" max="<?= $product->stock ?>"
                            class="form-control border-0 text-center fw-bold" style="width:64px">
                        <button class="btn btn-light px-3 py-2" onclick="changeQty(1)">+</button>
                    </div>
                    <button class="btn ka-btn-primary btn-lg px-5 fw-semibold flex-grow-1"
                        onclick="addToCartWithQty(<?= $product->id ?>)"
                        <?= !\Core\Session::isLoggedIn() ? 'data-login-required="1"' : '' ?>>
                        <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                    </button>
                </div>
            <?php else: ?>
                <button class="btn btn-secondary btn-lg w-100 mb-4" disabled>Stok Habis</button>
            <?php endif; ?>

            <!-- Info pengiriman -->
            <div class="d-flex gap-3 flex-wrap">
                <div class="d-flex align-items-center gap-2 text-muted small">
                    <i class="bi bi-truck text-success"></i> Gratis ongkir min. pembelian tertentu
                </div>
                <div class="d-flex align-items-center gap-2 text-muted small">
                    <i class="bi bi-shield-check text-primary"></i> Garansi kualitas premium
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews -->
    <div class="ka-card mb-5">
        <div class="ka-card-header fw-bold"><i class="bi bi-star me-2"></i>Ulasan Pelanggan</div>
        <div class="p-4">
            <?php if (empty($reviews)): ?>
                <div class="text-center py-4">
                    <div style="font-size:3rem">💬</div>
                    <p class="text-muted mt-2">Belum ada ulasan. Jadilah yang pertama!</p>
                </div>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="d-flex gap-3 mb-4 pb-4 border-bottom">
                        <div class="ka-avatar-sm flex-shrink-0">
                            <?= strtoupper(substr($review->userName ?? 'U', 0, 1)) ?>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-semibold"><?= htmlspecialchars($review->userName ?? 'Pelanggan') ?></span>
                                <div>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?= $i <= $review->rating ? '-fill' : '' ?> text-warning" style="font-size:12px"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-muted small"><?= date('d M Y', strtotime($review->createdAt)) ?></span>
                            </div>
                            <?php if ($review->comment): ?>
                                <p class="mb-0 text-muted"><?= nl2br(htmlspecialchars($review->comment)) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Related -->
    <?php if (!empty($related)): ?>
        <h3 class="ka-section-title mb-4">Produk Terkait</h3>
        <div class="row g-4">
            <?php foreach ($related as $p): ?>
                <?php include __DIR__ . '/../partials/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    function changeQty(delta) {
        const input = document.getElementById('detailQty');
        const max = parseInt(input.max);
        const newVal = Math.min(max, Math.max(1, parseInt(input.value) + delta));
        input.value = newVal;
    }

    function addToCartWithQty(productId) {
        const qty = parseInt(document.getElementById('detailQty').value) || 1;
        const btn = event.target.closest('button');
        if (btn.dataset.loginRequired) {
            window.location.href = APP_URL + '/login';
            return;
        }

        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menambah...';

        fetch(APP_URL + '/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `product_id=${productId}&qty=${qty}`,
            })
            .then(r => r.json())
            .then(res => {
                if (res.redirect) {
                    window.location.href = res.redirect;
                    return;
                }
                if (res.success) {
                    updateCartBadge(res.cart_count);
                    btn.innerHTML = '<i class="bi bi-check2 me-2"></i>Ditambahkan!';
                    btn.style.background = '#2c7a4b';
                    setTimeout(() => {
                        btn.innerHTML = original;
                        btn.disabled = false;
                        btn.style = '';
                    }, 2000);
                    showToast(res.message, 'success');
                } else {
                    showToast(res.message, 'danger');
                    btn.innerHTML = original;
                    btn.disabled = false;
                }
            });
    }
</script>