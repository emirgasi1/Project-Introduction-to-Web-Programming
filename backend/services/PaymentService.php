<?php
require_once __DIR__ . '/../dao/PaymentDao.php';

class PaymentService {
    private $dao;

    public function __construct($db) {
        $this->dao = new PaymentDao($db);
    }

    public function create($data) {
        // Provjeri da li narudžba postoji
        $orderService = new OrderService($GLOBALS['db']);
        $order = $orderService->getById($data['order_id']);
        if (!$order) {
            Flight::halt(400, "Order not found.");
        }

        // Kreiraj uplatu
        return $this->dao->create($data);
    }

    public function update($id, $data) {
        // Provjeri da li narudžba postoji
        $orderService = new OrderService($GLOBALS['db']);
        $order = $orderService->getById($data['order_id']);
        if (!$order) {
            Flight::halt(400, "Order not found.");
        }

        // Ažuriraj uplatu
        return $this->dao->update($id, $data);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function getById($id) {
        return $this->dao->getById($id);
    }
}