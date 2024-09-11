<?php

use App\Http\Controllers\ExistsByNameController;
use App\Http\Controllers\GetAllDefaultProfileIconsController;
use App\Http\Controllers\GetUserDetailsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SaveProfileController;
use App\Http\Controllers\SaveUserController;
use App\Http\Middleware\RejectSystemUserAccess;
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

// 認証不要のパブリックルート
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/profile-icons/defaults', [GetAllDefaultProfileIconsController::class, 'execute']);
Route::post('/user/check-name', [ExistsByNameController::class, 'execute']);
Route::post('/logout', [LogoutController::class, 'logout']);

// 認証が必要なルート
Route::middleware(['auth:sanctum',RejectSystemUserAccess::class])->group(function () {
  Route::get('/user', [GetUserDetailsController::class, 'execute']);
  Route::post('/profile', [SaveProfileController::class, 'execute']);
  Route::post('/user', [SaveUserController::class, 'execute']);
});
