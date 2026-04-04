<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan — <?= $from ?> s/d <?= $to ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 24px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 4px;
        }

        .header p {
            color: #666;
            font-size: 11px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .summary-box {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
        }

        .summary-box .val {
            font-size: 16px;
            font-weight: bold;
            color: #b4783c;
        }

        .summary-box .lbl {
            font-size: 10px;
            color: #666;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead th {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }

        tbody td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 11px;
        }

        tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin: 16px 0 8px;
            border-left: 3px solid #b4783c;
            padding-left: 8px;
        }

        .footer {
            text-align: center;
            color: #999;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 12px;
            margin-top: 20px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="margin-bottom:16px">
        <button onclick="window.print()" style="padding:8px 20px;background:#b4783c;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px">
            🖨️ Cetak Laporan
        </button>
        <button onclick="window.close()" style="padding:8px 20px;background:#666;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px;margin-left:8px">
            Tutup
        </button>
    </div>

    <div class="header">
        <h1>LAPORAN PENJUALAN</h1>
        <p>Karupuak Jangek Arsyla — Padang, Sumatera Barat</p>
        <p>Periode: <?= date('d M Y', strtotime($from)) ?> s/d <?= date('d M Y', strtotime($to)) ?></p>
        <p>Dicetak: <?= date('d M Y H:i') ?></p>
    </div>

    <!-- Summary -->
    <?php $s = $report['summary']; ?>
    <div class="summary-grid">
        <div class="summary-box">
            <div class="val">Rp <?= number_format($s['total_revenue'] ?? 0, 0, ',', '.') ?></div>
            <div class="lbl">Total Pendapatan</div>
        </div>
        <div class="summary-box">
            <div class="val"><?= $s['total_orders'] ?? 0 ?></div>
            <div class="lbl">Total Pesanan</div>
        </div>
        <div class="summary-box">
            <div class="val"><?= $s['unique_customers'] ?? 0 ?></div>
            <div class="lbl">Pelanggan Unik</div>
        </div>
        <div class="summary-box">
            <div class="val"><?= $s['total_items_sold'] ?? 0 ?></div>
            <div class="lbl">Item Terjual</div>
        </div>
    </div>

    <!-- Trend -->
    <div class="section-title">Rincian Penjualan per Periode</div>
    <table>
        <thead>
            <tr>
                <th>Periode</th>
                <th>Jumlah Order</th>
                <th>Total Pendapatan</th>
                <th>Item Terjual</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($report['trend'] as $t): ?>
                <tr>
                    <td><?= $t['period'] ?></td>
                    <td><?= $t['orders'] ?></td>
                    <td>Rp <?= number_format($t['revenue'], 0, ',', '.') ?></td>
                    <td><?= $t['items_sold'] ?? '—' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Top Products -->
    <div class="section-title">Produk Terlaris</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Jumlah Terjual</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($report['top_products'] as $i => $p): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= $p['total_qty'] ?> pcs</td>
                    <td>Rp <?= number_format($p['total_revenue'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Top Categories -->
    <div class="section-title">Penjualan per Kategori</div>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Jumlah Terjual</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($report['top_categories'] as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['name']) ?></td>
                    <td><?= $c['total_qty'] ?> pcs</td>
                    <td>Rp <?= number_format($c['total_revenue'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh sistem Karupuak Jangek Arsyla &copy; <?= date('Y') ?>
    </div>

</body>

</html>