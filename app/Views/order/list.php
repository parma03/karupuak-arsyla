<?php /* app/Views/order/list.php */ ?>
<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-bag-check me-2"></i>Pesanan Saya</h2>

    <!-- Filter status -->
    <div class="d-flex gap-2 flex-wrap mb-4">
        <?php $statuses = [null => 'Semua', 'pending' => 'Menunggu Bayar', 'paid' => 'Dibayar', 'processing' => 'Diproses', 'shipped' => 'Dikirim', 'delivered' => 'Selesai', 'cancelled' => 'Dibatalkan']; ?>
        <?php foreach ($statuses as $k => $v): ?>
            <a href="?<?= $k ? 'status=' . $k : '' ?>"
                class="btn btn-sm <?= ($k === ($_GET['status'] ?? null)) ? 'ka-btn-primary' : 'btn-outline-secondary' ?>">
                <?= $v ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($orders)): ?>
        <div class="text-center py-5">
            <div style="font-size:4rem">📦</div>
            <h4 class="mt-3">Belum Ada Pesanan</h4>
            <a href="<?= APP_URL ?>" class="btn ka-btn-primary mt-2">Mulai Belanja</a>
        </div>
    <?php else: ?>
        <div class="d-flex flex-column gap-3">
            <?php foreach ($orders as $order): ?>
                <div class="ka-card">
                    <div class="ka-card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <span class="fw-bold">#<?= htmlspecialchars($order->orderCode) ?></span>
                            <span class="text-muted ms-2 small"><?= date('d M Y, H:i', strtotime($order->createdAt)) ?></span>
                        </div>
                        <span class="badge bg-<?= $order->statusColor() ?> rounded-pill px-3">
                            <?= $order->statusLabel() ?>
                        </span>
                    </div>
                    <div class="p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <div class="text-muted small mb-1">
                                <i class="bi bi-truck me-1"></i>
                                <?= strtoupper($order->courier) ?> — <?= htmlspecialchars($order->cityName) ?>
                            </div>
                            <div class="fw-bold fs-5 ka-text-primary">Rp <?= number_format($order->total, 0, ',', '.') ?></div>
                        </div>
                        <div class="d-flex gap-2">
                            <?php if ($order->paymentStatus === 'unpaid' && $order->status === 'pending'): ?>
                                <a href="<?= APP_URL ?>/payment/<?= $order->id ?>" class="btn ka-btn-primary btn-sm">
                                    <i class="bi bi-credit-card me-1"></i>Bayar
                                </a>
                            <?php endif; ?>
                            <?php if ($order->status === 'shipped'): ?>
                                <a href="<?= APP_URL ?>/orders/<?= $order->id ?>/tracking" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-geo-alt me-1"></i>Lacak
                                </a>
                                <form method="POST" action="<?= APP_URL ?>/orders/<?= $order->id ?>/confirm"
                                    onsubmit="return confirm('Konfirmasi pesanan sudah diterima?')" class="d-inline">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle me-1"></i>Terima
                                    </button>
                                </form>
                            <?php endif; ?>
                            <a href="<?= APP_URL ?>/orders/<?= $order->id ?>" class="btn btn-outline-secondary btn-sm">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>