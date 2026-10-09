<?php
// src/Infrastructure/Persistence/MySQLProductRepository.php
namespace Infrastructure\Persistence;

use Domain\Product\Entities\Product;
use Domain\Product\Repositories\ProductRepositoryInterface;

class MySQLProductRepository implements ProductRepositoryInterface
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    public function findAll(array $filters = [], int $page = 1, int $perPage = 12): array
    {
        [$where, $params] = $this->buildWhere($filters);
        $sortBy  = in_array($filters['sort_by']  ?? '', ['price', 'name', 'rating_avg', 'sold_count', 'created_at'])
            ? $filters['sort_by'] : 'created_at';
        $sortDir = strtoupper($filters['sort_dir'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
        $offset  = ($page - 1) * $perPage;
        $orderBy = $sortBy === 'rating_avg'
            ? 'CASE WHEN p.sold_count >= 500 THEN 5 WHEN p.sold_count >= 100 THEN 4 '
                . 'WHEN p.sold_count >= 50 THEN 3 WHEN p.sold_count >= 10 THEN 2 '
                . 'WHEN p.sold_count > 0 THEN 1 ELSE 0 END'
            : 'p.' . $sortBy;

        $sql = "
            SELECT p.*, c.name AS category_name
            FROM products p
            LEFT JOIN categories c ON c.id = p.category_id
            $where
            ORDER BY $orderBy $sortDir
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit',  $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  \PDO::PARAM_INT);
        $stmt->execute();

        return array_map(fn($r) => Product::fromArray($r), $stmt->fetchAll());
    }

    public function countAll(array $filters = []): int
    {
        [$where, $params] = $this->buildWhere($filters);
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products p $where");
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function findById(int $id): ?Product
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? Product::fromArray($row) : null;
    }

    public function findBySlug(string $slug): ?Product
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE slug = ? LIMIT 1');
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ? Product::fromArray($row) : null;
    }

    public function findFeatured(int $limit = 8): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM products WHERE is_featured=1 AND is_active=1 ORDER BY created_at DESC LIMIT ?'
        );
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => Product::fromArray($r), $stmt->fetchAll());
    }

    public function save(Product $product): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO products
                (category_id,name,slug,description,price,price_sale,stock,weight,image,is_active,is_featured)
            VALUES
                (:category_id,:name,:slug,:description,:price,:price_sale,:stock,:weight,:image,:is_active,:is_featured)
        ");
        $stmt->execute([
            ':category_id'  => $product->categoryId,
            ':name'         => $product->name,
            ':slug'         => $product->slug,
            ':description'  => $product->description,
            ':price'        => $product->price,
            ':price_sale'   => $product->priceSale,
            ':stock'        => $product->stock,
            ':weight'       => $product->weight,
            ':image'        => $product->image,
            ':is_active'    => (int)$product->isActive,
            ':is_featured'  => (int)$product->isFeatured,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(Product $product): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE products SET
                category_id=:category_id, name=:name, description=:description,
                price=:price, price_sale=:price_sale, stock=:stock, weight=:weight,
                image=:image, is_active=:is_active, is_featured=:is_featured
            WHERE id=:id
        ");
        return $stmt->execute([
            ':category_id' => $product->categoryId,
            ':name'        => $product->name,
            ':description' => $product->description,
            ':price'       => $product->price,
            ':price_sale'  => $product->priceSale,
            ':stock'       => $product->stock,
            ':weight'      => $product->weight,
            ':image'       => $product->image,
            ':is_active'   => (int)$product->isActive,
            ':is_featured' => (int)$product->isFeatured,
            ':id'          => $product->id,
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
    }

    public function updateRating(int $id, float $avg, int $count): bool
    {
        return $this->pdo->prepare(
            'UPDATE products SET rating_avg=?, rating_count=? WHERE id=?'
        )->execute([$avg, $count, $id]);
    }

    public function decrementStock(int $id, int $qty): bool
    {
        return $this->pdo->prepare(
            'UPDATE products SET stock = GREATEST(0, stock - ?) WHERE id=?'
        )->execute([$qty, $id]);
    }

    public function incrementSold(int $id, int $qty): bool
    {
        return $this->pdo->prepare(
            'UPDATE products SET sold_count = sold_count + ? WHERE id=?'
        )->execute([$qty, $id]);
    }

    // ── helpers ────────────────────────────────────────────────────
    private function buildWhere(array $filters): array
    {
        $conditions = [];
        $params     = [];

        if (isset($filters['is_active'])) {
            $conditions[] = 'p.is_active = :is_active';
            $params[':is_active'] = $filters['is_active'];
        }
        if (!empty($filters['category_id'])) {
            $conditions[] = 'p.category_id = :category_id';
            $params[':category_id'] = $filters['category_id'];
        }
        if (!empty($filters['is_featured'])) {
            $conditions[] = 'p.is_featured = 1';
        }
        if (!empty($filters['search'])) {
            $conditions[] = '(p.name LIKE :search OR p.description LIKE :search2)';
            $params[':search']  = '%' . $filters['search'] . '%';
            $params[':search2'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['exclude_id'])) {
            $conditions[] = 'p.id != :exclude_id';
            $params[':exclude_id'] = $filters['exclude_id'];
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        return [$where, $params];
    }
}
