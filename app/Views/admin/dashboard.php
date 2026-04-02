<?php /* app/Views/admin/dashboard.php */ ?>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <?php $cards = [
        ['icon' => 'bi-bag-check', 'label' => 'Total Pesanan',    'value' => $stats['total_orders'],    'color' => 'primary',  'sub' => $stats['paid_orders'] . ' sudah bayar'],
        ['icon' => 'bi-cash-stack', 'label' => 'Pendapatan',      'value' => 'Rp ' . number_format($stats['total_revenue'], 0, ',', '.'), 'color' => 'success', 'sub' => 'pesanan lunas'],
        ['icon' => 'bi-people',   'label' => 'Pelanggan',        'value' => $stats['total_customers'], 'color' => 'info',     'sub' => 'total terdaftar'],
        ['icon' => 'bi-box-seam', 'label' => 'Produk Aktif',     'value' => $stats['total_products'],  'color' => 'warning',  'sub' => $stats['low_stock'] . ' stok menipis'],
        ['icon' => 'bi-clock',    'label' => 'Pesanan Pending',  'value' => $stats['pending_orders'],  'color' => 'danger',   'sub' => 'menunggu aksi'],
        ['icon' => 'bi-credit-card', 'label' => 'Belum Dibayar',  'value' => $stats['pending_payment'], 'color' => 'secondary', 'sub' => 'perlu verifikasi'],
    ]; ?>
    <?php foreach ($cards as $c): ?>
        <div class="col-6 col-xl-2">
            <div class="ka-stat-card ka-stat-<?= $c['color'] ?>">
                <div class="ka-stat-icon"><i class="bi <?= $c['icon'] ?>"></i></div>
                <div class="ka-stat-val"><?= $c['value'] ?></div>
                <div class="ka-stat-lbl"><?= $c['label'] ?></div>
                <div class="ka-stat-sub"><?= $c['sub'] ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Chart + Top Products -->
<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="ka-card h-100">
            <div class="ka-card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-bar-chart-line me-2"></i>Penjualan 7 Hari Terakhir</span>
                <a href="<?= APP_URL ?>/admin/reports" class="btn btn-sm btn-outline-primary">Laporan Lengkap</a>
            </div>
            <div class="p-4">
                <canvas id="salesChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="ka-card h-100">
            <div class="ka-card-header fw-semibold"><i class="bi bi-trophy me-2"></i>Produk Terlaris Bulan Ini</div>
            <div class="p-3">
                <?php if (empty($topProducts)): ?>
                    <p class="text-muted text-center py-4">Belum ada data</p>
                <?php else: ?>
                    <?php foreach ($topProducts as $i => $p): ?>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="ka-rank-badge"><?= $i + 1 ?></span>
                            <div class="flex-grow-1">
                                <div class="fw-semibold small"><?= htmlspecialchars($p['name']) ?></div>
                                <div class="text-muted small"><?= $p['sold'] ?> terjual</div>
                            </div>
                            <div class="fw-bold small ka-text-primary">
                                Rp <?= number_format($p['revenue'], 0, ',', '.') ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="ka-card">
    <div class="ka-card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-clock-history me-2"></i>Pesanan Terbaru</span>
        <a href="<?= APP_URL ?>/admin/orders" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table ka-table mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Bayar</th>
                    <th>Tanggal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentOrders as $o): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($o['order_code']) ?></code></td>
                        <td><?= htmlspecialchars($o['customer_name'] ?? '—') ?></td>
                        <td class="fw-semibold">Rp <?= number_format($o['total'], 0, ',', '.') ?></td>
                        <td>
                            <?php
                            $statusColors = ['pending' => 'warning', 'paid' => 'info', 'processing' => 'primary', 'shipped' => 'info', 'delivered' => 'success', 'cancelled' => 'danger'];
                            $sc = $statusColors[$o['status']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $sc ?>"><?= ucfirst($o['status']) ?></span>
                        </td>
                        <td>
                            <span class="badge bg-<?= $o['payment_status'] === 'paid' ? 'success' : 'warning' ?>">
                                <?= ucfirst($o['payment_status']) ?>
                            </span>
                        </td>
                        <td class="text-muted small"><?= date('d M, H:i', strtotime($o['created_at'])) ?></td>
                        <td>
                            <a href="<?= APP_URL ?>/admin/orders/<?= $o['id'] ?>" class="btn btn-xs btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const trendData = <?= json_encode($trend) ?>;
    const labels = trendData.map(d => d.day);
    const revenues = trendData.map(d => parseFloat(d.revenue));
    const ordersD = trendData.map(d => parseInt(d.orders));

    new Chart(document.getElementById('salesChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenues,
                    backgroundColor: 'rgba(180, 120, 60, 0.15)',
                    borderColor: '#b4783c',
                    borderWidth: 2,
                    borderRadius: 6,
                    yAxisID: 'y',
                },
                {
                    label: 'Jumlah Order',
                    data: ordersD,
                    type: 'line',
                    borderColor: '#2c7a4b',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    pointRadius: 4,
                    yAxisID: 'y1',
                    tension: 0.4,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    position: 'left',
                    ticks: {
                        callback: v => 'Rp ' + v.toLocaleString('id-ID')
                    }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
</script>