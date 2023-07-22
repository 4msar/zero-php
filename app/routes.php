<?php

/**
 * There was $router variable have 2 type of method: GET | POST
 * In the method can receive 2 parameter, first one is the path and second one is a callback
 * In callback you can pass an array where the first element is a class
 * and second one is the method in the array
 *
 * To use dynamic variable use colon (:) then name the parameter you will receive this as
 * a parameter of your callback.
 *
 * Remember the first parameter of your callback is reserved as request instance
 * the request instance is belongs to App\Core\Request class.
 */

use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\UserController;
use App\Core\Collection;

$router->get('/', [HomeController::class, 'index']);

$router->get('/settings', [HomeController::class, 'settings']);
$router->post('/settings', [HomeController::class, 'update']);
$router->post('/settings/password', [HomeController::class, 'update_password']);

$router->get('/login', [LoginController::class, 'index']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

$router->get('/users', [UserController::class, 'index']);
$router->post('/users', [UserController::class, 'store']);
$router->post('/users/:id/delete', [UserController::class, 'delete']);


$router->any('/test', function ($request) {

    return new Collection([
        'name' => 'John Doe',
        'age' => 20,
        'address' => 'Manila, Philippines'
    ]);

    $data = app(App\Models\Users::class)->latest()->paginate(2, 1);

    return response()->json($data);
    dd($data);
});
