<?php /* app/Views/cart/index.php */ ?>
<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-cart3 me-2"></i>Keranjang Belanja</h2>

    <?php if ($cart->isEmpty()): ?>
        <div class="text-center py-5">
            <div style="font-size:5rem">🛒</div>
            <h4 class="mt-3 fw-bold">Keranjang Anda Kosong</h4>
            <p class="text-muted">Yuk tambahkan produk karupuak favoritmu!</p>
            <a href="<?= APP_URL ?>" class="btn ka-btn-primary btn-lg mt-2">
                <i class="bi bi-shop me-2"></i>Mulai Belanja
            </a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="ka-card">
                    <div class="ka-card-header d-flex justify-content-between align-items-center">
                        <span class="fw-semibold"><?= $cart->totalItems() ?> Item</span>
                        <a href="<?= APP_URL ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-plus me-1"></i>Tambah Produk
                        </a>
                    </div>

                    <?php foreach ($cart->items as $item): ?>
                        <div class="ka-cart-item d-flex align-items-center gap-3 p-3 border-bottom" id="cart-item-<?= $item->productId ?>">
                            <a href="<?= APP_URL ?>/product/<?= $item->productId ?>">
                                <img src="<?= $item->productImage ? UPLOAD_URL . $item->productImage : APP_URL . '/public/img/no-image.png' ?>"
                                    class="ka-cart-img rounded-3" alt="<?= htmlspecialchars($item->productName ?? '') ?>">
                            </a>
                            <div class="flex-grow-1">
                                <a href="<?= APP_URL ?>/product/<?= $item->productId ?>" class="ka-cart-name">
                                    <?= htmlspecialchars($item->productName ?? 'Produk') ?>
                                </a>
                                <div class="ka-cart-price mt-1">Rp <?= number_format($item->price, 0, ',', '.') ?></div>
                                <?php if (($item->productStock ?? 99) <= 5): ?>
                                    <small class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Sisa <?= $item->productStock ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="ka-qty-control d-flex align-items-center gap-2">
                                <button class="btn btn-sm btn-outline-secondary ka-qty-btn"
                                    onclick="updateCart(<?= $item->productId ?>, <?= $item->qty - 1 ?>)">−</button>
                                <span class="ka-qty-val" id="qty-<?= $item->productId ?>"><?= $item->qty ?></span>
                                <button class="btn btn-sm btn-outline-secondary ka-qty-btn"
                                    onclick="updateCart(<?= $item->productId ?>, <?= $item->qty + 1 ?>)">+</button>
                            </div>
                            <div class="ka-cart-subtotal fw-bold" id="sub-<?= $item->productId ?>">
                                Rp <?= number_format($item->subtotal(), 0, ',', '.') ?>
                            </div>
                            <button class="btn btn-sm btn-outline-danger rounded-circle"
                                onclick="removeFromCart(<?= $item->productId ?>)" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="ka-card ka-cart-summary sticky-top" style="top:90px">
                    <div class="ka-card-header fw-bold">Ringkasan Pesanan</div>
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal (<?= $cart->totalItems() ?> item)</span>
                            <span class="fw-semibold" id="cart-subtotal">Rp <?= number_format($cart->subtotal(), 0, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Ongkir</span>
                            <span class="text-muted">Dihitung saat checkout</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 ka-text-primary" id="cart-total">Rp <?= number_format($cart->subtotal(), 0, ',', '.') ?></span>
                        </div>
                        <a href="<?= APP_URL ?>/checkout" class="btn ka-btn-primary w-100 btn-lg fw-semibold">
                            <i class="bi bi-bag-check me-2"></i>Lanjut Checkout
                        </a>
                        <a href="<?= APP_URL ?>" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="bi bi-arrow-left me-2"></i>Lanjut Belanja
                        </a>
                    </div>
                    <!-- Payment methods -->
                    <div class="px-4 pb-4">
                        <p class="text-muted small text-center mb-2">Metode pembayaran</p>
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <span class="ka-pay-badge">Transfer Bank</span>
                            <span class="ka-pay-badge">QRIS</span>
                            <span class="ka-pay-badge">GoPay</span>
                            <span class="ka-pay-badge">OVO</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<form id="cart-form" method="POST" style="display:none">
    <input type="hidden" name="product_id" id="cart-product-id">
    <input type="hidden" name="qty" id="cart-qty">
</form>

<script src="<?= APP_URL ?>/public/js/cart.js"></script>