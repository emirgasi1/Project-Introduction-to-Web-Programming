<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/CategoryDao.php';

class CategoryService extends BaseService {
    public function __construct(PDO $db) {
        $dao = new CategoryDao($db);
        parent::__construct($dao);
    }

    public function create(array $data): int {
        if (empty($data['category_name'])) {
            throw new Exception("Category name is required.");
        }
        return parent::create($data);
    }

    public function update(int $id, array $data): bool {
        if (empty($data['category_name'])) {
            throw new Exception("Category name is required.");
        }
        return parent::update($id, $data);
    }
}
