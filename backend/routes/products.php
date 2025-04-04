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
    $service = new ProductService($GLOBALS['db']);
    Flight::json($service->getById($id));
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
    $data = Flight::request()->data->getData();
    $service = new ProductService($GLOBALS['db']);
    Flight::json($service->create($data));
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
    $data = Flight::request()->data->getData();
    $service = new ProductService($GLOBALS['db']);
    Flight::json($service->update($id, $data));
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
    $service = new ProductService($GLOBALS['db']);
    Flight::json($service->delete($id));
});
