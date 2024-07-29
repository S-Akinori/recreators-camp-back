<?php

use App\Http\Controllers\AdminMaterialController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialFavoriteController;
use App\Http\Controllers\MaterialLikeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PermissionRequestController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
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
// Route::post('/login',[AuthController::class, 'login']);
Route::get('/test', function(Request $request) {
  Log::info(config('app.url'));
  return response()->json(config('app.url'), 200);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('/materials', [MaterialController::class, 'store']);

Route::resource('/categories', CategoryController::class);
Route::resource('/materials', MaterialController::class);
Route::get('/materials/tag/{tag}', [MaterialController::class, 'getMaterialsByTag']);

Route::resource('/comments', CommentController::class)->only(['store', 'destroy', 'update'])->middleware('auth:sanctum');
Route::resource('/comments', CommentController::class)->only(['index', 'show']);
Route::middleware('auth:sanctum')->get('/materials/{id}', [MaterialController::class, 'show']);
Route::get('/materials/{id}/download', [MaterialController::class, 'download']);

Route::get('/users/{user_id}/materials', [UserMaterialController::class, 'index']);

Route::resource('/materials.likes', MaterialLikeController::class)->only(['store', 'destroy', 'show'])->middleware('auth:sanctum');
Route::resource('/materials.favorites', MaterialFavoriteController::class)->only(['store', 'destroy', 'show'])->middleware('auth:sanctum');
Route::resource('news', NewsController::class)->only(['store', 'update', 'destroy', 'edit'])->middleware('auth:sanctum');
Route::resource('news', NewsController::class)->only(['index']);
Route::get('/news/{slug}', [NewsController::class, 'showBySlug']);

Route::middleware('auth:sanctum')->get('/user/favorites', [MaterialFavoriteController::class, 'index']);

Route::middleware('auth:sanctum')->post('/materials/{id}/permission_request', [PermissionRequestController::class, 'send']);

Route::middleware('auth:sanctum')->get('/permission_tokens/{id}', [PermissionRequestController::class, 'show']);
Route::middleware('auth:sanctum')->put('/permission_tokens/{id}', [PermissionRequestController::class, 'update']);
Route::middleware('auth:sanctum')->get('/permission_tokens/materials/{id}', [PermissionRequestController::class, 'showByMaterialId']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);

Route::post('/file', [FileUploadController::class, 'store']);

Route::post('/contact', [ContactController::class, 'send']);

Route::middleware('auth:sanctum')->put('/admin/users/{id}', [AdminUserController::class, 'update']);
Route::middleware('auth:sanctum')->put('/admin/materials/{id}', [AdminMaterialController::class, 'update']);

Route::get('/search', [SearchController::class, 'search']);

Route::get('/tags', [TagController::class, 'index']);
Route::post('/tags', [TagController::class, 'store']);
Route::get('/tags/{id}', [TagController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/{user}/is-following', [UserController::class, 'isFollowing']);
    Route::post('/users/{user}/follow', [FollowController::class, 'follow']);
    Route::post('/users/{user}/unfollow', [FollowController::class, 'unfollow']);
});
Route::get('/users/{user}/followers', [FollowController::class, 'followers']);
Route::get('/users/{user}/followings', [FollowController::class, 'followings']);