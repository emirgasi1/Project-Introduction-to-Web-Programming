<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ProductDao.php';
require_once __DIR__ . '/../services/CategoryService.php';

class ProductService extends BaseService {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
        $dao = new ProductDao($db);
        parent::__construct($dao);
    }

    public function create(array $data): int {
        return parent::create($data);
    }

    public function update(int $id, array $data): bool {
        return parent::update($id, $data);
    }
    public function countProducts(): int {
    $stmt = $this->db->query("SELECT COUNT(*) as count FROM products");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)$row['count'];
}

}
