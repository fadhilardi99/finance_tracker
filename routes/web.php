<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('finance-tracker');
})->name('finance-tracker');

Route::get('/finance-tracker', function () {
    return view('finance-tracker');
})->name('finance-tracker.index');