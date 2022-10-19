<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BusinessUnitController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WorkflowController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::post('verify-account', [AuthController::class, 'verifyAccount'])->name('verifyAccount');

Route::group(['middleware'=>'jwt.auth'],function() {

    Route::apiResource('user', UserController::class)->except(['create', 'edit', 'destroy', 'show', 'store']);
    Route::apiResource('file', FileController::class)->except(['create', 'edit', 'update', 'destroy', 'show']);
    Route::apiResource('business-unit', BusinessUnitController::class)->except(['create', 'edit', 'show']);
    Route::apiResource('contact', ContactController::class)->except(['create', 'edit', 'destroy']);

    Route::post('worflow', [WorkflowController::class, 'store'])->name('store');

});





