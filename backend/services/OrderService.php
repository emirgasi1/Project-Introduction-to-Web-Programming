<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/OrderDao.php';
require_once __DIR__ . '/UserService.php';

class OrderService extends BaseService {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
        $dao = new OrderDao($db);
        parent::__construct($dao);
    }

    public function create(array $data): int {
        $this->validateOrder($data);

        $userService = new UserService($this->db);
        if (!$userService->getById((int)$data['user_id'])) {
            Flight::halt(400, "User not found.");
        }

        // Ako nema order_date, postavi danasnji
        if (empty($data['order_date'])) {
            $data['order_date'] = date('Y-m-d');
        } else if (!$this->validateDate($data['order_date'])) {
            Flight::halt(400, "Invalid order_date format. Use YYYY-MM-DD.");
        }

        return parent::create($data);
    }

    public function update(int $id, array $data): bool {
        $order = $this->getById($id);
        if (!$order) {
            Flight::halt(404, "Order not found.");
        }

        // Ako je poslat user_id, provjeri ga
        if (isset($data['user_id'])) {
            $userService = new UserService($this->db);
            if (!$userService->getById((int)$data['user_id'])) {
                Flight::halt(400, "User not found.");
            }
        }

        // Ako je poslat datum, validiraj ga
        if (isset($data['order_date']) && !$this->validateDate($data['order_date'])) {
            Flight::halt(400, "Invalid order_date format. Use YYYY-MM-DD.");
        }

        $this->validateOrder($data, true); // za update partial validation
        return parent::update($id, $data);
    }

    private function validateOrder(array $data, bool $isUpdate = false): void {
        // Kod kreiranja svi fieldovi moraju postojati, kod update-a samo oni koji se šalju
        if (!$isUpdate || isset($data['user_id'])) {
            if (!isset($data['user_id']) || !is_numeric($data['user_id'])) {
                Flight::halt(400, "Invalid or missing user_id.");
            }
        }
        if (!$isUpdate || isset($data['total_price'])) {
            if (!isset($data['total_price']) || !is_numeric($data['total_price']) || $data['total_price'] < 0) {
                Flight::halt(400, "Total price must be a positive number.");
            }
        }
        if (!$isUpdate || isset($data['status'])) {
            $validStatuses = ['pending', 'completed', 'cancelled'];
            if (!isset($data['status']) || !in_array($data['status'], $validStatuses)) {
                Flight::halt(400, "Status must be one of: pending, completed, or cancelled.");
            }
        }
    }

    // Validacija datuma (YYYY-MM-DD)
    private function validateDate($date): bool {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
    public function getByUserId(int $user_id): array {
    $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result ? $result : [];
}

}
