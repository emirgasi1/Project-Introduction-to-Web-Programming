<?php

require_once __DIR__ . '/../dao/CategoryDao.php';

class CategoryService {
    private $dao;

    public function __construct($db) {
        $this->dao = new CategoryDao($db);
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function getById($id) {
        return $this->dao->getById($id);
    }

    public function create($data) {
        // Validacija imena kategorije
        if (empty($data['category_name'])) {
            throw new Exception("Category name is required.");
        }

        return $this->dao->create($data);
    }

    public function update($id, $data) {
        // Validacija imena kategorije
        if (empty($data['category_name'])) {
            throw new Exception("Category name is required.");
        }

        return $this->dao->update($id, $data);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }
}
