<?php

use App\Http\Controllers\ExistsByNameController;
use App\Http\Controllers\GetAllDefaultProfileIconsController;
use App\Http\Controllers\GetOgpByUrlController;
use App\Http\Controllers\GetUserDetailsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SaveProfileController;
use App\Http\Controllers\SaveUserController;
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

Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LogoutController::class, 'logout']);
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::get('/user', [GetUserDetailsController::class, 'execute']);
Route::get('/profile-icons/defaults', [GetAllDefaultProfileIconsController::class,'execute']);
Route::post('/user/check-name',[ExistsByNameController::class,'execute']);
Route::get('/ogp', [GetOgpByUrlController::class, 'execute']);

Route::group(['middleware' => 'auth:sanctum'], function () {
  Route::post('/profile', [SaveProfileController::class, 'execute']);
  Route::post('/user',[SaveUserController::class, 'execute']);
});
