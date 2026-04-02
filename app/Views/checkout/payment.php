<?php /* app/Views/checkout/payment.php */ ?>
<div class="container py-5">
    <div class="ka-stepper mb-5 d-flex align-items-center">
        <div class="ka-step completed"><span>1</span> Keranjang</div>
        <div class="ka-step-line"></div>
        <div class="ka-step completed"><span>2</span> Pengiriman</div>
        <div class="ka-step-line"></div>
        <div class="ka-step active"><span>3</span> Pembayaran</div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="ka-card text-center p-5">
                <div class="ka-payment-icon mb-3">💳</div>
                <h4 class="fw-bold">Selesaikan Pembayaran</h4>
                <p class="text-muted">Order <strong>#<?= htmlspecialchars($order->orderCode) ?></strong></p>

                <div class="ka-payment-summary mb-4 p-3 rounded-3 bg-light">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Belanja</span>
                        <span>Rp <?= number_format($order->subtotal, 0, ',', '.') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Ongkos Kirim</span>
                        <span>Rp <?= number_format($order->shippingCost, 0, ',', '.') ?></span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total Pembayaran</span>
                        <span class="ka-text-primary fs-5">Rp <?= number_format($order->total, 0, ',', '.') ?></span>
                    </div>
                </div>

                <button id="pay-button" class="btn ka-btn-primary btn-lg w-100 fw-semibold py-3">
                    <i class="bi bi-lock me-2"></i>Bayar Sekarang
                </button>
                <p class="text-muted small mt-3">
                    <i class="bi bi-shield-lock me-1"></i>
                    Pembayaran aman diproses oleh Midtrans
                </p>

                <!-- Payment icons -->
                <div class="d-flex justify-content-center gap-2 flex-wrap mt-2">
                    <?php foreach (['Transfer Bank', 'QRIS', 'GoPay', 'OVO', 'DANA', 'ShopeePay'] as $pm): ?>
                        <span class="badge bg-light text-dark border"><?= $pm ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="<?= APP_URL ?>/orders/<?= $order->id ?>" class="text-muted small">
                    Bayar nanti · Lihat detail pesanan
                </a>
            </div>
        </div>
    </div>
</div>

<script src="<?= MIDTRANS_SNAP_URL ?>" data-client-key="<?= MIDTRANS_CLIENT_KEY ?>"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', function() {
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Membuka pembayaran...';
        snap.pay('<?= htmlspecialchars($snapToken) ?>', {
            onSuccess: function(result) {
                window.location.href = '<?= APP_URL ?>/payment/finish?order_id=<?= $order->id ?>&transaction_status=' + result.transaction_status;
            },
            onPending: function(result) {
                window.location.href = '<?= APP_URL ?>/orders/<?= $order->id ?>';
            },
            onError: function(result) {
                alert('Pembayaran gagal. Silakan coba lagi.');
                document.getElementById('pay-button').disabled = false;
                document.getElementById('pay-button').innerHTML = '<i class="bi bi-lock me-2"></i>Bayar Sekarang';
            },
            onClose: function() {
                document.getElementById('pay-button').disabled = false;
                document.getElementById('pay-button').innerHTML = '<i class="bi bi-lock me-2"></i>Bayar Sekarang';
            }
        });
    });
</script>