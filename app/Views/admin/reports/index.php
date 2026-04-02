<?php /* app/Views/admin/reports/index.php */ ?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-bar-chart-line me-2"></i>Laporan Penjualan</h4>
    <div class="d-flex gap-2">
        <a href="?<?= http_build_query(array_merge($_GET, ['action' => 'export'])) ?>&_export=1"
            onclick="this.href='<?= APP_URL ?>/admin/reports/export?from=<?= $from ?>&to=<?= $to ?>'"
            class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-excel me-1"></i>Export CSV
        </a>
        <a href="<?= APP_URL ?>/admin/reports/print?from=<?= $from ?>&to=<?= $to ?>"
            target="_blank" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-printer me-1"></i>Cetak
        </a>
    </div>
</div>

<!-- Filter -->
<div class="ka-card mb-4">
    <form method="GET" action="<?= APP_URL ?>/admin/reports" class="p-3 d-flex gap-3 flex-wrap align-items-end">
        <div>
            <label class="form-label small fw-semibold">Dari</label>
            <input type="date" name="from" class="form-control" value="<?= $from ?>">
        </div>
        <div>
            <label class="form-label small fw-semibold">Sampai</label>
            <input type="date" name="to" class="form-control" value="<?= $to ?>">
        </div>
        <div>
            <label class="form-label small fw-semibold">Tampilkan</label>
            <select name="group" class="form-select">
                <option value="daily" <?= $groupBy === 'daily'   ? 'selected' : '' ?>>Per Hari</option>
                <option value="monthly" <?= $groupBy === 'monthly' ? 'selected' : '' ?>>Per Bulan</option>
            </select>
        </div>
        <div class="d-flex align-items-end gap-2">
            <button type="submit" class="btn ka-btn-primary"><i class="bi bi-funnel me-1"></i>Tampilkan</button>
            <!-- Shortcut -->
            <a href="?from=<?= date('Y-m-01') ?>&to=<?= date('Y-m-d') ?>" class="btn btn-outline-secondary btn-sm">Bulan Ini</a>
            <a href="?from=<?= date('Y-m-01', strtotime('-1 month')) ?>&to=<?= date('Y-m-t', strtotime('-1 month')) ?>"
                class="btn btn-outline-secondary btn-sm">Bulan Lalu</a>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<?php $s = $report['summary']; ?>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="ka-stat-card ka-stat-success">
            <div class="ka-stat-val">Rp <?= number_format($s['total_revenue'] ?? 0, 0, ',', '.') ?></div>
            <div class="ka-stat-lbl">Total Pendapatan</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="ka-stat-card ka-stat-primary">
            <div class="ka-stat-val"><?= $s['total_orders'] ?? 0 ?></div>
            <div class="ka-stat-lbl">Total Pesanan</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="ka-stat-card ka-stat-info">
            <div class="ka-stat-val"><?= $s['unique_customers'] ?? 0 ?></div>
            <div class="ka-stat-lbl">Pelanggan Unik</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="ka-stat-card ka-stat-warning">
            <div class="ka-stat-val">Rp <?= number_format($s['avg_order_value'] ?? 0, 0, ',', '.') ?></div>
            <div class="ka-stat-lbl">Rata-rata Order</div>
        </div>
    </div>
</div>

<!-- Chart -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="ka-card h-100">
            <div class="ka-card-header fw-semibold">Grafik Penjualan</div>
            <div class="p-4"><canvas id="reportChart" height="280"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="ka-card h-100">
            <div class="ka-card-header fw-semibold">Penjualan per Kategori</div>
            <div class="p-4"><canvas id="categoryChart" height="280"></canvas></div>
        </div>
    </div>
</div>

<!-- Top Products -->
<div class="row g-4">
    <div class="col-lg-7">
        <div class="ka-card">
            <div class="ka-card-header fw-semibold"><i class="bi bi-trophy me-2"></i>Produk Terlaris</div>
            <div class="table-responsive">
                <table class="table ka-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th>Terjual</th>
                            <th>Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['top_products'] as $i => $p): ?>
                            <tr>
                                <td><span class="ka-rank-badge"><?= $i + 1 ?></span></td>
                                <td class="fw-semibold"><?= htmlspecialchars($p['name']) ?></td>
                                <td><?= $p['total_qty'] ?> pcs</td>
                                <td class="fw-semibold ka-text-primary">Rp <?= number_format($p['total_revenue'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="ka-card">
            <div class="ka-card-header fw-semibold">Trend per Periode</div>
            <div class="table-responsive">
                <table class="table ka-table mb-0">
                    <thead>
                        <tr>
                            <th>Periode</th>
                            <th>Order</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['trend'] as $t): ?>
                            <tr>
                                <td><?= $t['period'] ?></td>
                                <td><?= $t['orders'] ?></td>
                                <td class="fw-semibold">Rp <?= number_format($t['revenue'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const trend = <?= json_encode($report['trend']) ?>;
    const categories = <?= json_encode($report['top_categories']) ?>;

    new Chart(document.getElementById('reportChart'), {
        type: 'line',
        data: {
            labels: trend.map(d => d.period),
            datasets: [{
                label: 'Pendapatan',
                data: trend.map(d => parseFloat(d.revenue)),
                borderColor: '#b4783c',
                backgroundColor: 'rgba(180,120,60,0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: v => 'Rp ' + v.toLocaleString('id-ID')
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: categories.map(c => c.name),
            datasets: [{
                data: categories.map(c => parseFloat(c.total_revenue)),
                backgroundColor: ['#b4783c', '#2c7a4b', '#1a4480', '#e07b39', '#6c757d', '#198754'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>