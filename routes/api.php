<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedUser;
use App\Http\Controllers\UserFollowerController;
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

Route::middleware('auth:sanctum')->get('user', AuthenticatedUser::class)->name('user');
Route::apiResource('users', UserController::class)->except(['store']);
Route::apiResource('users.followers', UserFollowerController::class)->only(['index', 'store', 'destroy']);
Route::apiResource('users.followings', UserController::class)->only(['index']);
