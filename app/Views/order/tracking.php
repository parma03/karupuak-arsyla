<?php /* app/Views/order/tracking.php */ ?>
<div class="container py-5">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?= APP_URL ?>/orders/<?= $order->id ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0"><i class="bi bi-geo-alt me-2"></i>Lacak Pesanan</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <!-- Order info -->
            <div class="ka-card mb-4">
                <div class="ka-card-header fw-semibold">Info Pengiriman</div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="text-muted small">No. Pesanan</div>
                            <div class="fw-bold"><?= htmlspecialchars($order->orderCode) ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Kurir</div>
                            <div class="fw-bold"><?= strtoupper($order->courier) ?> — <?= htmlspecialchars($order->courierService) ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">No. Resi</div>
                            <div class="fw-bold"><?= htmlspecialchars($shipment['tracking_number'] ?? 'Belum tersedia') ?></div>
                        </div>
                    </div>

                    <?php if ($shipment && $shipment['tracking_number']): ?>
                        <div class="mt-3">
                            <a href="https://cekresi.com/?noresi=<?= urlencode($shipment['tracking_number']) ?>"
                                target="_blank" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Cek Resi Eksternal
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Timeline -->
            <div class="ka-card">
                <div class="ka-card-header fw-semibold">Status Pengiriman</div>
                <div class="p-4">
                    <?php
                    $statuses = [
                        ['key' => 'pending',    'label' => 'Pesanan Dibuat',      'icon' => 'bi-bag-check',     'desc' => 'Pesanan Anda telah diterima'],
                        ['key' => 'processing', 'label' => 'Pesanan Diproses',    'icon' => 'bi-gear',          'desc' => 'Sedang dikemas oleh tim kami'],
                        ['key' => 'shipped',    'label' => 'Dalam Pengiriman',    'icon' => 'bi-truck',         'desc' => 'Paket dalam perjalanan ke alamat Anda'],
                        ['key' => 'delivered',  'label' => 'Pesanan Diterima',    'icon' => 'bi-house-check',   'desc' => 'Paket telah sampai di tujuan'],
                    ];
                    $orderStatuses = ['pending', 'processing', 'shipped', 'delivered'];
                    $currentIdx    = array_search($order->status, $orderStatuses);
                    if ($currentIdx === false) $currentIdx = -1;
                    ?>
                    <div class="ka-tracking-timeline">
                        <?php foreach ($statuses as $i => $step): ?>
                            <?php
                            $isDone   = $i <= $currentIdx;
                            $isActive = $i === $currentIdx;
                            ?>
                            <div class="ka-track-step <?= $isDone ? 'done' : '' ?> <?= $isActive ? 'active' : '' ?>">
                                <div class="ka-track-icon">
                                    <i class="bi <?= $step['icon'] ?>"></i>
                                </div>
                                <div class="ka-track-line <?= $i === count($statuses) - 1 ? 'd-none' : '' ?>"></div>
                                <div class="ka-track-body">
                                    <div class="fw-semibold"><?= $step['label'] ?></div>
                                    <div class="text-muted small"><?= $step['desc'] ?></div>
                                    <?php if ($isActive && $shipment): ?>
                                        <?php $ts = $order->status === 'delivered' ? ($shipment['delivered_at'] ?? null) : ($shipment['shipped_at'] ?? null); ?>
                                        <?php if ($ts): ?>
                                            <div class="text-muted small mt-1"><i class="bi bi-clock me-1"></i><?= date('d M Y, H:i', strtotime($ts)) ?></div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ka-tracking-timeline {
        display: flex;
        flex-direction: column;
    }

    .ka-track-step {
        display: flex;
        gap: 16px;
        position: relative;
        padding-bottom: 28px;
    }

    .ka-track-step:last-child {
        padding-bottom: 0;
    }

    .ka-track-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #e9ecef;
        color: #aaa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
        border: 2px solid #dee2e6;
    }

    .ka-track-step.done .ka-track-icon {
        background: var(--ka-primary-lt);
        color: var(--ka-primary);
        border-color: var(--ka-primary);
    }

    .ka-track-step.active .ka-track-icon {
        background: var(--ka-primary);
        color: #fff;
        border-color: var(--ka-primary);
        box-shadow: 0 0 0 4px rgba(180, 120, 60, .2);
    }

    .ka-track-line {
        position: absolute;
        left: 21px;
        top: 44px;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .ka-track-step.done .ka-track-line {
        background: var(--ka-primary);
    }

    .ka-track-body {
        padding-top: 8px;
        flex: 1;
    }

    .ka-track-step.active .ka-track-body .fw-semibold {
        color: var(--ka-primary);
    }
</style>