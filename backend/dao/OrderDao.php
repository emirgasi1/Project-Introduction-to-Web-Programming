<?php
// backend/dao/OrderDao.php
class OrderDao {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM orders");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE order_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO orders (user_id, total_price, status, order_date) VALUES (?, ?, ?, NOW())");
        $stmt->execute([
            $data['user_id'],
            $data['total_price'],
            $data['status']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE orders SET user_id = ?, total_price = ?, status = ? WHERE order_id = ?");
        return $stmt->execute([
            $data['user_id'],
            $data['total_price'],
            $data['status'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM orders WHERE order_id = ?");
        return $stmt->execute([$id]);
    }
}
?>
