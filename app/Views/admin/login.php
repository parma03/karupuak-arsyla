<?php /* app/Views/admin/login.php — uses auth layout */ ?>
<div class="text-center mb-4">
    <div style="font-size:3rem">🔐</div>
    <h3 class="fw-bold mt-2">Admin Panel</h3>
    <p class="text-muted">Karupuak Kulit Arsyla</p>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger rounded-3"><i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" action="<?= APP_URL ?>/admin/login">
    <div class="mb-3">
        <label class="form-label fw-semibold">Email</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
            <input type="email" name="email" class="form-control border-start-0 ps-0"
                placeholder="admin@arsyla.com" required>
        </div>
    </div>
    <div class="mb-4">
        <label class="form-label fw-semibold">Password</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
            <input type="password" name="password" id="adminPw"
                class="form-control border-start-0 ps-0 border-end-0" placeholder="••••••••" required>
            <button type="button" class="input-group-text bg-light border-start-0" onclick="togglePassword('adminPw',this)">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>
    <button type="submit" class="btn ka-btn-primary w-100 py-3 fw-semibold">
        <i class="bi bi-shield-lock me-2"></i>Masuk ke Admin
    </button>
</form>