<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/PaymentDao.php';
require_once __DIR__ . '/OrderService.php';

class PaymentService extends BaseService {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
        $dao = new PaymentDao($db);
        parent::__construct($dao);
    }

    public function create(array $data): int {
        $orderService = new OrderService($this->db);
        if (!$orderService->getById((int)$data['order_id'])) {
            Flight::halt(400, "Order not found.");
        }
        return $this->dao->create($data);
    }

    public function update(int $id, array $data): bool {
        if (!$this->getById($id)) {
            Flight::halt(404, "Payment not found.");
        }
        $orderService = new OrderService($this->db);
        if (!$orderService->getById((int)$data['order_id'])) {
            Flight::halt(400, "Order not found.");
        }
        return $this->dao->update($id, $data);
    }
    public function getByUserId(int $user_id) {
    $sql = "SELECT p.* FROM payments p
            JOIN orders o ON p.order_id = o.order_id
            WHERE o.user_id = :user_id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
