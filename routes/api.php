<?php

use App\Http\Controllers\PostController;
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

Route::get('/post', [PostController::class, 'index'])->name('post.index');

Route::delete('post/{id}', [PostController::class, 'delete'])->name('post.delete');

Route::get('post/restore/one/{id}', [PostController::class, 'restore'])->name('post.restore');

Route::get('post/restore_all', [PostController::class, 'restore_all'])->name('post.restore_all');
