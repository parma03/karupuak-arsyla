<?php /* app/Views/admin/products/form.php */ ?>
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?= APP_URL ?>/admin/products" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0"><?= $product ? 'Edit Produk' : 'Tambah Produk' ?></h4>
</div>

<form method="POST"
    action="<?= $product ? APP_URL . '/admin/products/' . $product->id . '/edit' : APP_URL . '/admin/products/create' ?>"
    enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="ka-card mb-4">
                <div class="ka-card-header fw-semibold">Informasi Produk</div>
                <div class="p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                            value="<?= htmlspecialchars($product?->name ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat->id ?>" <?= ($product?->categoryId == $cat->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="5"
                            placeholder="Deskripsikan produk secara detail..."><?= htmlspecialchars($product?->description ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="ka-card">
                <div class="ka-card-header fw-semibold">Harga & Stok</div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga Normal <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="price" class="form-control"
                                    value="<?= $product?->price ?? '' ?>" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga Diskon</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="price_sale" class="form-control"
                                    value="<?= $product?->priceSale ?? '' ?>" min="0" placeholder="Kosongkan jika tidak ada">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control"
                                value="<?= $product?->stock ?? 0 ?>" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Berat (gram) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="weight" class="form-control"
                                    value="<?= $product?->weight ?? 100 ?>" min="1" required>
                                <span class="input-group-text">gram</span>
                            </div>
                            <small class="text-muted">Digunakan untuk kalkulasi ongkir</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Gambar -->
            <div class="ka-card mb-4">
                <div class="ka-card-header fw-semibold">Foto Produk</div>
                <div class="p-4 text-center">
                    <div class="ka-img-preview mb-3" id="imgPreviewWrap">
                        <img id="imgPreview"
                            src="<?= $product?->imageUrl() ?? APP_URL . '/public/img/no-image.png' ?>"
                            class="rounded-3 border w-100" style="max-height:200px;object-fit:cover;">
                    </div>
                    <input type="file" name="image" id="imageInput" class="form-control" accept="image/*"
                        onchange="previewImage(this)">
                    <small class="text-muted d-block mt-2">JPG / PNG / WebP · Maks 5MB</small>
                </div>
            </div>

            <!-- Status -->
            <div class="ka-card">
                <div class="ka-card-header fw-semibold">Pengaturan</div>
                <div class="p-4">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="chkActive"
                            <?= ($product?->isActive ?? true) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="chkActive">Produk Aktif</label>
                        <div class="text-muted small">Produk tampil di katalog</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="chkFeatured"
                            <?= ($product?->isFeatured ?? false) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="chkFeatured">Produk Unggulan</label>
                        <div class="text-muted small">Tampil di bagian unggulan</div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn ka-btn-primary btn-lg fw-semibold">
                    <i class="bi bi-check-lg me-2"></i><?= $product ? 'Simpan Perubahan' : 'Tambah Produk' ?>
                </button>
                <a href="<?= APP_URL ?>/admin/products" class="btn btn-outline-secondary">Batal</a>
            </div>
        </div>
    </div>
</form>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('imgPreview').src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>