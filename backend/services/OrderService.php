<?php
require_once __DIR__ . '/../dao/OrderDao.php';

class OrderService {
    private $dao;

    public function __construct($db) {
        $this->dao = new OrderDao($db);
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function getById($id) {
        return $this->dao->getById($id);
    }

    public function create($data) {
        $this->validateOrder($data);
        return $this->dao->create($data);
    }

    public function update($id, $data) {
        $this->validateOrder($data);
        return $this->dao->update($id, $data);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }

    private function validateOrder($data) {
        if (!isset($data['user_id']) || !is_numeric($data['user_id'])) {
            throw new Exception("Invalid or missing user ID.");
        }

        if (!isset($data['total_price']) || !is_numeric($data['total_price']) || $data['total_price'] < 0) {
            throw new Exception("Total price must be a positive number.");
        }

        $validStatuses = ['pending', 'completed', 'cancelled'];
        if (!isset($data['status']) || !in_array($data['status'], $validStatuses)) {
            throw new Exception("Status must be one of: pending, completed, or cancelled.");
        }
    }
}
?>
