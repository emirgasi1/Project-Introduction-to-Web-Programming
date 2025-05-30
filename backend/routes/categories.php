<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../services/CategoryService.php';
require_once __DIR__ . '/../middleware/RequestValidationMiddleware.php';

/**
 * @OA\Get(
 *     path="/categories",
 *     summary="Get all categories",
 *     tags={"Categories"},
 *     @OA\Response(response=200, description="List of all categories")
 * )
 */
Flight::route('GET /categories', function() {
    // Provjeri token i autorizaciju
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRoles(['admin', 'customer']); // Oba mogu pristupiti

    $db = Flight::get('db');
    $service = new CategoryService($db);
    Flight::json($service->getAll());
});

/**
 * @OA\Get(
 *     path="/categories/{id}",
 *     summary="Get category by ID",
 *     tags={"Categories"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Single category data"),
 *     @OA\Response(response=404, description="Not Found")
 * )
 */
Flight::route('GET /categories/@id', function($id) {
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRoles(['admin', 'customer']);

    $db = Flight::get('db');
    $service = new CategoryService($db);
    $cat = $service->getById((int)$id);
    if (!$cat) throw new Exception("Category not found.", 404);
    Flight::json($cat);
});
/**
 * @OA\Post(
 *     path="/categories",
 *     summary="Create new category",
 *     tags={"Categories"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(required={"category_name"}, @OA\Property(property="category_name", type="string", example="Burgers"))
 *     ),
 *     @OA\Response(response=201, description="Category created")
 * )
 */
Flight::route('POST /categories', function() {
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRole('admin'); // Samo admin može kreirati

    $db = Flight::get('db');
    $data = Flight::request()->data->getData();
    $service = new CategoryService($db);
    $id = $service->create($data);
    Flight::halt(201, json_encode(['category_id' => $id]));
});

/**
 * @OA\Put(
 *     path="/categories/{id}",
 *     summary="Update category",
 *     tags={"Categories"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(@OA\Property(property="category_name", type="string"))),
 *     @OA\Response(response=200, description="Category updated")
 * )
 */

Flight::route('PUT /categories/@id', function($id) {
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRole('admin');

    $db = Flight::get('db');
    $data = Flight::request()->data->getData();
    $service = new CategoryService($db);
    $updated = $service->update((int)$id, $data);
    if (!$updated) throw new Exception("Category not found.", 404);
    Flight::json(['updated' => true]);
});

/**
 * @OA\Delete(
 *     path="/categories/{id}",
 *     summary="Delete category",
 *     tags={"Categories"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Category deleted")
 * )
 */
Flight::route('DELETE /categories/@id', function($id) {
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRole('admin');

    $db = Flight::get('db');
    $service = new CategoryService($db);
    $deleted = $service->delete((int)$id);
    if (!$deleted) throw new Exception("Category not found.", 404);
    Flight::json(['deleted' => true]);
});
