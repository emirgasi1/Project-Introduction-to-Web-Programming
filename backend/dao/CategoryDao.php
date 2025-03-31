<?php
// backend/dao/CategoryDao.php
class CategoryDao {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE category_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO categories (category_name) VALUES (?)");
        $stmt->execute([$data['category_name']]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE categories SET category_name = ? WHERE category_id = ?");
        return $stmt->execute([$data['category_name'], $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE category_id = ?");
        return $stmt->execute([$id]);
    }
}
?>
