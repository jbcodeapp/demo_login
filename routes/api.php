<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/register', [UserApiController::class, 'register']);
Route::post('/login', [UserApiController::class, 'login']);

Route::get('/users', [UserApiController::class, 'index']);

Route::middleware('auth:api')->group(function () {
    Route::put('/api/user/update-status/{user}', [UserApiController::class, 'updateStatus']);
});

// Route::get('/api/user', function (Request $request) {
//     return $request->user();
// });


// Route::controller(UserController::class)->group(function ()  {
//     Route::post('/login','/register');
// });

// Route::controller(UserController::class)->group(function ()  {
//     Route::get('/login','/register');
// });