<?php

use App\Http\Controllers\WebAuthApiController;
use App\Http\Controllers\WebDashboardApiController;
use App\Http\Controllers\WebDashboardPageController;
use App\Http\Controllers\WebToolsApiController;
use App\Http\Controllers\WebToolsPageController;
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

Route::inertia('/', 'Home')->name('home');

Route::group([
    'as' => 'auth.',
    'prefix' => 'auth'
], function () {
    Route::group([
        'middleware' => 'guest'
    ], function () {
        Route::inertia('/login', 'Auth/Login')->name('login');
        Route::post('/login', [WebAuthApiController::class, 'login'])->name('login.api');
    });

    Route::inertia('/register', 'Auth/Register')->name('register');

    Route::post('/register', [WebAuthApiController::class, 'register'])->name('register.api');
    Route::get('/logout', [WebAuthApiController::class, 'logout'])->name('logout.api');
});

Route::group([
    'as' => 'dashboard.',
    'prefix' => 'dashboard',
    'middleware' => 'auth'
], function () {
    Route::get('/', [WebDashboardPageController::class, 'renderHome'])->name('home');
    Route::get('/profile', [WebDashboardPageController::class, 'renderProfile'])->name('profile');
    Route::get('/setting', [WebDashboardPageController::class, 'renderSetting'])->name('setting');
    Route::get('/change-password', [WebDashboardPageController::class, 'renderPasswordChange'])->name('change-password');

    Route::post('/update-setting', [WebDashboardApiController::class, 'updateAccountSetting'])->name('setting.update.api');
    Route::post('/change-password', [WebDashboardApiController::class, 'changePassword'])->name('password.change.api');
});

Route::group([
    'as' => 'tools.',
    'prefix' => 'tools',
    'middleware' => 'auth'
], function () {
    Route::inertia('/', 'Tools/Home')->name('home');
    Route::get('/account/link', [WebToolsPageController::class, 'renderAccountLinkPage'])->name('account.link');
    Route::inertia('/level/trans:in', 'Tools/Level/TransIn')->name('level.trans.in');
    Route::inertia('/level/trans:out', 'Tools/Level/TransOut')->name('level.trans.out');
    Route::get('/song/upload:link', [WebToolsPageController::class, 'renderUploadLinkPage'])->name('song.upload.link');
    Route::get('/song/upload:netease', [WebToolsPageController::class, 'renderUploadNeteasePage'])->name('song.upload.netease');
    Route::get('/song/list', [WebToolsPageController::class, 'renderSongListPage'])->name('song.list');

    Route::post('/account/link', [WebToolsApiController::class, 'linkAccount'])->name('account.link.api');
    Route::post('/account/unlink', [WebToolsApiController::class, 'unlinkAccount'])->name('account.link.api');
    Route::post('/level/trans:in', [WebToolsApiController::class, 'levelTransIn'])->name('level.trans.in');
    #Route::post('/level/trans:out', [WebToolsApiController::class, 'levelTransOut'])->name('level.trans.out');
    Route::post('/song/edit', [WebToolsPageController::class, 'renderEditSongPage'])->name('song.edit');
    Route::post('/song/edit:save', [WebToolsApiController::class, 'updateSong'])->name('song.edit.api');
    Route::post('/song/delete', [WebToolsApiController::class, 'deleteSong'])->name('song.delete');
    Route::post('/song/upload:link', [WebToolsApiController::class, 'CustomSongUpload_Link'])->name('song.upload.link.api');
    Route::post('/song/upload:netease', [WebToolsApiController::class, 'CustomSongUpload_NeteaseMusic'])->name('song.upload.netease.api');
});
