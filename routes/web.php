<?php

use Illuminate\Support\Facades\Route;

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

Route::view('/', 'app', ['component' => 'Home'])->name('home');

Route::group([
    'as' => 'dashboard.'
], function() {
    Route::view('/dashboard', 'app', ['component' => 'Dashboard'])->name('home');
});

Route::group([
    'as' => 'tools.'
], function() {
    Route::view('/tools', 'app', ['component' => 'Tools'])->name('home');
});
