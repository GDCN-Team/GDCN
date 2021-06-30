<?php

use App\Http\Controllers\Web\Auth\ApiController as AuthApiController;
use App\Http\Controllers\Web\Dashboard\ApiController as DashboardApiController;
use App\Http\Controllers\Web\Dashboard\PageController as DashboardPageController;
use App\Http\Controllers\Web\Tools\ApiController as ToolsApiController;
use App\Http\Controllers\Web\Tools\PageController as ToolsPageController;
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
        Route::post('/login', [AuthApiController::class, 'login'])->name('login.api');
    });

    Route::inertia('/register', 'Auth/Register')->name('register');

    Route::post('/register', [AuthApiController::class, 'register'])->name('register.api');
    Route::get('/logout', [AuthApiController::class, 'logout'])->name('logout.api');
});

Route::group([
    'as' => 'dashboard.',
    'prefix' => 'dashboard',
    'middleware' => 'auth'
], function () {
    Route::get('/', [DashboardPageController::class, 'renderHome'])->name('home');
    Route::get('/profile', [DashboardPageController::class, 'renderProfile'])->name('profile');
    Route::get('/setting', [DashboardPageController::class, 'renderSetting'])->name('setting');
    Route::get('/change-password', [DashboardPageController::class, 'renderPasswordChange'])->name('change-password');

    Route::post('/update-setting', [DashboardApiController::class, 'updateAccountSetting'])->name('setting.update.api');
    Route::post('/change-password', [DashboardApiController::class, 'changePassword'])->name('password.change.api');
});

Route::group([
    'as' => 'tools.',
    'prefix' => 'tools',
    'middleware' => 'auth'
], function () {
    Route::inertia('/', 'Tools/Home')->name('home');

    Route::group([
        'as' => 'account.'
    ], function () {
        Route::get('/account/link', [ToolsPageController::class, 'renderAccountLinkPage'])->name('link');
        Route::post('/account/link', [ToolsApiController::class, 'linkAccount'])->name('link.api');
        Route::post('/account/unlink', [ToolsApiController::class, 'unlinkAccount'])->name('unlink.api');
    });

    Route::group([
        'as' => 'level.'
    ], function () {
        Route::inertia('/level/trans:in', 'Tools/Level/TransIn')->name('trans.in');
        Route::post('/level/trans:in', [ToolsApiController::class, 'levelTransIn'])->name('trans.in.api');

        Route::inertia('/level/trans:out', 'Tools/Level/TransOut')->name('trans.out');
        Route::post('/level/trans:out', [ToolsApiController::class, 'levelTransOut'])->name('trans.out.api');
    });

    Route::group([
        'as' => 'song.'
    ], function () {
        Route::get('/song/upload:link', [ToolsPageController::class, 'renderUploadLinkPage'])->name('upload.link');
        Route::post('/song/upload:link', [ToolsApiController::class, 'CustomSongUpload_Link'])->name('upload.link.api');

        Route::get('/song/upload:netease', [ToolsPageController::class, 'renderUploadNeteasePage'])->name('upload.netease');
        Route::post('/song/upload:netease', [ToolsApiController::class, 'CustomSongUpload_NeteaseMusic'])->name('upload.netease.api');

        Route::get('/song/list', [ToolsPageController::class, 'renderSongListPage'])->name('list');
        Route::post('/song/edit/{song}', [ToolsApiController::class, 'updateSong'])->name('edit.api');
        Route::post('/song/delete/{song}', [ToolsApiController::class, 'deleteSong'])->name('delete.api');
    });
});
