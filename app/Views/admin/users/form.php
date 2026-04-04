<?php /* app/Views/admin/users/form.php */ ?>
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?= APP_URL ?>/admin/users" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0"><?= $user ? 'Edit User' : 'Tambah User' ?></h4>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="ka-card">
            <div class="p-4">
                <form method="POST" action="<?= $user ? APP_URL . '/admin/users/' . $user->id . '/edit' : APP_URL . '/admin/users/create' ?>">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control"
                            value="<?= htmlspecialchars($user?->name ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($user?->email ?? '') ?>"
                            <?= $user ? 'readonly' : '' ?> required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. Telepon</label>
                        <input type="text" name="phone" class="form-control"
                            value="<?= htmlspecialchars($user?->phone ?? '') ?>">
                    </div>
                    <?php if (!$user): ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter">
                            <small class="text-muted">Kosongkan untuk password default: <code>password123</code></small>
                        </div>
                    <?php endif; ?>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" <?= ($user?->isActive ?? true) ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= !($user?->isActive ?? true) ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn ka-btn-primary fw-semibold px-4">
                            <i class="bi bi-check-lg me-2"></i><?= $user ? 'Simpan' : 'Tambah' ?>
                        </button>
                        <a href="<?= APP_URL ?>/admin/users" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>