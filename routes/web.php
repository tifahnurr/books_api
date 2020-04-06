<?php

use Illuminate\Support\Facades\Route;
use App\User;
use App\Category;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth:api'])->group(function () {
    Route::get('/books', 'BookController@all');
    Route::get('/categories', 'CategoryController@all');
    Route::post('/comments', 'CommentController@store');
    Route::post('/ratings', 'RatingController@store');
    Route::get('/books/{id}', 'BookController@get');

    Route::middleware(['can:admin'])->group(function () {
        Route::post('/users', 'UserController@store');
        Route::get('/users', 'UserController@all');
        Route::get('/users/{id}', 'UserController@get');
        Route::put('/users', 'UserController@update');
        Route::delete('/users/{user_id}', 'UserController@delete');
        
        Route::post('/books', 'BookController@store');
        Route::post('/books/categories', 'BookController@addCategory');
        Route::put('/books', 'BookController@update');
        Route::delete('/books/{book_id}', 'BookController@delete');
                
        Route::post('/categories', 'CategoryController@store');
        Route::put('/categories', 'CategoryController@update');
        Route::delete('/categories/{category_id}', 'CategoryController@delete');

        Route::post('/comments/approve', 'CommentController@approve');
        Route::get('/comments', 'CommentController@all');
    
    });
});



Route::post('/', 'UserController@login');


Route::get('/books/{id}/categories', 'BookController@getCategories');

