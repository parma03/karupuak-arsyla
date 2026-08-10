<?php /* app/Views/admin/orders/detail.php */ ?>
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?= APP_URL ?>/admin/orders" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Pesanan #<?= htmlspecialchars($order->orderCode) ?></h4>
    <span class="badge bg-<?= $order->statusColor() ?> fs-6"><?= $order->statusLabel() ?></span>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Items -->
        <div class="ka-card mb-4">
            <div class="ka-card-header fw-semibold">Item Pesanan</div>
            <?php foreach ($items as $item): ?>
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    <img src="<?= $item->productImage ? UPLOAD_URL . $item->productImage : APP_URL . '/public/img/no-image.png' ?>"
                        width="56" height="56" class="rounded-2 object-fit-cover border">
                    <div class="flex-grow-1">
                        <div class="fw-semibold"><?= htmlspecialchars($item->productName) ?></div>
                        <div class="text-muted small">Rp <?= number_format($item->price, 0, ',', '.') ?> × <?= $item->qty ?></div>
                    </div>
                    <div class="fw-bold">Rp <?= number_format($item->subtotal, 0, ',', '.') ?></div>
                </div>
            <?php endforeach; ?>
            <div class="p-3 text-end">
                <div class="text-muted small mb-1">Subtotal: <strong>Rp <?= number_format($order->subtotal, 0, ',', '.') ?></strong></div>
                <div class="text-muted small mb-1">Ongkir (<?= strtoupper($order->courier) ?>): <strong>Rp <?= number_format($order->shippingCost, 0, ',', '.') ?></strong></div>
                <div class="fs-5 fw-bold ka-text-primary">Total: Rp <?= number_format($order->total, 0, ',', '.') ?></div>
            </div>
        </div>

        <!-- Alamat -->
        <div class="ka-card mb-4">
            <div class="ka-card-header fw-semibold"><i class="bi bi-geo-alt me-2"></i>Alamat Pengiriman</div>
            <div class="p-4">
                <div class="fw-semibold"><?= htmlspecialchars($order->name) ?></div>
                <div class="text-muted"><?= htmlspecialchars($order->phone) ?></div>
                <div class="mt-2"><?= nl2br(htmlspecialchars($order->address)) ?></div>
                <div class="text-muted"><?= htmlspecialchars($order->cityName) ?>, <?= htmlspecialchars($order->province) ?></div>
            </div>
        </div>

        <!-- Update status -->
        <div class="ka-card">
            <div class="ka-card-header fw-semibold"><i class="bi bi-arrow-repeat me-2"></i>Update Status</div>
            <?php if ($order->status === 'delivered'): ?>
                <div class="p-4">
                    <div class="alert alert-info mb-0">
                        Pesanan sudah diterima, status tidak dapat diubah lagi.
                    </div>
                </div>
            <?php else: ?>
                <form method="POST" action="<?= APP_URL ?>/admin/orders/<?= $order->id ?>/status" class="p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status Pesanan</label>
                            <select name="status" class="form-select" required>
                                <?php foreach (['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'] as $s): ?>
                                    <option value="<?= $s ?>" <?= $order->status === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. Resi (jika dikirim)</label>
                            <input type="text" name="tracking_number" class="form-control"
                                value="<?= htmlspecialchars($shipment['tracking_number'] ?? '') ?>"
                                placeholder="Contoh: JNE123456789">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Catatan</label>
                            <input type="text" name="notes" class="form-control" placeholder="Catatan untuk status ini (opsional)">
                        </div>
                    </div>
                    <button type="submit" class="btn ka-btn-primary mt-3">
                        <i class="bi bi-check-lg me-2"></i>Update Status
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Payment info -->
        <div class="ka-card mb-4">
            <div class="ka-card-header fw-semibold"><i class="bi bi-credit-card me-2"></i>Info Pembayaran</div>
            <div class="p-4">
                <?php if ($payment): ?>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Status</span>
                        <span class="badge bg-<?= $payment['status'] === 'settlement' ? 'success' : 'warning' ?>">
                            <?= ucfirst($payment['status']) ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Metode</span>
                        <span><?= htmlspecialchars($payment['payment_type'] ?? '—') ?></span>
                    </div>
                    <?php if ($payment['paid_at']): ?>
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted">Waktu Bayar</span>
                            <span class="small"><?= date('d M Y H:i', strtotime($payment['paid_at'])) ?></span>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-muted small mb-0">Belum ada data pembayaran.</p>
                <?php endif; ?>
                <div class="d-flex justify-content-between mt-3">
                    <span class="text-muted">Total Tagihan</span>
                    <span class="fw-bold ka-text-primary">Rp <?= number_format($order->total, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Shipment -->
        <?php if ($shipment): ?>
            <div class="ka-card">
                <div class="ka-card-header fw-semibold"><i class="bi bi-truck me-2"></i>Info Pengiriman</div>
                <div class="p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Kurir</span>
                        <span><?= strtoupper($order->courier) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Resi</span>
                        <span><?= htmlspecialchars($shipment['tracking_number'] ?? '—') ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Status</span>
                        <span class="badge bg-info"><?= ucfirst($shipment['status']) ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>