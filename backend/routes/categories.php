<?php
require_once __DIR__ . '/../services/CategoryService.php';

/**
 * @OA\Get(
 *     path="/categories",
 *     summary="Get all categories",
 *     tags={"Categories"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all categories"
 *     )
 * )
 */
Flight::route('GET /categories', function() {
    $service = new CategoryService($GLOBALS['db']);
    Flight::json($service->getAll());
});

/**
 * @OA\Get(
 *     path="/categories/{id}",
 *     summary="Get category by ID",
 *     tags={"Categories"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Single category data"
 *     )
 * )
 */
Flight::route('GET /categories/@id', function($id) {
    $service = new CategoryService($GLOBALS['db']);
    Flight::json($service->getById($id));
});

/**
 * @OA\Post(
 *     path="/categories",
 *     summary="Create new category",
 *     tags={"Categories"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"category_name"},
 *             @OA\Property(property="category_name", type="string", example="Burgers")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category created"
 *     )
 * )
 */
Flight::route('POST /categories', function() {
    $data = Flight::request()->data->getData();
    $service = new CategoryService($GLOBALS['db']);
    Flight::json($service->create($data));
});

/**
 * @OA\Put(
 *     path="/categories/{id}",
 *     summary="Update category",
 *     tags={"Categories"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="category_name", type="string", example="Wraps")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category updated"
 *     )
 * )
 */
Flight::route('PUT /categories/@id', function($id) {
    $data = Flight::request()->data->getData();
    $service = new CategoryService($GLOBALS['db']);
    Flight::json($service->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/categories/{id}",
 *     summary="Delete category",
 *     tags={"Categories"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category deleted"
 *     )
 * )
 */
Flight::route('DELETE /categories/@id', function($id) {
    $service = new CategoryService($GLOBALS['db']);
    Flight::json($service->delete($id));
});
