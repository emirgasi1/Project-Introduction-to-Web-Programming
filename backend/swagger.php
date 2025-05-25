<?php

require __DIR__ . '/../../../vendor/autoload.php';

$openapi = \OpenApi\Generator::scan([
   __DIR__ . '/doc_setup.php',
   __DIR__ . '/../../../rest/routes' 
]);

header('Content-Type: application/json');
echo $openapi->toJson();
?>
