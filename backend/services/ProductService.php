<?php
require_once __DIR__ . '/../dao/ProductDao.php';

class ProductService {
    private $dao;

    public function __construct($db) {
        $this->dao = new ProductDao($db); // ispravljeno iz ProducttDao u ProductDao
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function getById($id) {
        return $this->dao->getById($id);
    }

    public function create($data) {
        // ✅ Validacija
        if (empty($data['product_name']) || strlen($data['product_name']) < 2) {
            Flight::halt(400, "Product name must be at least 2 characters.");
        }

        if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
            Flight::halt(400, "Price must be a positive number.");
        }

        if (empty($data['category_id'])) {
            Flight::halt(400, "Category ID is required.");
        }

        return $this->dao->create($data);
    }

    public function update($id, $data) {
        // ✅ Validacija
        if (empty($data['product_name']) || strlen($data['product_name']) < 2) {
            Flight::halt(400, "Product name must be at least 2 characters.");
        }

        if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
            Flight::halt(400, "Price must be a positive number.");
        }

        if (empty($data['category_id'])) {
            Flight::halt(400, "Category ID is required.");
        }

        return $this->dao->update($id, $data);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }
}
?>

