<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../services/ProductService.php';

/**
 * @OA\Get(
 *     path="/products",
 *     summary="Get all products",
 *     tags={"Products"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all products"
 *     )
 * )
 */
Flight::route('GET /products', function() {
    $token = Flight::request()->getHeader("Authentication") ?: Flight::request()->getHeader("Authorization");
    Flight::auth_middleware()->verifyToken($token);
    $service = new ProductService($GLOBALS['db']);
    Flight::json($service->getAll());
});

/**
 * @OA\Get(
 *     path="/products/{id}",
 *     summary="Get a product by ID",
 *     tags={"Products"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product data"
 *     )
 * )
 */
Flight::route('GET /products/@id', function($id) {
    $token = Flight::request()->getHeader("Authentication") ?: Flight::request()->getHeader("Authorization");
    Flight::auth_middleware()->verifyToken($token);
    $service = new ProductService($GLOBALS['db']);
    $product = $service->getById($id);
    if (!$product) throw new Exception("Product not found.", 404);
    Flight::json($product);
});

/**
 * @OA\Post(
 *     path="/products",
 *     summary="Create a new product",
 *     tags={"Products"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"product_name", "description", "price", "category_id", "image_url"},
 *             @OA\Property(property="product_name", type="string"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="price", type="number"),
 *             @OA\Property(property="category_id", type="integer"),
 *             @OA\Property(property="image_url", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product created"
 *     )
 * )
 */
Flight::route('POST /products', function() {
    $token = Flight::request()->getHeader("Authentication") ?: Flight::request()->getHeader("Authorization");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRoles(['admin']);

    $data = Flight::request()->data->getData();
    $service = new ProductService($GLOBALS['db']);
    $id = $service->create($data);
    Flight::halt(201, json_encode(['product_id' => $id]));
});

/**
 * @OA\Put(
 *     path="/products/{id}",
 *     summary="Update a product",
 *     tags={"Products"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="product_name", type="string"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="price", type="number"),
 *             @OA\Property(property="category_id", type="integer"),
 *             @OA\Property(property="image_url", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product updated"
 *     )
 * )
 */
Flight::route('PUT /products/@id', function($id) {
    $token = Flight::request()->getHeader("Authentication") ?: Flight::request()->getHeader("Authorization");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRoles(['admin']);

    $data = Flight::request()->data->getData();
    $service = new ProductService($GLOBALS['db']);
    $updated = $service->update($id, $data);
    if (!$updated) throw new Exception("Product not found.", 404);
    Flight::json(['updated' => true]);
});

/**
 * @OA\Delete(
 *     path="/products/{id}",
 *     summary="Delete a product",
 *     tags={"Products"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product deleted"
 *     )
 * )
 */
Flight::route('DELETE /products/@id', function($id) {
    $token = Flight::request()->getHeader("Authentication") ?: Flight::request()->getHeader("Authorization");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRoles(['admin']);

    $service = new ProductService($GLOBALS['db']);
    $deleted = $service->delete($id);
    if (!$deleted) throw new Exception("Product not found.", 404);
    Flight::json(['deleted' => true]);
});
