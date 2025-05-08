<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/PaymentDao.php';
require_once __DIR__ . '/OrderService.php';

class PaymentService extends BaseService {
    private PDO $db;

    public function __construct(PDO $db) {
        // Sačuvaj PDO za validaciju
        $this->db = $db;
        // Napravi DAO i proslijedi ga BaseService-u
        $dao = new PaymentDao($db);
        parent::__construct($dao);
    }

    public function create(array $data): int {
        // 1) Provjeri da li narudžba postoji
        $orderService = new OrderService($this->db);
        if (!$orderService->getById((int)$data['order_id'])) {
            Flight::halt(400, "Order not found.");
        }
        // 2) Kreiraj uplatu preko DAO-a
        return $this->dao->create($data);
    }

    public function update(int $id, array $data): bool {
        // 1) Provjeri da li uplata uopće postoji
        if (!$this->getById($id)) {
            Flight::halt(404, "Payment not found.");
        }
        // 2) Provjeri da li narudžba postoji
        $orderService = new OrderService($this->db);
        if (!$orderService->getById((int)$data['order_id'])) {
            Flight::halt(400, "Order not found.");
        }
        // 3) Ažuriraj uplatu
        return $this->dao->update($id, $data);
    }
}
