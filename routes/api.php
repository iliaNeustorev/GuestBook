<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MessageController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('messages')->group(function () {
    Route::get('/get', [MessageController::class, 'getMessages']);
    Route::post('/submitMessage', [MessageController::class, 'submitMessage']);
    Route::post('/deleteMessage', [MessageController::class, 'deleteMessage'])->middleware('auth');
    Route::post('/changeMessage', [MessageController::class, 'changeMessage'])->middleware('auth');
});

Route::prefix('admin')->middleware('is_admin')->group(function () {
    Route::post('/showUserMessages', [AdminController::class, 'showUserMessages']);
    Route::get('/CheckIsAdmin', [AdminController::class, 'CheckIsAdmin']);
    Route::post('/deleteMessage', [AdminController::class, 'deleteMessage']);
    Route::post('/changeMessageUser', [AdminController::class, 'changeMessageUser']);
    Route::post('/exportMessages', [AdminController::class, 'exportMessages']);
});

Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout']);

