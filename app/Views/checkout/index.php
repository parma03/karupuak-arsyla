<?php /* app/Views/checkout/index.php */ ?>
<div class="container py-5">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?= APP_URL ?>/cart" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <h2 class="fw-bold mb-0"><i class="bi bi-bag-check me-2"></i>Checkout</h2>
    </div>

    <!-- Stepper -->
    <div class="ka-stepper mb-5 d-flex align-items-center gap-0">
        <div class="ka-step completed"><span>1</span> Keranjang</div>
        <div class="ka-step-line"></div>
        <div class="ka-step active"><span>2</span> Pengiriman</div>
        <div class="ka-step-line"></div>
        <div class="ka-step"><span>3</span> Pembayaran</div>
    </div>

    <form method="POST" action="<?= APP_URL ?>/checkout" id="checkoutForm">
        <div class="row g-4">
            <!-- Alamat & Pengiriman -->
            <div class="col-lg-7">
                <!-- Alamat -->
                <div class="ka-card mb-4">
                    <div class="ka-card-header"><i class="bi bi-geo-alt me-2"></i>Alamat Pengiriman</div>
                    <div class="p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Penerima</label>
                                <input type="text" name="name" class="form-control"
                                    value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">No. Telepon</label>
                                <input type="tel" name="phone" class="form-control"
                                    value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Alamat Lengkap</label>
                                <textarea name="address" class="form-control" rows="3" required
                                    placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan..."><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kota / Kabupaten</label>
                                <input type="text" name="city_name" class="form-control"
                                    value="<?= htmlspecialchars($user['city'] ?? '') ?>"
                                    placeholder="Contoh: Kota Padang" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Provinsi</label>
                                <input type="text" name="province" class="form-control"
                                    value="<?= htmlspecialchars($user['province'] ?? '') ?>"
                                    placeholder="Contoh: Sumatera Barat">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Kode Pos</label>
                                <input type="text" name="postal_code" class="form-control" placeholder="12345"
                                    value="<?= htmlspecialchars($user['postal_code'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pilih Kurir -->
                <div class="ka-card mb-4">
                    <div class="ka-card-header"><i class="bi bi-truck me-2"></i>Pilih Kurir & Layanan</div>
                    <div class="p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kurir</label>
                            <select id="courier_select" class="form-select" required>
                                <option value="">-- Pilih Kurir --</option>
                                <?php foreach ($couriers as $c): ?>
                                    <option value="<?= $c ?>"><?= strtoupper($c) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Pilihan layanan muncul setelah kurir dipilih -->
                        <div id="serviceResult" class="d-none">
                            <label class="form-label fw-semibold">Pilih Layanan</label>
                            <div id="serviceOptions" class="d-flex flex-column gap-2"></div>
                        </div>

                        <input type="hidden" name="courier" id="selected_courier">
                        <input type="hidden" name="courier_service" id="selected_service">
                        <input type="hidden" name="shipping_cost" id="selected_cost" value="0">

                        <div class="mt-3">
                            <small class="text-muted"><i class="bi bi-info-circle me-1"></i>
                                Tarif pengiriman bersifat simulasi untuk keperluan pengujian.</small>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="ka-card">
                    <div class="ka-card-header"><i class="bi bi-chat-left-text me-2"></i>Catatan (Opsional)</div>
                    <div class="p-4">
                        <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Tolong dibungkus rapi..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-5">
                <div class="ka-card sticky-top" style="top:90px">
                    <div class="ka-card-header fw-bold">Ringkasan Pesanan</div>
                    <div class="p-4">
                        <?php foreach ($cart->items as $item): ?>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="<?= $item->productImage ? UPLOAD_URL . $item->productImage : APP_URL . '/public/img/no-image.png' ?>"
                                        width="48" height="48" class="rounded-2 object-fit-cover">
                                    <div>
                                        <div class="small fw-semibold"><?= htmlspecialchars($item->productName ?? '') ?></div>
                                        <div class="text-muted small">x<?= $item->qty ?></div>
                                    </div>
                                </div>
                                <span class="fw-semibold small">Rp <?= number_format($item->subtotal(), 0, ',', '.') ?></span>
                            </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span>Rp <?= number_format($cart->subtotal(), 0, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Ongkos Kirim</span>
                            <span id="ongkir-display">—</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 ka-text-primary" id="total-display">
                                Rp <?= number_format($cart->subtotal(), 0, ',', '.') ?>
                            </span>
                        </div>
                        <button type="submit" class="btn ka-btn-primary w-100 btn-lg fw-semibold"
                            id="submitCheckout" disabled>
                            <i class="bi bi-lock me-2"></i>Lanjut ke Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const subtotal = <?= $cart->subtotal() ?>;

    // Data layanan kurir (simulasi — tidak memanggil API eksternal)
    const COURIER_SERVICES = {
        jne: [{
                service: 'OKE',
                description: 'Ongkos Kirim Ekonomis',
                cost: 10000,
                etd: '4-5'
            },
            {
                service: 'REG',
                description: 'Reguler',
                cost: 15000,
                etd: '2-3'
            },
            {
                service: 'YES',
                description: 'Yakin Esok Sampai',
                cost: 35000,
                etd: '1'
            },
        ],
        jnt: [{
                service: 'EZ',
                description: 'Reguler',
                cost: 12000,
                etd: '2-3'
            },
            {
                service: 'EXPRESS',
                description: 'Express',
                cost: 28000,
                etd: '1'
            },
        ],
        sicepat: [{
                service: 'HALU',
                description: 'Hemat',
                cost: 9000,
                etd: '4-5'
            },
            {
                service: 'BEST',
                description: 'Reguler',
                cost: 14000,
                etd: '2-3'
            },
            {
                service: 'GOKIL',
                description: 'Express',
                cost: 30000,
                etd: '1'
            },
        ],
    };

    document.getElementById('courier_select').addEventListener('change', function() {
        const courier = this.value;
        const services = COURIER_SERVICES[courier] ?? [];
        const wrap = document.getElementById('serviceOptions');
        const result = document.getElementById('serviceResult');

        // Reset pilihan sebelumnya
        document.getElementById('selected_courier').value = '';
        document.getElementById('selected_service').value = '';
        document.getElementById('selected_cost').value = '0';
        document.getElementById('ongkir-display').textContent = '—';
        document.getElementById('total-display').textContent =
            'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('submitCheckout').disabled = true;

        if (!courier || !services.length) {
            result.classList.add('d-none');
            return;
        }

        wrap.innerHTML = '';
        services.forEach(s => {
            wrap.innerHTML += `
            <label class="ka-ongkir-option" style="cursor:pointer">
                <input type="radio" name="ongkir_radio" value="${s.cost}"
                       onchange="selectService('${courier}', '${s.service}', ${s.cost})">
                <div class="ka-ongkir-detail">
                    <span class="fw-semibold">${courier.toUpperCase()} ${s.service}</span>
                    <span class="text-muted small">${s.description} &middot; Estimasi ${s.etd} hari</span>
                </div>
                <span class="fw-bold ka-text-primary">Rp ${s.cost.toLocaleString('id-ID')}</span>
            </label>`;
        });
        result.classList.remove('d-none');
    });

    function selectService(courier, service, cost) {
        document.getElementById('selected_courier').value = courier;
        document.getElementById('selected_service').value = service;
        document.getElementById('selected_cost').value = cost;
        document.getElementById('ongkir-display').textContent =
            'Rp ' + cost.toLocaleString('id-ID');
        document.getElementById('total-display').textContent =
            'Rp ' + (subtotal + cost).toLocaleString('id-ID');
        document.getElementById('submitCheckout').disabled = false;
    }
</script>