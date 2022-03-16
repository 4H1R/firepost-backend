<?php

use App\Http\Controllers\Auth\AuthenticatedUser;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\UserPostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserFollowerController;
use App\Http\Controllers\User\UserFollowingController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', AuthenticatedUser::class)->name('user');
    Route::get('/search', SearchController::class)->name('search');
});
Route::apiResource('posts', PostController::class);
Route::apiResource('users.posts', UserPostController::class)->only(['index']);
Route::apiResource('users', UserController::class)->except(['store']);
Route::apiResource('users.followers', UserFollowerController::class)->only(['index', 'store']);
Route::apiResource('users.followings', UserFollowingController::class)->only(['index']);
Route::delete('users/{user}/followers', [UserFollowerController::class, 'destroy'])->name('users.followers.destroy');
