<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth:api')->group(function() {
    
    Route::prefix('/blog')->group(function() {
        Route::get('/', [BlogController::class, 'index']);
        Route::get('/detail/{id}', [BlogController::class, 'detail']);
        Route::post('/store', [BlogController::class, 'store']);
        Route::put('/edit', [BlogController::class, 'edit']);
        Route::delete('/delete/{id}', [BlogController::class, 'delete']);
    });

    Route::prefix('/comment')->group(function() {
        Route::get('/{id}', [CommentController::class, 'index']);
        Route::post('/store', [CommentController::class, 'store']);
        Route::put('/edit', [CommentController::class, 'edit']);
        Route::delete('/delete/{id}', [CommentController::class, 'delete']);
    });

});
