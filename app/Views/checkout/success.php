<?php /* app/Views/checkout/success.php */ ?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6 text-center">
      <div class="ka-card p-5">
        <div class="ka-success-icon mb-4">🎉</div>
        <h2 class="fw-bold mb-2">Pesanan Berhasil!</h2>
        <p class="text-muted mb-4">
          Terima kasih telah berbelanja di Karupuak Jangek Arsyla.<br>
          Kami akan segera memproses pesanan Anda.
        </p>

        <?php if ($orderId): ?>
          <div class="ka-success-order-box mb-4 p-3 rounded-3 bg-light">
            <div class="text-muted small mb-1">Nomor Pesanan</div>
            <div class="fw-bold fs-5 ka-text-primary">
              <?php
              $pdo  = getDB();
              $stmt = $pdo->prepare('SELECT order_code FROM orders WHERE id = ?');
              $stmt->execute([$orderId]);
              $row  = $stmt->fetch();
              echo htmlspecialchars($row['order_code'] ?? '#' . $orderId);
              ?>
            </div>
          </div>
        <?php endif; ?>

        <div class="d-flex gap-3 justify-content-center flex-wrap">
          <a href="<?= APP_URL ?>/orders/<?= $orderId ?>" class="btn ka-btn-primary">
            <i class="bi bi-bag-check me-2"></i>Detail Pesanan
          </a>
          <a href="<?= APP_URL ?>" class="btn btn-outline-secondary">
            <i class="bi bi-shop me-2"></i>Lanjut Belanja
          </a>
        </div>

        <div class="mt-4 pt-4 border-top">
          <p class="text-muted small mb-2">Status pesanan dapat dipantau di:</p>
          <a href="<?= APP_URL ?>/orders" class="ka-link small">
            <i class="bi bi-box-seam me-1"></i>Pesanan Saya
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .ka-success-icon {
    font-size: 5rem;
    animation: bounceIn .6s ease;
  }

  .ka-success-order-box {
    border: 1.5px dashed var(--ka-primary);
  }

  @keyframes bounceIn {
    0% {
      transform: scale(0.5);
      opacity: 0;
    }

    70% {
      transform: scale(1.1);
    }

    100% {
      transform: scale(1);
      opacity: 1;
    }
  }
</style>