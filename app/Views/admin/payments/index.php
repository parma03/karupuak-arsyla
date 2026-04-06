<?php /* app/Views/admin/payments/index.php */ ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-credit-card me-2"></i>Verifikasi Pembayaran</h4>
</div>

<div class="ka-card">
    <div class="table-responsive">
        <table class="table ka-table mb-0">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Waktu Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $p): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($p['order_code']) ?></code></td>
                        <td><?= htmlspecialchars($p['customer_name']) ?></td>
                        <td class="fw-semibold">Rp <?= number_format($p['total'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($p['payment_type'] ?? '—') ?></td>
                        <td>
                            <?php $sc = match ($p['status']) {
                                'settlement', 'capture' => 'success',
                                'pending' => 'warning',
                                default => 'danger'
                            }; ?>
                            <span class="badge bg-<?= $sc ?>"><?= ucfirst($p['status']) ?></span>
                        </td>
                        <td class="small text-muted">
                            <?= $p['paid_at'] ? date('d M Y H:i', strtotime($p['paid_at'])) : '—' ?>
                        </td>
                        <td>
                            <?php if (!in_array($p['status'], ['settlement', 'capture'])): ?>
                                <form method="POST" action="<?= APP_URL ?>/admin/payments/<?= $p['id'] ?>/verify" onsubmit="return false" data-confirm="Verifikasi pembayaran ini?">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check-circle me-1"></i>Verifikasi
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-success small"><i class="bi bi-check-circle-fill me-1"></i>Lunas</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>