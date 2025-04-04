<?php
class ProductDao {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ OVO PREIMENUJ U create
    public function create($product) {
        $stmt = $this->db->prepare("INSERT INTO products (product_name, description, price, category_id, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $product['product_name'],
            $product['description'],
            $product['price'],
            $product['category_id'],
            $product['image_url']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $product) {
        $stmt = $this->db->prepare("UPDATE products SET product_name=?, description=?, price=?, category_id=?, image_url=? WHERE product_id=?");
        $stmt->execute([
            $product['product_name'],
            $product['description'],
            $product['price'],
            $product['category_id'],
            $product['image_url'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
