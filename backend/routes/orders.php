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
    Flight::auth_middleware()->verifyToken(Flight::request()->getHeader("Authentication"));
    $user = (array) Flight::get('user')->user;

    if (!isset($user['role'])) {
        throw new Exception("Unauthorized", 401);
    }

    $service = new OrderService($GLOBALS['db']);
    if ($user['role'] === 'admin') {
        $result = $service->getAll();
    } else {
        $result = $service->getByUserId($user['user_id']);
    }

    if (!is_array($result)) {
        $result = [];
    }
    Flight::json($result);
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
    Flight::auth_middleware()->verifyToken(Flight::request()->getHeader("Authentication"));
    $user = (array) Flight::get('user')->user;

    $service = new OrderService($GLOBALS['db']);
    $order = $service->getById($id);

    if (!$order) throw new Exception("Order not found.", 404);

    if ($user['role'] !== 'admin' && $order['user_id'] !== $user['user_id']) {
        throw new Exception("Forbidden.", 403);
    }

    Flight::json($order);
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
 *             @OA\Property(property="status", type="string", example="pending"),
 *             @OA\Property(property="order_date", type="string", format="date", example="2025-05-23")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Order created"
 *     )
 * )
 */
Flight::route('POST /orders', function() {
    Flight::auth_middleware()->verifyToken(Flight::request()->getHeader("Authentication"));
    $user = (array) Flight::get('user')->user;

    $data = Flight::request()->data->getData();

    if ($user['role'] !== 'admin') {
        $data['user_id'] = $user['user_id'];
    }

    if (!isset($data['user_id'], $data['total_price'], $data['status'])) {
        throw new Exception("Missing required fields: user_id, total_price, status", 400);
    }
    if (!isset($data['order_date']) || !$data['order_date']) {
        $data['order_date'] = date('Y-m-d');
    }

    $service = new OrderService($GLOBALS['db']);
    $id = $service->create($data);
    Flight::halt(201, json_encode(['order_id' => $id]));
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
 *             @OA\Property(property="status", type="string", example="completed"),
 *             @OA\Property(property="order_date", type="string", format="date", example="2025-05-23")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order updated"
 *     )
 * )
 */
Flight::route('PUT /orders/@id', function($id) {
    Flight::auth_middleware()->verifyToken(Flight::request()->getHeader("Authentication"));
    Flight::auth_middleware()->authorizeRole('admin');

    $data = Flight::request()->data->getData();
    $service = new OrderService($GLOBALS['db']);
    $updated = $service->update($id, $data);

    if (!$updated) throw new Exception("Order not found.", 404);
    Flight::json(['updated' => true]);
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
    Flight::auth_middleware()->verifyToken(Flight::request()->getHeader("Authentication"));
    Flight::auth_middleware()->authorizeRole('admin');

    $service = new OrderService($GLOBALS['db']);
    $deleted = $service->delete($id);

    if (!$deleted) throw new Exception("Order not found.", 404);
    Flight::json(['deleted' => true]);
});
