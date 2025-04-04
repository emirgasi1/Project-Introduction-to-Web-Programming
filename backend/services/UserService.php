<?php
require_once __DIR__ . '/../dao/UserDao.php';

class UserService {
    private $dao;

    public function __construct($db) {
        $this->dao = new UserDao($db);
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function getById($id) {
        return $this->dao->getById($id);
    }

    public function create($data) {
        $this->validateUser($data);
        return $this->dao->create($data);
    }

    public function update($id, $data) {
        $this->validateUser($data);
        return $this->dao->update($id, $data);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }

    private function validateUser($data) {
        if (!isset($data['username']) || !is_string($data['username']) || strlen($data['username']) < 3) {
            throw new Exception("Username must be at least 3 characters long.");
        }
    
        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }
    
        if (!isset($data['password_hash']) || strlen($data['password_hash']) < 6) {
            throw new Exception("Password must be at least 6 characters.");
        }
    
        $validRoles = ['user', 'admin'];
        if (!isset($data['role']) || !in_array($data['role'], $validRoles)) {
            throw new Exception("Role must be either 'user' or 'admin'.");
        }
    }
    
}
?>
