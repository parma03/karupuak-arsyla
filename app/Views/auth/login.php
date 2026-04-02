<?php /* app/Views/auth/login.php */ ?>
<div class="text-center mb-4">
    <h3 class="fw-bold">Selamat Datang Kembali 👋</h3>
    <p class="text-muted">Masuk ke akun Arsyla Anda</p>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger rounded-3"><i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" action="<?= APP_URL ?>/login" novalidate>
    <div class="mb-3">
        <label class="form-label fw-semibold">Email</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
            <input type="email" name="email" class="form-control border-start-0 ps-0 <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                placeholder="email@contoh.com"
                value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
        </div>
        <?php if (isset($errors['email'])): ?>
            <div class="text-danger small mt-1"><?= $errors['email'][0] ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-4">
        <label class="form-label fw-semibold">Password</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
            <input type="password" name="password" id="passwordInput"
                class="form-control border-start-0 ps-0 border-end-0 <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                placeholder="••••••••" required>
            <button type="button" class="input-group-text bg-light border-start-0" onclick="togglePassword('passwordInput', this)">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <?php if (isset($errors['password'])): ?>
            <div class="text-danger small mt-1"><?= $errors['password'][0] ?></div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn ka-btn-primary w-100 py-3 fw-semibold">
        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
    </button>
</form>

<p class="text-center mt-4 mb-0">
    Belum punya akun? <a href="<?= APP_URL ?>/register" class="ka-link fw-semibold">Daftar sekarang</a>
</p>