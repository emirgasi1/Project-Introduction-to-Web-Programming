<?php
class UserDao {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['username'],
            $data['email'],
            $data['password_hash'],
            $data['role']
        ]);
        return $this->db->lastInsertId();
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ?, password_hash = ?, role = ? WHERE user_id = ?");
        return $stmt->execute([
            $data['username'],
            $data['email'],
            $data['password_hash'],
            $data['role'],
            $id
        ]);
    }
    

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = ?");
        return $stmt->execute([$id]);
    }
}
