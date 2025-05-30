<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::group('/auth', function() {

    // POST /auth/register
    Flight::route("POST /register", function() {
        $data = Flight::request()->data->getData();

        // Validacija
        RequestValidationMiddleware::validateRequiredFields($data, ['email', 'password']);

        $response = Flight::auth_service()->register($data);

        if ($response['success']) {
            Flight::json([
                'message' => 'User registered successfully',
                'data' => $response['data']
            ]);
        } else {
            throw new Exception($response['error'], 400);
        }
    });

    // POST /auth/login
    Flight::route('POST /login', function() {
        $data = Flight::request()->data->getData();

        // Validacija
        RequestValidationMiddleware::validateRequiredFields($data, ['email', 'password']);

        $response = Flight::auth_service()->login($data);

        if ($response['success']) {
            Flight::json([
                'message' => 'User logged in successfully',
                'data' => $response['data']
            ]);
        } else {
            throw new Exception($response['error'], 401);
        }
    });

    // Zaštićena ruta za test tokena
    Flight::route('GET /protected', function() {
        $authHeader = Flight::request()->getHeader("Authorization");
        Flight::auth_middleware()->verifyToken($authHeader);
        Flight::json(["message" => "Welcome to protected route"]);
    });
});
