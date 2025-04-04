<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../services/OrderService.php';

/**
 * @OA\Get(
 *     path="/orders",
 *     summary="Get all orders",
 *     tags={"Orders"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all orders"
 *     )
 * )
 */
Flight::route('GET /orders', function() {
    $service = new OrderService($GLOBALS['db']);
    Flight::json($service->getAll());
});

/**
 * @OA\Get(
 *     path="/orders/{id}",
 *     summary="Get order by ID",
 *     tags={"Orders"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Single order object"
 *     )
 * )
 */
Flight::route('GET /orders/@id', function($id) {
    $service = new OrderService($GLOBALS['db']);
    Flight::json($service->getById($id));
});

/**
 * @OA\Post(
 *     path="/orders",
 *     summary="Create a new order",
 *     tags={"Orders"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "total_price", "status"},
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="total_price", type="number", format="float", example=29.99),
 *             @OA\Property(property="status", type="string", example="pending")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order created"
 *     )
 * )
 */
Flight::route('POST /orders', function() {
    $data = Flight::request()->data->getData();
    $service = new OrderService($GLOBALS['db']);
    Flight::json($service->create($data));
});

/**
 * @OA\Put(
 *     path="/orders/{id}",
 *     summary="Update order by ID",
 *     tags={"Orders"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="total_price", type="number", format="float", example=49.99),
 *             @OA\Property(property="status", type="string", example="completed")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order updated"
 *     )
 * )
 */
Flight::route('PUT /orders/@id', function($id) {
    $data = Flight::request()->data->getData();
    $service = new OrderService($GLOBALS['db']);
    Flight::json($service->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/orders/{id}",
 *     summary="Delete order by ID",
 *     tags={"Orders"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order deleted"
 *     )
 * )
 */
Flight::route('DELETE /orders/@id', function($id) {
    $service = new OrderService($GLOBALS['db']);
    Flight::json($service->delete($id));
});
