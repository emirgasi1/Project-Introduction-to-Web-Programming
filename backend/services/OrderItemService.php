<?php
require_once __DIR__ . '/../dao/OrderItemDao.php';
class OrderItemService {
    private $dao;

    public function __construct($db) {
        $this->dao = new OrderItemDao($db);
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function getById($id) {
        return $this->dao->getById($id);
    }

    public function create($data) {
        // Provjeri da li narudžba postoji
        $orderService = new OrderService($GLOBALS['db']);
        $order = $orderService->getById($data['order_id']);
        if (!$order) {
            Flight::halt(400, "Order not found.");
        }

        // Provjeri da li proizvod postoji
        $productService = new ProductService($GLOBALS['db']);
        $product = $productService->getById($data['product_id']);
        if (!$product) {
            Flight::halt(400, "Product not found.");
        }

        return $this->dao->create($data);
    }

    public function update($id, $data) {
        // Provjeri da li narudžba i proizvod postoje
        $orderService = new OrderService($GLOBALS['db']);
        $order = $orderService->getById($data['order_id']);
        if (!$order) {
            Flight::halt(400, "Order not found.");
        }

        $productService = new ProductService($GLOBALS['db']);
        $product = $productService->getById($data['product_id']);
        if (!$product) {
            Flight::halt(400, "Product not found.");
        }

        return $this->dao->update($id, $data);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }
}

?>
