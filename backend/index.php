<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

require_once __DIR__ . '/routes/products.php';
require_once __DIR__ . '/routes/categories.php';
require_once __DIR__ . '/routes/orders.php';
require_once __DIR__ . '/routes/order-items.php';
require_once __DIR__ . '/routes/payments.php';
require_once __DIR__ . '/routes/users.php';

Flight::start();
