<?php
class OrderItemDao {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT oi.*, p.product_name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.product_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT oi.*, p.product_name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.product_id WHERE order_item_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['order_id'],   // Order ID
            $data['product_id'], // Product ID
            $data['quantity'],   // Quantity
            $data['price']       // Price
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE order_items SET order_id = ?, product_id = ?, quantity = ?, price = ? WHERE order_item_id = ?");
        return $stmt->execute([
            $data['order_id'],
            $data['product_id'],
            $data['quantity'],
            $data['price'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM order_items WHERE order_item_id = ?");
        return $stmt->execute([$id]);
    }
}

?>
