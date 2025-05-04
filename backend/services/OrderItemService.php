<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/OrderItemDao.php';
require_once __DIR__ . '/OrderService.php';
require_once __DIR__ . '/ProductService.php';

class OrderItemService extends BaseService {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db  = $db;
        $dao       = new OrderItemDao($db);
        parent::__construct($dao);
    }

    public function create(array $data): int {
        // 1) Provjera postoji li narudžba
        $orderService = new OrderService($this->db);
        if (!$orderService->getById((int)$data['order_id'])) {
            Flight::halt(400, "Order not found.");
        }
        // 2) Provjera postoji li proizvod
        $productService = new ProductService($this->db);
        if (!$productService->getById((int)$data['product_id'])) {
            Flight::halt(400, "Product not found.");
        }
        // 3) Kreiraj zapis
        return parent::create($data);
    }

    public function update(int $id, array $data): bool {
        // 1) Provjeri da li item postoji
        if (!$this->getById($id)) {
            Flight::halt(404, "Order item not found.");
        }
        // 2) Provjera narudžbe i proizvoda
        $orderService = new OrderService($this->db);
        if (!$orderService->getById((int)$data['order_id'])) {
            Flight::halt(400, "Order not found.");
        }
        $productService = new ProductService($this->db);
        if (!$productService->getById((int)$data['product_id'])) {
            Flight::halt(400, "Product not found.");
        }
        // 3) Ažuriraj zapis
        return parent::update($id, $data);
    }
}
