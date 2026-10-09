<?php /* app/Views/partials/product_card.php — variabel: $p (Product entity) */ ?>
<div class="col-6 col-md-4 col-lg-3">
    <div class="ka-product-card h-100">
        <?php if ($p->isFeatured): ?>
            <span class="ka-product-badge-featured">⭐ Unggulan</span>
        <?php elseif ($p->isOnSale()): ?>
            <span class="ka-product-badge-sale">SALE</span>
        <?php endif; ?>

        <a href="<?= APP_URL ?>/product/<?= $p->id ?>" class="ka-product-img-wrap">
            <img src="<?= $p->imageUrl() ?>" alt="<?= htmlspecialchars($p->name) ?>" class="ka-product-img" loading="lazy">
        </a>

        <div class="ka-product-body">
            <div class="ka-product-category"><?= htmlspecialchars($p->categoryName ?? '') ?></div>
            <h6 class="ka-product-name">
                <a href="<?= APP_URL ?>/product/<?= $p->id ?>"><?= htmlspecialchars($p->name) ?></a>
            </h6>

            <!-- Rating otomatis berdasarkan jumlah terjual -->
            <div class="ka-product-rating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="bi bi-star<?= $i <= $p->automaticRating() ? '-fill' : '' ?> text-warning" style="font-size:11px"></i>
                <?php endfor; ?>
                <span class="ms-1 text-muted" style="font-size:11px">
                    (<?= $p->automaticRating() ?>,0 · <?= $p->soldCount ?> terjual)
                </span>
            </div>

            <!-- Harga -->
            <div class="ka-product-price mt-2">
                <?php if ($p->isOnSale()): ?>
                    <span class="ka-price-sale">Rp <?= number_format($p->priceSale, 0, ',', '.') ?></span>
                    <span class="ka-price-original">Rp <?= number_format($p->price, 0, ',', '.') ?></span>
                <?php else: ?>
                    <span class="ka-price-main">Rp <?= number_format($p->price, 0, ',', '.') ?></span>
                <?php endif; ?>
            </div>

            <!-- Stok -->
            <?php if ($p->stock <= 5 && $p->stock > 0): ?>
                <div class="ka-stock-warning"><i class="bi bi-exclamation-circle me-1"></i>Sisa <?= $p->stock ?></div>
            <?php elseif ($p->stock === 0): ?>
                <div class="ka-stock-empty"><i class="bi bi-x-circle me-1"></i>Habis</div>
            <?php endif; ?>
        </div>

        <div class="ka-product-footer">
            <?php if ($p->stock > 0): ?>
                <button class="btn ka-btn-add-cart w-100"
                    onclick="addToCart(<?= $p->id ?>, this)"
                    <?= !\Core\Session::isLoggedIn() ? 'data-login-required="1"' : '' ?>>
                    <i class="bi bi-cart-plus me-1"></i> Tambah
                </button>
            <?php else: ?>
                <button class="btn btn-secondary w-100" disabled>Stok Habis</button>
            <?php endif; ?>
        </div>
    </div>
</div>