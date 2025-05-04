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

    // override create/update da ubaciš dodatne validacije…
    public function create(array $data): int {
        // validiraj category_id, name, price…
        return parent::create($data);
    }

    public function update(int $id, array $data): bool {
        // validiraj…
        return parent::update($id, $data);
    }
}
