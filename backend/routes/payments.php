<?php
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
    $service = new PaymentService($GLOBALS['db']);
    Flight::json($service->getAll());
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
    $service = new PaymentService($GLOBALS['db']);
    Flight::json($service->getById($id));
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
    $data = Flight::request()->data->getData();
    $service = new PaymentService($GLOBALS['db']);
    Flight::json($service->create($data));
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
    $data = Flight::request()->data->getData();
    $service = new PaymentService($GLOBALS['db']);
    Flight::json($service->update($id, $data));
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
    $service = new PaymentService($GLOBALS['db']);
    Flight::json($service->delete($id));
});
