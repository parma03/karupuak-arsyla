<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin') ?> — Arsyla Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/public/css/custom.css" rel="stylesheet">
</head>

<body class="ka-admin-body">

    <!-- Sidebar -->
    <div class="ka-sidebar" id="sidebar">
        <div class="ka-sidebar-brand">
            <span class="fs-2">🥨</span>
            <span class="ka-sidebar-title">Arsyla</span>
            <span class="ka-sidebar-sub">Admin Panel</span>
        </div>
        <nav class="ka-sidebar-nav">
            <?php
            $uri    = trim($_GET['url'] ?? '', '/');
            $isOwner = \Core\Session::isOwner();
            $menu   = [
                ['icon' => 'bi-speedometer2', 'label' => 'Dashboard',       'url' => 'admin'],
                ['icon' => 'bi-bag-check',   'label' => 'Pesanan',         'url' => 'admin/orders'],
                ['icon' => 'bi-credit-card', 'label' => 'Pembayaran',      'url' => 'admin/payments'],
                ['icon' => 'bi-truck',       'label' => 'Pengiriman',      'url' => 'admin/shipping'],
                ['icon' => 'bi-box-seam',    'label' => 'Produk',          'url' => 'admin/products'],
                ['icon' => 'bi-tags',        'label' => 'Kategori',        'url' => 'admin/categories'],
                ['icon' => 'bi-people',      'label' => 'Pengguna',        'url' => 'admin/users'],
                ['icon' => 'bi-chat-square-text', 'label' => 'Ulasan',      'url' => 'admin/reviews'],
                ['icon' => 'bi-bar-chart-line', 'label' => 'Laporan',       'url' => 'admin/reports'],
            ];
            foreach ($menu as $m):
                $active = ($m['url'] === 'admin')
                    ? ($uri === 'admin' ? 'active' : '')
                    : (str_starts_with($uri, $m['url']) ? 'active' : '');
            ?>
                <a href="<?= APP_URL ?>/<?= $m['url'] ?>" class="ka-sidebar-link <?= $active ?>">
                    <i class="bi <?= $m['icon'] ?>"></i>
                    <span><?= $m['label'] ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="ka-sidebar-footer">
            <div class="d-flex align-items-center gap-2 p-3">
                <div class="ka-avatar-sm"><?= strtoupper(substr(\Core\Session::get('user_name', 'A'), 0, 1)) ?></div>
                <div>
                    <div class="text-white small fw-semibold"><?= htmlspecialchars(\Core\Session::get('user_name', 'Admin')) ?></div>
                    <div class="text-white-50" style="font-size:11px"><?= ucfirst(\Core\Session::userRole()) ?></div>
                </div>
                <a href="<?= APP_URL ?>/admin/logout" class="ms-auto text-white-50" title="Keluar"><i class="bi bi-box-arrow-right fs-5"></i></a>
            </div>
        </div>
    </div>

    <!-- Main -->
    <div class="ka-admin-main" id="adminMain">
        <!-- Top bar -->
        <div class="ka-topbar d-flex align-items-center justify-content-between px-4 py-3">
            <button class="btn btn-sm btn-outline-secondary border-0" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
            <h5 class="mb-0 fw-bold"><?= htmlspecialchars($title ?? 'Dashboard') ?></h5>
            <div class="d-flex align-items-center gap-3">
                <a href="<?= APP_URL ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye me-1"></i>Lihat Toko
                </a>
            </div>
        </div>

        <!-- Flash -->
        <?php foreach (['success', 'danger', 'warning', 'info'] as $type): ?>
            <?php $msg = (new \Core\Response())->getFlash($type); ?>
            <?php if ($msg): ?>
                <div class="alert alert-<?= $type ?> alert-dismissible fade show mx-4 mt-3 rounded-3" role="alert">
                    <?= htmlspecialchars($msg) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Page content -->
        <div class="ka-admin-content p-4">
            <?= $content ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="<?= APP_URL ?>/public/js/app.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('adminMain').classList.toggle('expanded');
        });
    </script>
</body>

</html>