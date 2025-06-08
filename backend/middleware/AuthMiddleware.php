<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {

    public function verifyToken($token) {
        
        if (!$token) {
            Flight::halt(401, "Missing authentication header");
        }

        try {
            $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
            Flight::set('user', $decoded_token);
            Flight::set('jwt_token', $token);
            return TRUE;
        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    }

public function authorizeRole($requiredRole) {
    $user = Flight::get('user');
    $role = $user->user->role ?? '';
    if (!$role || $role !== $requiredRole) {
        Flight::halt(403, 'Access denied: insufficient privileges');
    }
}

public function authorizeRoles($roles) {
    $user = Flight::get('user');
    $role = $user->user->role ?? '';
    if (!$role || !in_array($role, $roles)) {
        Flight::halt(403, 'Forbidden: role not allowed');
    }
}


}
