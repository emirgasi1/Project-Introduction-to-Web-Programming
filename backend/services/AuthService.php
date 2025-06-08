<?php

require_once __DIR__ . '/../vendor/autoload.php';        // <--- OVO MORA PRVO!
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
require_once __DIR__ . '/../config.php';                // (ili '../config/config.php' ako si vratio u folder)

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthService extends BaseService {

    private $auth_dao;

    public function __construct() {
        $this->auth_dao = new AuthDao(Flight::get('db'));  // Povezivanje sa bazom kroz AuthDao
        parent::__construct(new AuthDao(Flight::get('db')));
    }

public function register($entity) {
    file_put_contents('C:\xampps\htdocs\EmirGasi\Project-Introduction-to-Web-Programming\frontend\REGISTER-DEBUG.txt', "ULAZ U REGISTER\n", FILE_APPEND);

    if (empty($entity['email']) || empty($entity['password'])) {
        file_put_contents('C:\xampps\htdocs\EmirGasi\Project-Introduction-to-Web-Programming\frontend\REGISTER-DEBUG.txt', "FAIL: Email or password empty\n", FILE_APPEND);
        return ['success' => false, 'error' => 'Email and password are required.'];
    }

    $email_exists = $this->auth_dao->get_user_by_email($entity['email']);
    if ($email_exists) {
        file_put_contents('C:\xampps\htdocs\EmirGasi\Project-Introduction-to-Web-Programming\frontend\REGISTER-DEBUG.txt', "FAIL: Email exists\n", FILE_APPEND);
        return ['success' => false, 'error' => 'Email already registered.'];
    }

    $entity['password_hash'] = password_hash($entity['password'], PASSWORD_BCRYPT);
    file_put_contents('C:\xampps\htdocs\EmirGasi\Project-Introduction-to-Web-Programming\frontend\REGISTER-DEBUG.txt', "HASH OK\n", FILE_APPEND);

    if (empty($entity['role'])) {
        $entity['role'] = 'customer';
    }

    file_put_contents('C:\xampps\htdocs\EmirGasi\Project-Introduction-to-Web-Programming\frontend\REGISTER-DEBUG.txt', "PRIJE CREATE\n", FILE_APPEND);

    $entity_id = $this->auth_dao->create($entity);

    file_put_contents('C:\xampps\htdocs\EmirGasi\Project-Introduction-to-Web-Programming\frontend\REGISTER-DEBUG.txt', "POSLIJE CREATE\n", FILE_APPEND);

    unset($entity['password']);
    $entity['id'] = $entity_id;

    return ['success' => true, 'data' => $entity];
}

    // Login korisnika
  public function login($entity) {
    if (empty($entity['email']) || empty($entity['password'])) {
        return ['success' => false, 'error' => 'Email and password are required.'];
    }

    // Proveri da li korisnik postoji u bazi
    file_put_contents('php://stderr', "LOGIN REQUEST: ".print_r($entity,1));
    $user = $this->auth_dao->get_user_by_email($entity['email']);
    file_put_contents('php://stderr', "DB USER: ".print_r($user,1));
    if (!$user) {
        return ['success' => false, 'error' => 'Invalid username or password.'];
    }

    // Proveri lozinku (koristi 'password_hash' umesto 'password')
    if (!password_verify($entity['password'], $user['password_hash'])) {
        return ['success' => false, 'error' => 'Invalid username or password.'];
    }

    unset($user['password_hash']);  // Ne vraćaj lozinku

    // --------- OVO JE NAJBITNIJE ---------
    // U JWT payload OBAVEZNO stavi i ROLE korisnika!
    $role = !empty($user['role']) ? $user['role'] : 'customer';

// login metoda, dio gdje se pravi JWT payload
    $jwt_payload = [
        'user' => [
            'user_id'    => $user['user_id'],
            'email'      => $user['email'],
            'username'   => $user['username'],
            // fallback na 'customer' ako je prazno ili null
            'role'       => !empty($user['role']) ? $user['role'] : 'customer',
            'created_at' => $user['created_at']
        ],
        'iat' => time(),
        'exp' => time() + (60 * 60 * 24)
    ];

$token = JWT::encode($jwt_payload, Config::JWT_SECRET(), 'HS256');


    return ['success' => true, 'data' => array_merge($user, ['token' => $token])];
}

}
