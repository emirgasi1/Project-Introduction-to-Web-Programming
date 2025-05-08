<?php

require __DIR__ . '/../../../vendor/autoload.php';

// Generišemo OpenAPI spec
$openapi = \OpenApi\Generator::scan([
   __DIR__ . '/doc_setup.php',
   __DIR__ . '/../../../rest/routes' // folder sa tvojim PHP route fajlovima
]);

// Postavimo header za JSON i izbacimo OpenAPI dokumentaciju
header('Content-Type: application/json');
echo $openapi->toJson();
?>
