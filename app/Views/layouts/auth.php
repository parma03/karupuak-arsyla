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

<body class="ka-auth-body">
    <div class="ka-auth-wrapper">
        <!-- Decorative side -->
        <div class="ka-auth-side d-none d-lg-flex">
            <div class="ka-auth-side-content">
                <div class="ka-auth-logo mb-4">🥨</div>
                <h2 class="ka-auth-tagline">Karupuak Kulit<br><span>Arsyla</span></h2>
                <p class="ka-auth-desc">Kerupuk Kulit sapi premium khas Minangkabau. Gurih, renyah, dan selalu bikin nagih.</p>
                <div class="ka-auth-badges mt-4 d-flex gap-3">
                    <div class="ka-auth-badge"><i class="bi bi-shield-check"></i> Terjamin</div>
                    <div class="ka-auth-badge"><i class="bi bi-truck"></i> Gratis Ongkir</div>
                    <div class="ka-auth-badge"><i class="bi bi-star-fill"></i> 4.9/5 Rating</div>
                </div>
            </div>
        </div>
        <!-- Form side -->
        <div class="ka-auth-main d-flex align-items-center justify-content-center">
            <div class="ka-auth-card w-100">
                <a href="<?= APP_URL ?>" class="ka-auth-back mb-4 d-inline-flex align-items-center gap-2">
                    <i class="bi bi-arrow-left"></i> Kembali ke toko
                </a>
                <?= $content ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="<?= APP_URL ?>/public/css/custom.css" rel="stylesheet">
    <script src="<?= APP_URL ?>/public/js/app.js"></script>

</body>

</html>