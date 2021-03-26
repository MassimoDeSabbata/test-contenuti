<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/login', [AuthController::class, 'login']);


/**
 * Autenticated routes
 */
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('articles', ArticleController::class);
    Route::get('/articles/{article}/publish', [ArticleController::class, 'publish']);
});


/**
 * Public routes
 */
Route::prefix('public')->name('public.')->group(function () {
    Route::get('articles', [ArticleController::class, 'index']);
    Route::get('articles/{article}', [ArticleController::class, 'show']);


    Route::apiResource('categories', CategoryController::class);
});
