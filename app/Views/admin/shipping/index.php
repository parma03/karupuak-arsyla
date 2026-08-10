<?php /* app/Views/admin/shipping/index.php */ ?>
<div class="mb-4">
    <h4 class="fw-bold"><i class="bi bi-truck me-2"></i>Update Status Pengiriman</h4>
</div>

<div class="ka-card">
    <div class="table-responsive">
        <table class="table ka-table mb-0">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Penerima</th>
                    <th>Kota</th>
                    <th>Kurir</th>
                    <th>No. Resi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($shipments as $s): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($s['order_code']) ?></code></td>
                        <td><?= htmlspecialchars($s['name']) ?></td>
                        <td class="text-muted small"><?= htmlspecialchars($s['city_name']) ?></td>
                        <td><?= strtoupper($s['courier']) ?></td>
                        <td>
                            <?php if ($s['tracking_number']): ?>
                                <code><?= htmlspecialchars($s['tracking_number']) ?></code>
                            <?php else: ?>
                                <span class="text-muted small">Belum ada resi</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php $sc = match ($s['status']) {
                                'delivered'  => 'success',
                                'shipped'    => 'info',
                                'in_transit' => 'primary',
                                default      => 'warning'
                            }; ?>
                            <span class="badge bg-<?= $sc ?>"><?= ucfirst(str_replace('_', ' ', $s['status'])) ?></span>
                        </td>
                        <td>
                            <?php if ($s['status'] === 'delivered'): ?>
                                <span class="text-muted small">Sudah diterima</span>
                            <?php else: ?>
                                <button class="btn btn-sm btn-outline-primary"
                                    onclick="openShipModal(<?= $s['order_id'] ?>, '<?= addslashes($s['tracking_number'] ?? '') ?>')">
                                    <i class="bi bi-pencil"></i> Update
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Update Pengiriman -->
<div class="modal fade" id="shipModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Update Pengiriman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="shipForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Pesanan</label>
                        <select name="status" class="form-select" required>
                            <option value="processing">Diproses</option>
                            <option value="shipped">Dikirim</option>
                            <option value="delivered">Diterima</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. Resi</label>
                        <input type="text" name="tracking_number" id="shipResi" class="form-control"
                            placeholder="Contoh: JNE0123456789">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn ka-btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openShipModal(orderId, resi) {
        document.getElementById('shipForm').action = '<?= APP_URL ?>/admin/shipping/' + orderId + '/update';
        document.getElementById('shipResi').value = resi;
        new bootstrap.Modal(document.getElementById('shipModal')).show();
    }
</script>