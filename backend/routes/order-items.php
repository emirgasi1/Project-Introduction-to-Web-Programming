<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../services/OrderItemService.php';
require_once __DIR__ . '/../middleware/RequestValidationMiddleware.php';

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
    Flight::auth_middleware()->verifyToken(Flight::request()->getHeader("Authentication"));
    $user = (array) Flight::get('user')->user;

    $service = new OrderItemService($GLOBALS['db']);

    if ($user['role'] === 'admin') {
        Flight::json($service->getAll());
    } else {
        Flight::json($service->getByUserId($user['user_id']));
    }
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
    Flight::auth_middleware()->verifyToken(Flight::request()->getHeader("Authentication"));
    $user = (array) Flight::get('user')->user;

    $service = new OrderItemService($GLOBALS['db']);
    $item = $service->getById($id);
    if (!$item) throw new Exception("Order item not found.", 404);

    if ($user['role'] !== 'admin' && $item['user_id'] !== $user['user_id']) {
        throw new Exception("Forbidden.", 403);
    }
    Flight::json($item);
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
 *         response=201,
 *         description="Order item created"
 *     )
 * )
 */
Flight::route('POST /order-items', function() {
    Flight::auth_middleware()->verifyToken(Flight::request()->getHeader("Authentication"));
    $user = (array) Flight::get('user')->user;

    if ($user['role'] !== 'admin') {
        throw new Exception("Only admin can add order items directly.", 403);
    }

    $data = Flight::request()->data->getData();
    RequestValidationMiddleware::validateRequiredFields($data, ['order_id', 'product_id', 'quantity', 'price']);

    $service = new OrderItemService($GLOBALS['db']);
    $id = $service->create($data);
    Flight::halt(201, json_encode(['order_item_id' => $id]));
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
    Flight::auth_middleware()->verifyToken(Flight::request()->getHeader("Authentication"));
    $user = (array) Flight::get('user')->user;

    if ($user['role'] !== 'admin') {
        throw new Exception("Only admin can update order items directly.", 403);
    }

    $data = Flight::request()->data->getData();
    RequestValidationMiddleware::validateRequiredFields($data, ['order_id', 'product_id', 'quantity', 'price']);

    $service = new OrderItemService($GLOBALS['db']);
    $updated = $service->update($id, $data);
    if (!$updated) throw new Exception("Order item not found.", 404);
    Flight::json(['updated' => true]);
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
    Flight::auth_middleware()->verifyToken(Flight::request()->getHeader("Authentication"));
    $user = (array) Flight::get('user')->user;

    if ($user['role'] !== 'admin') {
        throw new Exception("Only admin can delete order items directly.", 403);
    }

    $service = new OrderItemService($GLOBALS['db']);
    $deleted = $service->delete($id);
    if (!$deleted) throw new Exception("Order item not found.", 404);
    Flight::json(['deleted' => true]);
});
