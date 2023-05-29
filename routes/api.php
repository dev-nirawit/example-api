<?php

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function (Request $request) {
    $host = $_SERVER['SERVER_NAME'] ?? $_SERVER['SERVER_ADDR'];
    $uptime = exec('uptime');
    $os = php_uname('s');

    return app()->make(BaseController::class)->sendResponse(['host_server' => $host,
    'uptime_server' => $uptime,
    'os_server' => $os, 'request_ip' => $request->ip()], 'API OK');
});

Route::get('/login', function (Request $request) {
    $host = $_SERVER['SERVER_NAME'] ?? $_SERVER['SERVER_ADDR'];

    return app()->make(BaseController::class)->sendResponse(
        [
        'host_server' => $host,
        'login_route' => '/api/login',
        'form-data' => ['email' => '', 'password' => ''],
        'methods' => 'POST',
        'examples_authen_success' => [
            'route' => '/api/products',
            'headers' => [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer {$accessToken}',
            'methods' => 'GET,POST,UPDATE,DELETE',
                ],
            ],
        ], ' กรุณา Authen ก่อน');
});

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('products', ProductController::class);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
