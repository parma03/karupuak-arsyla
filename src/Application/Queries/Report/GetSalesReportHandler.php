<?php
// src/Application/Queries/Report/GetSalesReportHandler.php
namespace Application\Queries\Report;

use Application\Queries\QueryHandlerInterface;

class GetSalesReportHandler implements QueryHandlerInterface
{
    public function handle(object $query): array
    {
        /** @var GetSalesReportQuery $query */
        $pdo = getDB();

        // Format GROUP BY
        $dateFormat = $query->groupBy === 'monthly'
            ? "DATE_FORMAT(o.created_at, '%Y-%m')"
            : "DATE(o.created_at)";

        // Summary keseluruhan
        $summary = $pdo->prepare("
            SELECT
                COUNT(*)                            AS total_orders,
                SUM(o.total)                        AS total_revenue,
                SUM(o.subtotal)                     AS total_subtotal,
                SUM(o.shipping_cost)                AS total_shipping,
                AVG(o.total)                        AS avg_order_value,
                COUNT(DISTINCT o.user_id)           AS unique_customers,
                SUM(oi.qty)                         AS total_items_sold
            FROM orders o
            JOIN order_items oi ON oi.order_id = o.id
            WHERE o.created_at BETWEEN :from AND :to
              AND o.payment_status = 'paid'
        ");
        $summary->execute([':from' => $query->from . ' 00:00:00', ':to' => $query->to . ' 23:59:59']);
        $summaryData = $summary->fetch();

        // Trend per periode
        $trend = $pdo->prepare("
            SELECT
                $dateFormat                         AS period,
                COUNT(*)                            AS orders,
                SUM(o.total)                        AS revenue,
                SUM(oi.qty)                         AS items_sold
            FROM orders o
            JOIN order_items oi ON oi.order_id = o.id
            WHERE o.created_at BETWEEN :from AND :to
              AND o.payment_status = 'paid'
            GROUP BY period
            ORDER BY period ASC
        ");
        $trend->execute([':from' => $query->from . ' 00:00:00', ':to' => $query->to . ' 23:59:59']);
        $trendData = $trend->fetchAll();

        // Top produk terlaris
        $topProducts = $pdo->prepare("
            SELECT
                p.id, p.name, p.image,
                SUM(oi.qty)      AS total_qty,
                SUM(oi.subtotal) AS total_revenue
            FROM order_items oi
            JOIN orders o  ON o.id  = oi.order_id
            JOIN products p ON p.id = oi.product_id
            WHERE o.created_at BETWEEN :from AND :to
              AND o.payment_status = 'paid'
            GROUP BY p.id, p.name, p.image
            ORDER BY total_qty DESC
            LIMIT 10
        ");
        $topProducts->execute([':from' => $query->from . ' 00:00:00', ':to' => $query->to . ' 23:59:59']);
        $topProductsData = $topProducts->fetchAll();

        // Top kategori
        $topCategories = $pdo->prepare("
            SELECT
                c.name,
                SUM(oi.qty)      AS total_qty,
                SUM(oi.subtotal) AS total_revenue
            FROM order_items oi
            JOIN orders o    ON o.id  = oi.order_id
            JOIN products p  ON p.id  = oi.product_id
            JOIN categories c ON c.id = p.category_id
            WHERE o.created_at BETWEEN :from AND :to
              AND o.payment_status = 'paid'
            GROUP BY c.id, c.name
            ORDER BY total_revenue DESC
        ");
        $topCategories->execute([':from' => $query->from . ' 00:00:00', ':to' => $query->to . ' 23:59:59']);
        $topCategoriesData = $topCategories->fetchAll();

        return [
            'summary'        => $summaryData,
            'trend'          => $trendData,
            'top_products'   => $topProductsData,
            'top_categories' => $topCategoriesData,
            'from'           => $query->from,
            'to'             => $query->to,
        ];
    }
}
