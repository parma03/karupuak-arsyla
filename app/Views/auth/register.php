<?php /* app/Views/auth/register.php */ ?>
<div class="text-center mb-4">
    <h3 class="fw-bold">Buat Akun Baru 🎉</h3>
    <p class="text-muted">Bergabunglah dengan ribuan pelanggan Arsyla</p>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger rounded-3"><i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" action="<?= APP_URL ?>/register" novalidate>
    <div class="mb-3">
        <label class="form-label fw-semibold">Nama Lengkap</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
            <input type="text" name="name" class="form-control border-start-0 ps-0 <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                placeholder="Nama lengkap Anda"
                value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
        </div>
        <?php if (isset($errors['name'])): ?><div class="text-danger small mt-1"><?= $errors['name'][0] ?></div><?php endif; ?>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Email</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
            <input type="email" name="email" class="form-control border-start-0 ps-0 <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                placeholder="email@contoh.com"
                value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
        </div>
        <?php if (isset($errors['email'])): ?><div class="text-danger small mt-1"><?= $errors['email'][0] ?></div><?php endif; ?>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">No. Telepon (WhatsApp)</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone text-muted"></i></span>
            <input type="tel" name="phone" class="form-control border-start-0 ps-0"
                placeholder="08xxxxxxxxxx"
                value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Password</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
            <input type="password" name="password" id="pw1"
                class="form-control border-start-0 ps-0 border-end-0 <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                placeholder="Minimal 8 karakter" required>
            <button type="button" class="input-group-text bg-light border-start-0" onclick="togglePassword('pw1',this)">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <?php if (isset($errors['password'])): ?><div class="text-danger small mt-1"><?= $errors['password'][0] ?></div><?php endif; ?>
    </div>

    <div class="mb-4">
        <label class="form-label fw-semibold">Konfirmasi Password</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
            <input type="password" name="password_confirm" id="pw2"
                class="form-control border-start-0 ps-0 border-end-0"
                placeholder="Ulangi password" required>
            <button type="button" class="input-group-text bg-light border-start-0" onclick="togglePassword('pw2',this)">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn ka-btn-primary w-100 py-3 fw-semibold">
        <i class="bi bi-person-check me-2"></i>Buat Akun
    </button>
</form>

<p class="text-center mt-4 mb-0">
    Sudah punya akun? <a href="<?= APP_URL ?>/login" class="ka-link fw-semibold">Masuk di sini</a>
</p>