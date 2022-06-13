<?php

use App\Http\Controllers\Api\{BusinessUnitController, FileController, UserController};
use Illuminate\Http\Request;
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

Route::apiResource('user', UserController::class)->except(['create', 'edit', 'destroy', 'show']);

Route::apiResource('file', FileController::class)->except(['create', 'edit', 'update', 'destroy', 'show']);

Route::apiResource('business-unit', BusinessUnitController::class)->except(['create', 'edit', 'show']);
