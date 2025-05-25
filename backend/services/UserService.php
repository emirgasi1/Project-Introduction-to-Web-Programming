<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';

class UserService extends BaseService {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db  = $db;                 
        $dao       = new UserDao($db);     
        parent::__construct($dao);       
    }

    public function create(array $data): int {
        if (empty($data['role'])) {
    $data['role'] = 'user';
}

        $this->validateUser($data);

        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);

        return parent::create($data);
    }

    public function update(int $id, array $data): bool {
    $currentUser = $this->getById($id);
    if (!$currentUser) {
        Flight::halt(404, "User not found.");
    }
    if (empty($data['role'])) {
        $data['role'] = 'user';
    }

    $this->validateUser(array_merge($currentUser, $data));

    // Ako je password poslan, hashiraj ga, ako nije, koristi postojeći hash
    if (isset($data['password']) && strlen($data['password']) > 0) {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);
    } else {
        // Postavi stari hash iz baze!
        $data['password_hash'] = $currentUser['password_hash'];
    }

    // Ovo osigurava da uvijek šalješ sve potrebne kolone (username, email, role, password_hash)
    $updateData = [
        'username'      => $data['username']      ?? $currentUser['username'],
        'email'         => $data['email']         ?? $currentUser['email'],
        'role'          => $data['role']          ?? $currentUser['role'],
        'password_hash' => $data['password_hash']
    ];

    return parent::update($id, $updateData);
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
        $validRoles = ['customer', 'admin'];
        if (!isset($data['role']) || !in_array($data['role'], $validRoles)) {
            Flight::halt(400, "Role must be 'customer' or 'admin'.");
        }

    }
}
