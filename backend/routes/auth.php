<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../services/UserService.php';

Flight::route('POST /register', function() {
    $data = Flight::request()->data->getData();

    if (!isset($data['username'], $data['email'], $data['password'])) {
        throw new Exception('Sva polja (username, email, password) su obavezna.', 400);
    }

    $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
    unset($data['password']); 

    $data['role'] = 'user';
    $data['created_at'] = date('Y-m-d H:i:s');

    $userService = new UserService($GLOBALS['db']);
    $user_id = $userService->create($data);

    Flight::json([
        'message' => 'Korisnik uspješno registrovan!',
        'user_id' => $user_id
    ]);
});

Flight::route('POST /login', function() {
    $data = Flight::request()->data->getData();

    if (!isset($data['email'], $data['password'])) {
        throw new Exception('Email i password su obavezni.', 400);
    }

    $userService = new UserService($GLOBALS['db']);
    $user = $userService->getByEmail($data['email']);

    if (!$user || !password_verify($data['password'], $user['password_hash'])) {
        throw new Exception('Neispravni email ili lozinka.', 401);
    }

    Flight::json([
        'message' => 'Uspješno ste prijavljeni!',
        'user' => [
            'user_id' => $user['user_id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role']
        ]
    ]);
});
