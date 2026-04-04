<?php /* app/Views/admin/reviews/index.php */ ?>
<div class="mb-4">
    <h4 class="fw-bold"><i class="bi bi-chat-square-text me-2"></i>Kelola Ulasan</h4>
</div>

<div class="ka-card">
    <div class="table-responsive">
        <table class="table ka-table mb-0">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Produk</th>
                    <th>Rating</th>
                    <th>Komentar</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $r): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($r->userName ?? '—') ?></td>
                        <td class="small"><?= htmlspecialchars($r->productName ?? '—') ?></td>
                        <td>
                            <div class="d-flex">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star<?= $i <= $r->rating ? '-fill' : '' ?> text-warning" style="font-size:12px"></i>
                                <?php endfor; ?>
                            </div>
                        </td>
                        <td class="text-muted small" style="max-width:240px">
                            <?= htmlspecialchars(substr($r->comment ?? '—', 0, 100)) ?>
                            <?= strlen($r->comment ?? '') > 100 ? '...' : '' ?>
                        </td>
                        <td class="text-muted small"><?= date('d M Y', strtotime($r->createdAt)) ?></td>
                        <td>
                            <span class="badge bg-<?= $r->isVisible ? 'success' : 'secondary' ?>">
                                <?= $r->isVisible ? 'Tampil' : 'Disembunyikan' ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="<?= APP_URL ?>/admin/reviews/<?= $r->id ?>/delete"
                                onsubmit="return confirm('Hapus ulasan ini?')">
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>