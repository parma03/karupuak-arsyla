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
                                <label class="form-label fw-semibold">Provinsi</label>
                                <select name="province_id" id="province_select" class="form-select" required>
                                    <option value="">-- Pilih Provinsi --</option>
                                </select>
                                <input type="hidden" name="province" id="province_name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kota / Kabupaten</label>
                                <select name="city_id" id="city_select" class="form-select" required disabled>
                                    <option value="">-- Pilih Kota --</option>
                                </select>
                                <input type="hidden" name="city_name" id="city_name">
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
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kurir</label>
                                <select name="courier_code" id="courier_select" class="form-select" required>
                                    <option value="">-- Pilih Kurir --</option>
                                    <?php foreach ($couriers as $c): ?>
                                        <option value="<?= $c ?>"><?= strtoupper($c) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="button" id="cekOngkirBtn" class="btn ka-btn-primary w-100" disabled>
                                    <i class="bi bi-calculator me-2"></i>Cek Ongkir
                                </button>
                            </div>
                        </div>

                        <!-- Hasil ongkir -->
                        <div id="ongkirResult" class="d-none">
                            <label class="form-label fw-semibold">Pilih Layanan</label>
                            <div id="ongkirOptions" class="d-flex flex-column gap-2"></div>
                        </div>
                        <input type="hidden" name="courier" id="selected_courier">
                        <input type="hidden" name="courier_service" id="selected_service">
                        <input type="hidden" name="shipping_cost" id="selected_cost" value="0">
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
                        <button type="submit" class="btn ka-btn-primary w-100 btn-lg fw-semibold" id="submitCheckout" disabled>
                            <i class="bi bi-lock me-2"></i>Lanjut ke Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const APP_URL = '<?= APP_URL ?>';
    const subtotal = <?= $cart->subtotal() ?>;
    const cartWeight = <?= array_sum(array_map(fn($i) => ($i->productWeight ?? 100) * $i->qty, $cart->items)) ?>;

    // Load provinsi
    fetch(APP_URL + '/public/js/provinces.json')
        .then(r => r.ok ? r.json() : null)
        .catch(() => null)
        .then(data => {
            // Fallback: load dari RajaOngkir via AJAX jika ada
        });

    document.getElementById('province_select').addEventListener('change', function() {
        const provId = this.value;
        const provName = this.options[this.selectedIndex].text;
        document.getElementById('province_name').value = provName;
        const citySelect = document.getElementById('city_select');
        citySelect.innerHTML = '<option>Memuat kota...</option>';
        citySelect.disabled = true;

        fetch(APP_URL + '/public/js/cities.json')
            .then(r => r.json())
            .then(cities => {
                const filtered = cities.filter(c => c.province_id == provId);
                citySelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
                filtered.forEach(c => {
                    citySelect.innerHTML += `<option value="${c.city_id}">${c.type} ${c.city_name}</option>`;
                });
                citySelect.disabled = false;
                checkCekBtn();
            });
    });

    document.getElementById('city_select').addEventListener('change', function() {
        document.getElementById('city_name').value = this.options[this.selectedIndex].text;
        checkCekBtn();
    });

    document.getElementById('courier_select').addEventListener('change', checkCekBtn);

    function checkCekBtn() {
        const city = document.getElementById('city_select').value;
        const courier = document.getElementById('courier_select').value;
        document.getElementById('cekOngkirBtn').disabled = !(city && courier);
    }

    document.getElementById('cekOngkirBtn').addEventListener('click', function() {
        const cityId = document.getElementById('city_select').value;
        const courier = document.getElementById('courier_select').value;
        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menghitung...';
        this.disabled = true;

        fetch(APP_URL + '/shipping/check', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `city_id=${cityId}&weight=${cartWeight}&courier=${courier}`
            })
            .then(r => r.json())
            .then(res => {
                this.innerHTML = '<i class="bi bi-calculator me-2"></i>Cek Ongkir';
                this.disabled = false;
                if (res.success && res.data.length) {
                    const wrap = document.getElementById('ongkirOptions');
                    wrap.innerHTML = '';
                    res.data.forEach(s => {
                        wrap.innerHTML += `
          <label class="ka-ongkir-option">
            <input type="radio" name="ongkir_radio" value="${s.cost}"
                   data-service="${s.service}" onchange="selectOngkir(this, '${courier}', '${s.service}', ${s.cost})">
            <div class="ka-ongkir-detail">
              <span class="fw-semibold">${s.service}</span>
              <span class="text-muted small">${s.description} · Estimasi ${s.etd} hari</span>
            </div>
            <span class="fw-bold ka-text-primary">Rp ${s.cost.toLocaleString('id-ID')}</span>
          </label>`;
                    });
                    document.getElementById('ongkirResult').classList.remove('d-none');
                } else {
                    alert('Gagal mendapatkan ongkir. Periksa kota tujuan.');
                }
            });
    });

    function selectOngkir(el, courier, service, cost) {
        document.getElementById('selected_courier').value = courier;
        document.getElementById('selected_service').value = service;
        document.getElementById('selected_cost').value = cost;
        document.getElementById('ongkir-display').textContent = 'Rp ' + cost.toLocaleString('id-ID');
        document.getElementById('total-display').textContent = 'Rp ' + (subtotal + cost).toLocaleString('id-ID');
        document.getElementById('submitCheckout').disabled = false;
    }
</script>