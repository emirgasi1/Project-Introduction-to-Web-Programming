<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../services/UserService.php';

/**
 * @OA\Get(
 *     path="/users",
 *     summary="Get all users",
 *     tags={"Users"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all users"
 *     )
 * )
 */
Flight::route('GET /users', function() {
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRole('admin');

    $service = new UserService($GLOBALS['db']);
    $users = $service->getAll();

    foreach ($users as &$user) {
        unset($user['password_hash']);
    }

    Flight::json($users);
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     summary="Get user by ID",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User data"
 *     )
 * )
 */
Flight::route('GET /users/@id', function($id) {
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);
    $user = Flight::get('user');

    if ($user->user->role !== 'admin' && (int)$user->user->user_id !== (int)$id) {
        throw new Exception('Access denied: not allowed to view this user', 403);
    }

    $service = new UserService($GLOBALS['db']);
    $result = $service->getById($id);
    if (!$result) throw new Exception("User not found.", 404);
    unset($result['password_hash']);
    Flight::json($result);
});
/**
 * @OA\Post(
 *     path="/users",
 *     summary="Create a new user",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username", "email", "password_hash", "role"},
 *             @OA\Property(property="username", type="string", example="john_doe"),
 *             @OA\Property(property="email", type="string", example="john@example.com"),
 *             @OA\Property(property="password_hash", type="string", example="securepassword123"),
 *             @OA\Property(property="role", type="string", example="user")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User created"
 *     )
 * )
 */
Flight::route('POST /users', function() {
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRole('admin');

    $data = Flight::request()->data->getData();
    $service = new UserService($GLOBALS['db']);
    $id = $service->create($data);
    Flight::json(['user_id' => $id]);
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     summary="Update user by ID",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="username", type="string", example="john_updated"),
 *             @OA\Property(property="email", type="string", example="john_updated@example.com"),
 *             @OA\Property(property="password_hash", type="string", example="newpassword123"),
 *             @OA\Property(property="role", type="string", example="admin")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated"
 *     )
 * )
 */
Flight::route('PUT /users/@id', function($id) {
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);
    $user = Flight::get('user');

    if ($user->user->role !== 'admin' && (int)$user->user->user_id !== (int)$id) {
        throw new Exception('Access denied: not allowed to update this user', 403);
    }

    $data = Flight::request()->data->getData();
    $service = new UserService($GLOBALS['db']);
    $updated = $service->update($id, $data);
    if (!$updated) throw new Exception("User not found.", 404);
    Flight::json(['updated' => true]);
});

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     summary="Delete user by ID",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted"
 *     )
 * )
 */
Flight::route('DELETE /users/@id', function($id) {
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRole('admin');

    $service = new UserService($GLOBALS['db']);
    $deleted = $service->delete($id);
    if (!$deleted) throw new Exception("User not found.", 404);
    Flight::json(['deleted' => true]);
});