<?php
require_once __DIR__ . '/../dao/ProductDao.php';

class ProductService {
    private $dao;

    public function __construct($db) {
        $this->dao = new ProductDao($db); // Prosljeđivanje db u ProductDao
    }

    public function getAll() {
        // Pozivaj getAll metodu iz ProductDao koja već sadrži SQL upit
        return $this->dao->getAll();
    }
    
    public function getById($id) {
        return $this->dao->getById($id);
    }

    public function create($data) {
        // Provjeri da li kategorija postoji
        $categoryService = new CategoryService($GLOBALS['db']);
        $category = $categoryService->getById($data['category_id']);
        if (!$category) {
            Flight::halt(400, "Category not found.");
        }
    
        // Provjeri validnost drugih podataka (naziv, cijena, itd.)
        if (empty($data['product_name']) || strlen($data['product_name']) < 2) {
            Flight::halt(400, "Product name must be at least 2 characters.");
        }
    
        if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
            Flight::halt(400, "Price must be a positive number.");
        }
    
        return $this->dao->create($data);
    }

    public function update($id, $data) {
        // Provjeri da li kategorija postoji
        $categoryService = new CategoryService($GLOBALS['db']);
        $category = $categoryService->getById($data['category_id']);
        if (!$category) {
            Flight::halt(400, "Category not found.");
        }
    
        // Provjeri validnost podataka
        if (empty($data['product_name']) || strlen($data['product_name']) < 2) {
            Flight::halt(400, "Product name must be at least 2 characters.");
        }
    
        if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
            Flight::halt(400, "Price must be a positive number.");
        }
    
        return $this->dao->update($id, $data);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }
}

?>

