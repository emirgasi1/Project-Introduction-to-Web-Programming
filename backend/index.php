<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';  
require_once __DIR__ . '/services/AuthService.php';  
require_once __DIR__ . '/routes/AuthRoutes.php';  

require_once __DIR__ . '/routes/products.php';
require_once __DIR__ . '/routes/categories.php';
require_once __DIR__ . '/routes/orders.php';
require_once __DIR__ . '/routes/order-items.php';
require_once __DIR__ . '/routes/payments.php';
require_once __DIR__ . '/routes/users.php';

Flight::register('auth_service', 'AuthService');
Flight::register('auth_middleware', 'AuthMiddleware');

Flight::route('/*', function() {
    $url = Flight::request()->url;  

    if (strpos($url, '/auth/login') === 0 || strpos($url, '/auth/register') === 0 || strpos($url, '/swagger') === 0) {
        return TRUE;
    } else {
        try {
          
            $token = Flight::request()->getHeader("Authentication");
            if (Flight::auth_middleware()->verifyToken($token)) {
                return TRUE;
            }
        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    }
});
Flight::map('error', function (Throwable $ex) {
    $code = $ex->getCode();
    if ($code < 400 || $code >= 600) {
        $code = 500; 
    }


    Flight::json([
        'error' => true,
        'message' => $ex->getMessage()
    ], $code);
});

Flight::before('start', function(&$params, &$output) {
    $method = Flight::request()->method;
    $url = Flight::request()->url;
    $time = date('Y-m-d H:i:s');
    error_log("[$time] $method $url");
});

Flight::start();
?>
