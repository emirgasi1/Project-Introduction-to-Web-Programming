<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/OrderDao.php';
require_once __DIR__ . '/UserService.php';

class OrderService extends BaseService {
    private PDO $db;

    public function __construct(PDO $db) {
        // Spremi PDO za vlastite provjere
        $this->db = $db;
        // Kreiraj svoj DAO i proslijedi ga BaseService-u
        $dao = new OrderDao($db);
        parent::__construct($dao);
    }

    public function create(array $data): int {
        // 1) Provjeri da li user postoji
        $userService = new UserService($this->db);
        if (!$userService->getById((int)$data['user_id'])) {
            Flight::halt(400, "User not found.");
        }
        // 2) Validacija
        $this->validateOrder($data);
        // 3) Kreiraj preko BaseService
        return parent::create($data);
    }

    public function update(int $id, array $data): bool {
        // 1) Provjeri da li narudžba postoji
        if (!$this->getById($id)) {
            Flight::halt(404, "Order not found.");
        }
        // 2) Provjeri user
        $userService = new UserService($this->db);
        if (!$userService->getById((int)$data['user_id'])) {
            Flight::halt(400, "User not found.");
        }
        // 3) Validacija
        $this->validateOrder($data);
        // 4) Ažuriraj preko BaseService
        return parent::update($id, $data);
    }

    private function validateOrder(array $data): void {
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
