<?php

use App\Http\Controllers\WebToolsController;
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

Route::view('/', 'home')->name('home');

Route::group(['middleware' => 'guest'], function () {
    Route::view('/register', 'auth.register')->name('register');
    Route::view('/login', 'auth.login')->name('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::group(['as' => 'dashboard.', 'prefix' => 'dashboard'], function () {
        Route::view('/', 'dashboard.home')->name('home');
        Route::view('/profile', 'dashboard.profile')->name('profile');
        Route::view('/account/setting', 'dashboard.account.setting')->name('account.setting');
        Route::view('/account/setting/update_password', 'dashboard.account.update_password')->name('account.update.password');
    });

    Route::group(['as' => 'tools.', 'prefix' => 'tools'], function () {
        Route::view('/', 'tools.home')->name('home');
        Route::view('/songs/upload:netease', 'tools.songs.upload_netease')->name('songs.upload.netease');
        Route::view('/songs/all', 'tools.songs.list')->name('songs.list');
        Route::view('/accounts/link', 'tools.accounts.link')->name('accounts.link');
        Route::view('/levels/reupload', 'tools.levels.reupload')->name('levels.reupload');
        Route::view('/levels/level-to-gd', 'tools.levels.level-to-gd')->name('levels.level-to-gd');
    });
});
