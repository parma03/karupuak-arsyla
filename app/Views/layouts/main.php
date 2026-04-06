<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? APP_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/public/css/custom.css" rel="stylesheet">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark ka-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= APP_URL ?>">
                <span class="ka-logo-icon">🥨</span>
                <span class="ka-brand-text">Arsyla</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <!-- Search -->
                <form class="d-flex mx-auto ka-search-form" action="<?= APP_URL ?>" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control ka-search-input" name="q"
                            placeholder="Cari karupuak..."
                            value="<?= htmlspecialchars($search ?? '') ?>">
                        <button class="btn ka-search-btn" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
                <!-- Nav links -->
                <ul class="navbar-nav ms-auto align-items-center gap-1">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>"><i class="bi bi-house me-1"></i>Beranda</a>
                    </li>
                    <?php if (\Core\Session::isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="<?= APP_URL ?>/cart">
                                <i class="bi bi-cart3 fs-5"></i>
                                <?php if (($cartCount ?? 0) > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-badge">
                                        <?= $cartCount ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                                <div class="ka-avatar-sm"><?= strtoupper(substr(\Core\Session::get('user_name'), 0, 1)) ?></div>
                                <span><?= htmlspecialchars(\Core\Session::get('user_name')) ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                <li><a class="dropdown-item" href="<?= APP_URL ?>/orders"><i class="bi bi-bag-check me-2"></i>Pesanan Saya</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="<?= APP_URL ?>/logout"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>/login"><i class="bi bi-person me-1"></i>Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn ka-btn-primary ms-2" href="<?= APP_URL ?>/register">Daftar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- FLASH MESSAGES -->
    <?php foreach (['success', 'danger', 'warning', 'info'] as $type): ?>
        <?php $msg = (new \Core\Response())->getFlash($type); ?>
        <?php if ($msg): ?>
            <div class="alert alert-<?= $type ?> alert-dismissible fade show mb-0 rounded-0 text-center" role="alert">
                <i class="bi bi-<?= $type === 'success' ? 'check-circle' : ($type === 'danger' ? 'x-circle' : 'info-circle') ?> me-2"></i>
                <?= htmlspecialchars($msg) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- MAIN CONTENT -->
    <main>
        <?= $content ?>
    </main>

    <!-- FOOTER -->
    <footer class="ka-footer mt-5 pt-5 pb-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span style="font-size:2rem">🥨</span>
                        <span class="ka-brand-text text-white fs-4">Arsyla</span>
                    </div>
                    <p class="text-white-50 small">Kerupuk jangek sapi premium khas Minangkabau. Dibuat dengan bahan pilihan, digoreng dengan cinta.</p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="ka-social-link"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="ka-social-link"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="ka-social-link"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <h6 class="text-white fw-bold mb-3">Produk</h6>
                    <ul class="list-unstyled">
                        <?php foreach (($categories ?? []) as $cat): ?>
                            <li><a href="<?= APP_URL ?>?category=<?= $cat->id ?>" class="ka-footer-link"><?= htmlspecialchars($cat->name) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-lg-3 col-6">
                    <h6 class="text-white fw-bold mb-3">Layanan</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= APP_URL ?>/orders" class="ka-footer-link">Lacak Pesanan</a></li>
                        <li><a href="<?= APP_URL ?>/cart" class="ka-footer-link">Keranjang</a></li>
                        <li><a href="#" class="ka-footer-link">Kebijakan Privasi</a></li>
                        <li><a href="#" class="ka-footer-link">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="text-white fw-bold mb-3">Kontak</h6>
                    <ul class="list-unstyled text-white-50 small">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>Padang, Sumatera Barat</li>
                        <li class="mb-2"><i class="bi bi-whatsapp me-2"></i>+62 812-3456-7890</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i>arsyla@karupuak.id</li>
                    </ul>
                    <div class="mt-3">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/Stripe_Logo%2C_revised_2016.svg/200px-Stripe_Logo%2C_revised_2016.svg.png"
                            height="24" alt="Midtrans" class="opacity-50 me-2">
                    </div>
                </div>
            </div>
            <hr class="border-secondary mt-4">
            <p class="text-center text-white-50 small mb-0">© <?= date('Y') ?> Karupuak Jangek Arsyla. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= APP_URL ?>/public/js/app.js"></script>
</body>

</html>