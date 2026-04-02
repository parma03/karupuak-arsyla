<?php /* app/Views/admin/products/index.php */ ?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1">Kelola Produk</h4>
        <p class="text-muted small mb-0"><?= $pagination['total'] ?> produk terdaftar</p>
    </div>
    <a href="<?= APP_URL ?>/admin/products/create" class="btn ka-btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Tambah Produk
    </a>
</div>

<!-- Filter -->
<div class="ka-card mb-4">
    <form method="GET" action="<?= APP_URL ?>/admin/products" class="p-3 d-flex gap-3 flex-wrap align-items-end">
        <div class="flex-grow-1">
            <input type="text" name="q" class="form-control" placeholder="Cari nama produk..."
                value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        </div>
        <div>
            <select name="cat" class="form-select">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat->id ?>" <?= ($_GET['cat'] ?? '') == $cat->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i> Cari</button>
        <a href="<?= APP_URL ?>/admin/products" class="btn btn-outline-secondary">Reset</a>
    </form>
</div>

<div class="ka-card">
    <div class="table-responsive">
        <table class="table ka-table mb-0">
            <thead>
                <tr>
                    <th width="60">Foto</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td>
                            <img src="<?= $p->imageUrl() ?>" width="48" height="48"
                                class="rounded-2 object-fit-cover border">
                        </td>
                        <td>
                            <div class="fw-semibold"><?= htmlspecialchars($p->name) ?></div>
                            <?php if ($p->isFeatured): ?>
                                <span class="badge bg-warning text-dark small">⭐ Unggulan</span>
                            <?php endif; ?>
                            <?php if ($p->isOnSale()): ?>
                                <span class="badge bg-danger small">SALE</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p->categoryName ?? '—') ?></span></td>
                        <td>
                            <div class="fw-semibold">Rp <?= number_format($p->effectivePrice(), 0, ',', '.') ?></div>
                            <?php if ($p->isOnSale()): ?>
                                <small class="text-muted text-decoration-line-through">Rp <?= number_format($p->price, 0, ',', '.') ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?= $p->stock <= 5 ? ($p->stock == 0 ? 'danger' : 'warning') : 'success' ?>">
                                <?= $p->stock ?>
                            </span>
                        </td>
                        <td>
                            <i class="bi bi-star-fill text-warning"></i> <?= number_format($p->ratingAvg, 1) ?>
                            <span class="text-muted small">(<?= $p->ratingCount ?>)</span>
                        </td>
                        <td>
                            <span class="badge bg-<?= $p->isActive ? 'success' : 'secondary' ?>">
                                <?= $p->isActive ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="<?= APP_URL ?>/admin/products/<?= $p->id ?>/edit"
                                    class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="<?= APP_URL ?>/admin/products/<?= $p->id ?>/delete"
                                    onsubmit="return confirm('Hapus produk ini?')">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
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