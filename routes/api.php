<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GalleryController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::controller(AuthController::class)->group(
    function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::post('/logout', 'logout');
        Route::get('/me', 'activeUser')->middleware('auth:sanctum');
        Route::post('/refresh', 'refresh');
    }
);

Route::controller(GalleryController::class)->group(
    function() {
        Route::get('/galleries', 'index');
        Route::get('/galleries/{id}','show');
        Route::post('/galleries', 'store')->middleware('auth:sanctum');
        Route::put('/galleries/{id}', 'update')->middleware('auth:sanctum');
        Route::delete('/galleries/{id}', 'destroy');
    }
);

Route::controller(CommentController::class)->group(
    function() {
        Route::post('/galleries/{id}/comments', 'store')->middleware('auth:sanctum');
        Route::delete('comments/{id}', 'destroy')->middleware('auth:sanctum');
    }
);