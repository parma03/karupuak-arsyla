<?php /* app/Views/admin/users/index.php */ ?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <h4 class="fw-bold mb-0">Kelola Pengguna</h4>
    <a href="<?= APP_URL ?>/admin/users/create" class="btn ka-btn-primary">
        <i class="bi bi-person-plus me-2"></i>Tambah User
    </a>
</div>

<div class="ka-card mb-4">
    <form method="GET" action="<?= APP_URL ?>/admin/users" class="p-3 d-flex gap-3 flex-wrap align-items-end">
        <input type="text" name="q" class="form-control" style="max-width:240px"
            placeholder="Cari nama atau email..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <select name="role" class="form-select" style="max-width:160px">
            <option value="">Semua Role</option>
            <option value="customer" <?= ($_GET['role'] ?? '') === 'customer' ? 'selected' : '' ?>>Customer</option>
            <option value="admin" <?= ($_GET['role'] ?? '') === 'admin'    ? 'selected' : '' ?>>Admin</option>
            <option value="owner" <?= ($_GET['role'] ?? '') === 'owner'    ? 'selected' : '' ?>>Owner</option>
        </select>
        <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search me-1"></i>Cari</button>
        <a href="<?= APP_URL ?>/admin/users" class="btn btn-outline-secondary">Reset</a>
    </form>
</div>

<div class="ka-card">
    <div class="table-responsive">
        <table class="table ka-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u->id ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="ka-avatar-sm"><?= strtoupper(substr($u->name, 0, 1)) ?></div>
                                <span class="fw-semibold"><?= htmlspecialchars($u->name) ?></span>
                            </div>
                        </td>
                        <td class="text-muted small"><?= htmlspecialchars($u->email) ?></td>
                        <td>
                            <span class="badge bg-<?= $u->role === 'owner' ? 'danger' : ($u->role === 'admin' ? 'primary' : 'secondary') ?>">
                                <?= ucfirst($u->role) ?>
                            </span>
                        </td>
                        <td class="text-muted small"><?= htmlspecialchars($u->phone ?? '—') ?></td>
                        <td><span class="badge bg-<?= $u->isActive ? 'success' : 'secondary' ?>"><?= $u->isActive ? 'Aktif' : 'Nonaktif' ?></span></td>
                        <td class="text-muted small">—</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="<?= APP_URL ?>/admin/users/<?= $u->id ?>/edit" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <?php if ($u->role !== 'owner'): ?>
                                    <form method="POST" action="<?= APP_URL ?>/admin/users/<?= $u->id ?>/delete"
                                        onsubmit="return confirm('Hapus user ini?')">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>