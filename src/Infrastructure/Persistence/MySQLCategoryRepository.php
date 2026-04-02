<?php
// src/Infrastructure/Persistence/MySQLCategoryRepository.php
namespace Infrastructure\Persistence;

use Domain\Category\Entities\Category;
use Domain\Category\Repositories\CategoryRepositoryInterface;

class MySQLCategoryRepository implements CategoryRepositoryInterface
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    public function findAll(bool $activeOnly = true): array
    {
        $where = $activeOnly ? 'WHERE c.is_active=1' : '';
        $stmt  = $this->pdo->query("
            SELECT c.*, COUNT(p.id) AS product_count
            FROM categories c
            LEFT JOIN products p ON p.category_id=c.id AND p.is_active=1
            $where
            GROUP BY c.id
            ORDER BY c.sort_order ASC, c.name ASC
        ");
        return array_map(fn($r) => Category::fromArray($r), $stmt->fetchAll());
    }

    public function findById(int $id): ?Category
    {
        $stmt = $this->pdo->prepare('SELECT * FROM categories WHERE id=? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? Category::fromArray($row) : null;
    }

    public function findBySlug(string $slug): ?Category
    {
        $stmt = $this->pdo->prepare('SELECT * FROM categories WHERE slug=? LIMIT 1');
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ? Category::fromArray($row) : null;
    }

    public function save(Category $cat): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO categories (name,slug,description,image,is_active,sort_order) VALUES (?,?,?,?,?,?)'
        );
        $stmt->execute([$cat->name, $cat->slug, $cat->description, $cat->image, (int)$cat->isActive, $cat->sortOrder]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(Category $cat): bool
    {
        return $this->pdo->prepare(
            'UPDATE categories SET name=?,description=?,is_active=?,sort_order=? WHERE id=?'
        )->execute([$cat->name, $cat->description, (int)$cat->isActive, $cat->sortOrder, $cat->id]);
    }

    public function delete(int $id): bool
    {
        return $this->pdo->prepare('DELETE FROM categories WHERE id=?')->execute([$id]);
    }

    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        if ($excludeId) {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM categories WHERE slug=? AND id!=?');
            $stmt->execute([$slug, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM categories WHERE slug=?');
            $stmt->execute([$slug]);
        }
        return (int)$stmt->fetchColumn() > 0;
    }
}
