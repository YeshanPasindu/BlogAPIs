<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::get('/posts', [PostController::class, 'index']); //read own posts
    Route::post('/posts', [PostController::class, 'store']);//create post
    Route::put('/posts/{post}', [PostController::class, 'update']); //update own posts
    Route::delete('/posts/{post}', [PostController::class, 'destroy']); //delete own posts
    Route::get('/posts/{post}', [PostController::class, 'show']); //get only one post from postid

    Route::post('/posts/comments/{post_id}', [CommentController::class, 'store']); //add comment to any post
    Route::delete('/posts/comments/{comment_id}', [CommentController::class, 'destroy']); //delete own comments
    Route::put('/posts/comments/{comment_id}', [CommentController::class, 'update']); //update own comments
    
    
    Route::get('/allposts', [PostController::class, 'getAll']); //get all published posts
    Route::get('/postssearch/search', [PostController::class, 'searchAndFilter']); //post search and filter


});

