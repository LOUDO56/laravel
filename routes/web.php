<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/books', function () {
    $books = App\Models\Book::all();
    return view('books', compact('books'));
});