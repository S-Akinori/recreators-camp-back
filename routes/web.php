<?php

use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::post('/register', 'App\Http\Controllers\AuthController@register');
// Route::post('/login', 'App\Http\Controllers\AuthController@login');

Route::middleware('web')->group(function () {
    // ソーシャルログインのリダイレクトとコールバック
    Route::get('/login/{provider}', [SocialiteController::class, 'redirectToProvider']);
    Route::get('/login/{provider}/callback', [SocialiteController::class, 'handleProviderCallback']);
    
    // ログアウト
    Route::post('/logout', function () {
        Auth::logout();
        return response()->json(['message' => 'Logged out']);
    });
});

Route::get('/', function () {
  return view('welcome');
})->name('login');

Route::get('/register', function () {
  return view('welcome');
})->name('password.reset');