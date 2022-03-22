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

Route::get('search', SearchController::class)->middleware('auth:sanctum')->name('search');
Route::apiResource('posts', PostController::class);
Route::apiResource('users.posts', UserPostController::class)->only(['index']);
// There is issue with uploaded image when using put/patch so we gotta use post
Route::post('users/{user}', [UserController::class, 'update'])->name('users.update');
Route::apiResource('users', UserController::class)->except(['store', 'update']);
Route::apiResource('users.followers', UserFollowerController::class)->only(['index', 'store']);
Route::apiResource('users.followings', UserFollowingController::class)->only(['index']);
Route::delete('users/{user}/followers', [UserFollowerController::class, 'destroy'])->name('users.followers.destroy');
