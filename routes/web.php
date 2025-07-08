<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});

// Category & Books
Route::resource('categories', CategoryController::class);
Route::resource('books', BookController::class);
Route::resource('users', UserController::class);

// Report
Route::get('/reports/books-by-category', [BookController::class, 'reportByCategory'])
    ->name('reports.books-by-category');
Route::get('/reports/books-by-category', [BookController::class, 'reportByCategory'])
    ->name('reports.books-by-category');
Route::get('/reports/low-stock', [BookController::class, 'reportLowStock'])
    ->name('reports.low-stock');
