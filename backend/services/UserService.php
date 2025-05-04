<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';

class UserService extends BaseService {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db  = $db;                  // sačuvaj PDO ako ti treba za validaciju
        $dao       = new UserDao($db);     // kreiraj DAO
        parent::__construct($dao);         // proslijedi DAO BaseService-u
    }

    public function create(array $data): int {
        $this->validateUser($data);

        // hashiraj lozinku prije spremanja
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);

        return parent::create($data);
    }

    public function update(int $id, array $data): bool {
        // prvo provjeri da li postoji
        if (!$this->getById($id)) {
            Flight::halt(404, "User not found.");
        }

        $this->validateUser($data);

        if (isset($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        }

        return parent::update($id, $data);
    }

    private function validateUser(array $data): void {
        if (!isset($data['username']) || strlen($data['username']) < 3) {
            Flight::halt(400, "Username must be at least 3 characters long.");
        }
        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            Flight::halt(400, "Invalid email format.");
        }
        if (isset($data['password']) && strlen($data['password']) < 6) {
            Flight::halt(400, "Password must be at least 6 characters.");
        }
        $validRoles = ['user','admin'];
        if (!isset($data['role']) || !in_array($data['role'], $validRoles)) {
            Flight::halt(400, "Role must be 'user' or 'admin'.");
        }
    }
}
