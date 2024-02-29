<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialFavoriteController;
use App\Http\Controllers\MaterialLikeController;
use App\Http\Controllers\UserMaterialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

// ログインと新規登録のルーティング
// Route::post('/register', 'App\Http\Controllers\AuthController@register');
// Route::post('/login', 'App\Http\Controllers\AuthController@login');
// Route::post('/test', 'App\Http\Controllers\AuthController@test');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('/materials', [MaterialController::class, 'store']);

Route::resource('/categories', CategoryController::class);
Route::resource('/materials', MaterialController::class);
Route::middleware('auth:sanctum')->get('/materials/{id}', [MaterialController::class, 'show']);
Route::get('/materials/{id}/download', [MaterialController::class, 'download']);

Route::get('/users/{user_id}/materials', [UserMaterialController::class, 'index']);

Route::resource('/materials.likes', MaterialLikeController::class)->only(['store', 'destroy', 'show'])->middleware('auth:sanctum');
Route::resource('/materials.favorites', MaterialFavoriteController::class)->only(['store', 'destroy', 'show'])->middleware('auth:sanctum');