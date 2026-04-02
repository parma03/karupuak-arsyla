<?php
// src/Infrastructure/Persistence/MySQLUserRepository.php
namespace Infrastructure\Persistence;

use Domain\User\Entities\User;
use Domain\User\Repositories\UserRepositoryInterface;

class MySQLUserRepository implements UserRepositoryInterface
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? User::fromArray($row) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ? User::fromArray($row) : null;
    }

    public function findAll(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $conditions = [];
        $params     = [];

        if (!empty($filters['role'])) {
            $conditions[] = 'role = :role';
            $params[':role'] = $filters['role'];
        }
        if (!empty($filters['search'])) {
            $conditions[] = '(name LIKE :s OR email LIKE :s2)';
            $params[':s']  = '%' . $filters['search'] . '%';
            $params[':s2'] = '%' . $filters['search'] . '%';
        }

        $where  = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $offset = ($page - 1) * $perPage;

        $stmt = $this->pdo->prepare(
            "SELECT * FROM users $where ORDER BY created_at DESC LIMIT :lim OFFSET :off"
        );
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':lim', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset,  \PDO::PARAM_INT);
        $stmt->execute();

        return array_map(fn($r) => User::fromArray($r), $stmt->fetchAll());
    }

    public function countAll(array $filters = []): int
    {
        $conditions = [];
        $params     = [];
        if (!empty($filters['role'])) {
            $conditions[] = 'role = :role';
            $params[':role'] = $filters['role'];
        }
        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $stmt  = $this->pdo->prepare("SELECT COUNT(*) FROM users $where");
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function save(User $user): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (name,email,password,phone,address,city_id,city_name,province,postal_code,role,is_active)
            VALUES (:name,:email,:password,:phone,:address,:city_id,:city_name,:province,:postal_code,:role,:is_active)
        ");
        $stmt->execute([
            ':name'        => $user->name,
            ':email'       => $user->email,
            ':password'    => $user->password,
            ':phone'       => $user->phone,
            ':address'     => $user->address,
            ':city_id'     => $user->cityId,
            ':city_name'   => $user->cityName,
            ':province'    => $user->province,
            ':postal_code' => $user->postalCode,
            ':role'        => $user->role,
            ':is_active'   => (int)$user->isActive,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(User $user): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE users SET
                name=:name, phone=:phone, address=:address, city_id=:city_id,
                city_name=:city_name, province=:province, postal_code=:postal_code,
                is_active=:is_active
            WHERE id=:id
        ");
        return $stmt->execute([
            ':name'        => $user->name,
            ':phone'       => $user->phone,
            ':address'     => $user->address,
            ':city_id'     => $user->cityId,
            ':city_name'   => $user->cityName,
            ':province'    => $user->province,
            ':postal_code' => $user->postalCode,
            ':is_active'   => (int)$user->isActive,
            ':id'          => $user->id,
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        if ($excludeId) {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE email=? AND id!=?');
            $stmt->execute([$email, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE email=?');
            $stmt->execute([$email]);
        }
        return (int)$stmt->fetchColumn() > 0;
    }
}
