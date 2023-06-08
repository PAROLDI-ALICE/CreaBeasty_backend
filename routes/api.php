<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;


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

/*
USER
*/
Route::resource('/user', UserController::class);

/*
LOGIN/LOGOUT
*/
Route::controller(LoginController::class)->group(function () {
    //Connexion
    Route::post('login', 'login');
    //Déconnexion
    Route::post('logout', 'logout');
    //Rafraîchir le token pour retenter le login
    Route::post('refresh', 'refresh');
});
