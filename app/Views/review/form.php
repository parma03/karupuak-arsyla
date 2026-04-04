<?php /* app/Views/review/form.php */ ?>
<div class="container py-5">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?= APP_URL ?>/orders/<?= $order->id ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0"><i class="bi bi-star me-2"></i>Berikan Ulasan</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="ka-card mb-3 p-3">
                <div class="d-flex align-items-center gap-2 small text-muted">
                    <i class="bi bi-bag-check"></i>
                    Pesanan <strong class="text-dark">#<?= htmlspecialchars($order->orderCode) ?></strong>
                    · <?= date('d M Y', strtotime($order->createdAt)) ?>
                </div>
            </div>

            <form method="POST" action="<?= APP_URL ?>/review/<?= $order->id ?>">
                <?php foreach ($items as $idx => $item): ?>
                    <div class="ka-card mb-4">
                        <div class="p-4">
                            <!-- Product info -->
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <img src="<?= $item->productImage ? UPLOAD_URL . $item->productImage : APP_URL . '/public/img/no-image.png' ?>"
                                    width="72" height="72" class="rounded-3 object-fit-cover border">
                                <div>
                                    <div class="fw-bold"><?= htmlspecialchars($item->productName) ?></div>
                                    <div class="text-muted small"><?= $item->qty ?> pcs · Rp <?= number_format($item->price, 0, ',', '.') ?>/pcs</div>
                                </div>
                            </div>

                            <input type="hidden" name="product_id[]" value="<?= $item->productId ?>">

                            <!-- Star rating -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Rating <span class="text-danger">*</span></label>
                                <div class="ka-star-picker" id="stars-<?= $idx ?>">
                                    <?php for ($s = 5; $s >= 1; $s--): ?>
                                        <input type="radio" name="rating[]" id="star-<?= $idx ?>-<?= $s ?>"
                                            value="<?= $s ?>" <?= $s === 5 ? 'checked' : '' ?>>
                                        <label for="star-<?= $idx ?>-<?= $s ?>" title="<?= $s ?> bintang">
                                            <i class="bi bi-star-fill"></i>
                                        </label>
                                    <?php endfor; ?>
                                </div>
                                <div class="ka-rating-label mt-1 text-muted small" id="ratingLabel-<?= $idx ?>">Sempurna!</div>
                            </div>

                            <!-- Komentar -->
                            <div>
                                <label class="form-label fw-semibold">Komentar (Opsional)</label>
                                <textarea name="comment[]" class="form-control" rows="3"
                                    placeholder="Ceritakan pengalaman Anda dengan produk ini..."></textarea>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn ka-btn-primary btn-lg px-5 fw-semibold">
                        <i class="bi bi-send me-2"></i>Kirim Ulasan
                    </button>
                    <a href="<?= APP_URL ?>/orders/<?= $order->id ?>" class="btn btn-outline-secondary btn-lg">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Star picker RTL trick */
    .ka-star-picker {
        display: flex;
        flex-direction: row-reverse;
        gap: 4px;
        width: fit-content;
    }

    .ka-star-picker input {
        display: none;
    }

    .ka-star-picker label {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
        transition: color .15s;
    }

    .ka-star-picker input:checked~label,
    .ka-star-picker label:hover,
    .ka-star-picker label:hover~label {
        color: #f59e0b;
    }
</style>

<script>
    const ratingLabels = ['', 'Sangat Buruk', 'Buruk', 'Cukup', 'Bagus', 'Sempurna!'];
    document.querySelectorAll('.ka-star-picker').forEach((picker, idx) => {
        picker.querySelectorAll('input').forEach(radio => {
            radio.addEventListener('change', function() {
                const label = document.getElementById('ratingLabel-' + idx);
                if (label) label.textContent = ratingLabels[this.value] || '';
            });
        });
    });
</script>