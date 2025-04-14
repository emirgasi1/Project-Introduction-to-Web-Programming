<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/routes/auth.php';
require_once __DIR__ . '/routes/products.php';
require_once __DIR__ . '/routes/categories.php';
require_once __DIR__ . '/routes/orders.php';
require_once __DIR__ . '/routes/order-items.php';
require_once __DIR__ . '/routes/payments.php';
require_once __DIR__ . '/routes/users.php';

// Set the views path
Flight::set('flight.views.path', __DIR__ . '/views');

// ✅ Global error handler
Flight::map('error', function(Exception $ex){
    // Log exception message to a log file
    error_log("Error: " . $ex->getMessage() . " in " . $ex->getFile() . " on line " . $ex->getLine(), 3, __DIR__ . "/error.log");

    // Respond with the error message
    http_response_code($ex->getCode() ?: 500);
    Flight::json([
        'error' => $ex->getMessage()
    ]);
});

// ✅ Global exception handler
set_exception_handler(function($exception) {
    // Log the exception to the log file
    error_log("Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine(), 3, __DIR__ . "/error.log");
    
    // Respond with an error message
    Flight::halt(500, "Internal Server Error");
});

// ✅ Home route
Flight::route('/home', function() {
    Flight::render('home.php');
});

// Start FlightPHP app
Flight::start();
