<?php /* app/Views/order/detail.php */ ?>
<div class="container py-5">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?= APP_URL ?>/orders" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <h2 class="fw-bold mb-0">Pesanan #<?= htmlspecialchars($order->orderCode) ?></h2>
        <span class="badge bg-<?= $order->statusColor() ?> rounded-pill px-3 fs-6"><?= $order->statusLabel() ?></span>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Items -->
            <div class="ka-card mb-4">
                <div class="ka-card-header fw-bold">Item Pesanan</div>
                <?php foreach ($items as $item): ?>
                    <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                        <img src="<?= $item->productImage ? UPLOAD_URL . $item->productImage : APP_URL . '/public/img/no-image.png' ?>"
                            width="64" height="64" class="rounded-3 object-fit-cover">
                        <div class="flex-grow-1">
                            <div class="fw-semibold"><?= htmlspecialchars($item->productName) ?></div>
                            <div class="text-muted small">
                                Rp <?= number_format($item->price, 0, ',', '.') ?> × <?= $item->qty ?>
                            </div>
                        </div>
                        <div class="fw-bold">Rp <?= number_format($item->subtotal, 0, ',', '.') ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Alamat -->
            <div class="ka-card mb-4">
                <div class="ka-card-header fw-bold"><i class="bi bi-geo-alt me-2"></i>Alamat Pengiriman</div>
                <div class="p-4">
                    <div class="fw-semibold"><?= htmlspecialchars($order->name) ?></div>
                    <div class="text-muted"><?= htmlspecialchars($order->phone) ?></div>
                    <div class="mt-2"><?= nl2br(htmlspecialchars($order->address)) ?></div>
                    <div class="text-muted"><?= htmlspecialchars($order->cityName) ?>, <?= htmlspecialchars($order->province) ?></div>
                    <?php if ($order->notes): ?>
                        <div class="mt-2 p-2 bg-light rounded-2 small"><i class="bi bi-chat-left-text me-1"></i><?= htmlspecialchars($order->notes) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pengiriman -->
            <?php if ($shipment): ?>
                <div class="ka-card mb-4">
                    <div class="ka-card-header fw-bold"><i class="bi bi-truck me-2"></i>Info Pengiriman</div>
                    <div class="p-4">
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <div class="text-muted small">Kurir</div>
                                <div class="fw-semibold"><?= strtoupper($order->courier) ?> — <?= htmlspecialchars($order->courierService) ?></div>
                            </div>
                            <div class="col-sm-4">
                                <div class="text-muted small">No. Resi</div>
                                <div class="fw-semibold"><?= $shipment['tracking_number'] ?? '—' ?></div>
                            </div>
                            <div class="col-sm-4">
                                <div class="text-muted small">Status</div>
                                <div class="fw-semibold"><?= ucfirst($shipment['status']) ?></div>
                            </div>
                        </div>
                        <?php if ($shipment['tracking_number']): ?>
                            <a href="<?= APP_URL ?>/orders/<?= $order->id ?>/tracking" class="btn btn-outline-info btn-sm mt-3">
                                <i class="bi bi-geo-alt me-1"></i>Lacak Paket
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Review -->
            <?php if ($order->status === 'delivered'): ?>
                <div class="ka-card">
                    <div class="ka-card-header fw-bold"><i class="bi bi-star me-2"></i>Ulasan Produk</div>
                    <div class="p-4">
                        <?php foreach ($items as $item): ?>
                            <?php if (in_array($item->productId, $reviewedProducts ?? [])): ?>
                                <div class="d-flex align-items-center gap-2 mb-2 text-success">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span><?= htmlspecialchars($item->productName) ?> — sudah diulas</span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php
                        $unreviewedCount = count(array_filter($items, function ($i) use ($reviewedProducts) {
                            return !in_array($i->productId, $reviewedProducts ?? []);
                        }));
                        ?>
                        <?php if ($unreviewedCount > 0): ?>
                            <a href="<?= APP_URL ?>/review/<?= $order->id ?>" class="btn ka-btn-primary">
                                <i class="bi bi-star me-2"></i>Berikan Ulasan
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Summary -->
        <div class="col-lg-4">
            <div class="ka-card sticky-top" style="top:90px">
                <div class="ka-card-header fw-bold">Ringkasan Pembayaran</div>
                <div class="p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>Rp <?= number_format($order->subtotal, 0, ',', '.') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Ongkos Kirim</span>
                        <span>Rp <?= number_format($order->shippingCost, 0, ',', '.') ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold mb-4">
                        <span>Total</span>
                        <span class="ka-text-primary">Rp <?= number_format($order->total, 0, ',', '.') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Status Bayar</span>
                        <span class="badge bg-<?= $order->paymentStatus === 'paid' ? 'success' : 'warning' ?>">
                            <?= ucfirst($order->paymentStatus) ?>
                        </span>
                    </div>
                    <?php if ($payment): ?>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Metode</span>
                            <span><?= htmlspecialchars($payment['payment_type'] ?? '—') ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($order->paymentStatus === 'unpaid' && $order->status === 'pending'): ?>
                        <a href="<?= APP_URL ?>/payment/<?= $order->id ?>" class="btn ka-btn-primary w-100 mt-3">
                            <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>