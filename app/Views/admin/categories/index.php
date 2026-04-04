<?php /* app/Views/admin/categories/index.php */ ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Kelola Kategori</h4>
    <button class="btn ka-btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddCat">
        <i class="bi bi-plus-lg me-2"></i>Tambah Kategori
    </button>
</div>

<div class="ka-card">
    <div class="table-responsive">
        <table class="table ka-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Kategori</th>
                    <th>Slug</th>
                    <th>Produk</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= $cat->id ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($cat->name) ?></td>
                        <td><code class="small"><?= htmlspecialchars($cat->slug) ?></code></td>
                        <td><?= $cat->productCount ?> produk</td>
                        <td><?= $cat->sortOrder ?></td>
                        <td><span class="badge bg-<?= $cat->isActive ? 'success' : 'secondary' ?>"><?= $cat->isActive ? 'Aktif' : 'Nonaktif' ?></span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-outline-primary"
                                    onclick="openEditCat(<?= $cat->id ?>, '<?= addslashes($cat->name) ?>', '<?= addslashes($cat->description ?? '') ?>', <?= $cat->isActive ? 1 : 0 ?>, <?= $cat->sortOrder ?>)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="<?= APP_URL ?>/admin/categories/<?= $cat->id ?>/delete"
                                    onsubmit="return confirm('Hapus kategori ini? Pastikan tidak ada produk terkait.')">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalAddCat" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= APP_URL ?>/admin/categories/store">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Karupuak Balado">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Urutan Tampil</label>
                        <input type="number" name="sort_order" class="form-control" value="0" min="0">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn ka-btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEditCat" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editCatForm" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori</label>
                        <input type="text" name="name" id="editCatName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" id="editCatDesc" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Urutan</label>
                            <input type="number" name="sort_order" id="editCatSort" class="form-control" value="0">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="is_active" id="editCatActive" class="form-select">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn ka-btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditCat(id, name, desc, active, sort) {
        document.getElementById('editCatForm').action = '<?= APP_URL ?>/admin/categories/' + id + '/update';
        document.getElementById('editCatName').value = name;
        document.getElementById('editCatDesc').value = desc;
        document.getElementById('editCatActive').value = active;
        document.getElementById('editCatSort').value = sort;
        new bootstrap.Modal(document.getElementById('modalEditCat')).show();
    }
</script>