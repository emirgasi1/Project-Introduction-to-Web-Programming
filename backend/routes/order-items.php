<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../services/OrderItemService.php';

/**
 * @OA\Get(
 *     path="/order-items",
 *     summary="Get all order items",
 *     tags={"Order Items"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all order items"
 *     )
 * )
 */
Flight::route('GET /order-items', function() {
    $service = new OrderItemService($GLOBALS['db']);
    Flight::json($service->getAll());
});

/**
 * @OA\Get(
 *     path="/order-items/{id}",
 *     summary="Get order item by ID",
 *     tags={"Order Items"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Single order item"
 *     )
 * )
 */
Flight::route('GET /order-items/@id', function($id) {
    $service = new OrderItemService($GLOBALS['db']);
    Flight::json($service->getById($id));
});

/**
 * @OA\Post(
 *     path="/order-items",
 *     summary="Create new order item",
 *     tags={"Order Items"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"order_id", "product_id", "quantity", "price"},
 *             @OA\Property(property="order_id", type="integer", example=1),
 *             @OA\Property(property="product_id", type="integer", example=2),
 *             @OA\Property(property="quantity", type="integer", example=3),
 *             @OA\Property(property="price", type="number", format="float", example=9.99)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order item created"
 *     )
 * )
 */
Flight::route('POST /order-items', function() {
    $data = Flight::request()->data->getData();
    $service = new OrderItemService($GLOBALS['db']);
    Flight::json($service->create($data));
});

/**
 * @OA\Put(
 *     path="/order-items/{id}",
 *     summary="Update order item",
 *     tags={"Order Items"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="order_id", type="integer", example=1),
 *             @OA\Property(property="product_id", type="integer", example=2),
 *             @OA\Property(property="quantity", type="integer", example=3),
 *             @OA\Property(property="price", type="number", format="float", example=9.99)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order item updated"
 *     )
 * )
 */
Flight::route('PUT /order-items/@id', function($id) {
    $data = Flight::request()->data->getData();
    $service = new OrderItemService($GLOBALS['db']);
    Flight::json($service->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/order-items/{id}",
 *     summary="Delete order item",
 *     tags={"Order Items"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order item deleted"
 *     )
 * )
 */
Flight::route('DELETE /order-items/@id', function($id) {
    $service = new OrderItemService($GLOBALS['db']);
    Flight::json($service->delete($id));
});
