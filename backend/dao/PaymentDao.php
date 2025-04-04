<?php
class PaymentDao {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM payments");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE payment_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO payments (order_id, payment_method, payment_status, payment_date) VALUES (?, ?, ?, NOW())");
        $stmt->execute([
            $data['order_id'],
            $data['payment_method'],
            $data['payment_status']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE payments SET order_id = ?, payment_method = ?, payment_status = ? WHERE payment_id = ?");
        return $stmt->execute([
            $data['order_id'],
            $data['payment_method'],
            $data['payment_status'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM payments WHERE payment_id = ?");
        return $stmt->execute([$id]);
    }
}
