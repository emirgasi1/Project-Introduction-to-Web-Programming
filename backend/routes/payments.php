<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../services/PaymentService.php';

/**
 * @OA\Get(
 *     path="/payments",
 *     summary="Get all payments",
 *     tags={"Payments"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all payments"
 *     )
 * )
 */
Flight::route('GET /payments', function() {
    Flight::auth_middleware()->verifyToken(Flight::request()->getHeader("Authentication"));
    $user = (array) Flight::get('user')->user;

    $service = new PaymentService($GLOBALS['db']);
    if ($user['role'] === 'admin') {
        Flight::json($service->getAll());
    } else {
        Flight::json($service->getByUserId($user['user_id']));
    }
});

/**
 * @OA\Get(
 *     path="/payments/{id}",
 *     summary="Get payment by ID",
 *     tags={"Payments"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Payment found"
 *     )
 * )
 */
Flight::route('GET /payments/@id', function($id) {
    Flight::auth_middleware()->verifyToken(Flight::request()->getHeader("Authentication"));
    $user = (array) Flight::get('user')->user;

    $service = new PaymentService($GLOBALS['db']);
    $payment = $service->getById($id);
    if (!$payment) throw new Exception("Payment not found.", 404);

    if ($user['role'] !== 'admin' && $payment['user_id'] !== $user['user_id']) {
        throw new Exception("Forbidden.", 403);
    }
    Flight::json($payment);
});

/**
 * @OA\Post(
 *     path="/payments",
 *     summary="Create a new payment",
 *     tags={"Payments"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"order_id", "payment_method", "payment_status"},
 *             @OA\Property(property="order_id", type="integer", example=1),
 *             @OA\Property(property="payment_method", type="string", example="credit_card"),
 *             @OA\Property(property="payment_status", type="string", example="completed")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Payment created"
 *     )
 * )
 */

Flight::route('POST /payments', function() {
    Flight::auth_middleware()->verifyToken(Flight::request()->getHeader("Authentication"));
    Flight::auth_middleware()->authorizeRole('admin');

    $data = Flight::request()->data->getData();
    $service = new PaymentService($GLOBALS['db']);
    $id = $service->create($data);
    Flight::halt(201, json_encode(['payment_id' => $id]));
});

/**
 * @OA\Put(
 *     path="/payments/{id}",
 *     summary="Update payment by ID",
 *     tags={"Payments"},
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
 *             @OA\Property(property="payment_method", type="string", example="paypal"),
 *             @OA\Property(property="payment_status", type="string", example="failed")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Payment updated"
 *     )
 * )
 */
Flight::route('PUT /payments/@id', function($id) {
    Flight::auth_middleware()->verifyToken(Flight::request()->getHeader("Authentication"));
    Flight::auth_middleware()->authorizeRole('admin');

    $data = Flight::request()->data->getData();
    $service = new PaymentService($GLOBALS['db']);
    $updated = $service->update($id, $data);
    if (!$updated) throw new Exception("Payment not found.", 404);
    Flight::json(['updated' => true]);
});

/**
 * @OA\Delete(
 *     path="/payments/{id}",
 *     summary="Delete payment by ID",
 *     tags={"Payments"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Payment deleted"
 *     )
 * )
 */
Flight::route('DELETE /payments/@id', function($id) {
    Flight::auth_middleware()->verifyToken(Flight::request()->getHeader("Authentication"));
    Flight::auth_middleware()->authorizeRole('admin');

    $service = new PaymentService($GLOBALS['db']);
    $deleted = $service->delete($id);
    if (!$deleted) throw new Exception("Payment not found.", 404);
    Flight::json(['deleted' => true]);
});