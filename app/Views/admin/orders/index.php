<?php /* app/Views/admin/orders/index.php */ ?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1">Kelola Pesanan</h4>
        <p class="text-muted small mb-0"><?= $pagination['total'] ?> pesanan ditemukan</p>
    </div>
</div>

<!-- Filter -->
<div class="ka-card mb-4">
    <form method="GET" action="<?= APP_URL ?>/admin/orders" class="p-3 d-flex gap-3 flex-wrap align-items-end">
        <input type="text" name="q" class="form-control" style="max-width:220px"
            placeholder="Cari kode / nama..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <select name="status" class="form-select" style="max-width:180px">
            <option value="">Semua Status</option>
            <?php foreach (['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'] as $s): ?>
                <option value="<?= $s ?>" <?= ($_GET['status'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="date" name="from" class="form-control" style="max-width:160px" value="<?= $_GET['from'] ?? '' ?>">
        <input type="date" name="to" class="form-control" style="max-width:160px" value="<?= $_GET['to']   ?? '' ?>">
        <button type="submit" class="btn btn-outline-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
        <a href="<?= APP_URL ?>/admin/orders" class="btn btn-outline-secondary">Reset</a>
    </form>
</div>

<div class="ka-card">
    <div class="table-responsive">
        <table class="table ka-table mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Kota</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Bayar</th>
                    <th>Tgl</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($o->orderCode) ?></code></td>
                        <td><?= htmlspecialchars($o->name) ?></td>
                        <td class="small text-muted"><?= htmlspecialchars($o->cityName) ?></td>
                        <td class="fw-semibold">Rp <?= number_format($o->total, 0, ',', '.') ?></td>
                        <td><span class="badge bg-<?= $o->statusColor() ?>"><?= $o->statusLabel() ?></span></td>
                        <td><span class="badge bg-<?= $o->paymentStatus === 'paid' ? 'success' : 'warning' ?>"><?= ucfirst($o->paymentStatus) ?></span></td>
                        <td class="small text-muted"><?= date('d/m/y H:i', strtotime($o->createdAt)) ?></td>
                        <td>
                            <a href="<?= APP_URL ?>/admin/orders/<?= $o->id ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($pagination['total_pages'] > 1): ?>
        <div class="p-3 d-flex justify-content-center">
            <nav>
                <ul class="pagination ka-pagination mb-0">
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li class="page-item <?= $i == $pagination['page'] ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>