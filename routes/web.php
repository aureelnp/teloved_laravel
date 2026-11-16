<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/categories', function () {
    return view('categories.index');
})->name('categories.view');

Route::get('/products', function () {
    return view('products.index');
})->name('products.view');

Route::get('/users', function () {
    return view('users.index');
})->name('users.view');
