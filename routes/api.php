<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ServerController;
use App\Http\Controllers\Web\ToolsController;
use Illuminate\Support\Facades\Route;

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


Route::group([
    'middleware' => 'web'
], function () {
    Route::get('/server/stat', [ServerController::class, 'getStat'])->name('api.home.server.stat');

    Route::group([
        'as' => 'api.auth.',
        'prefix' => 'auth'
    ], function () {
        Route::get('/check/verified', [AuthController::class, 'checkVerified'])->name('verified.check');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    Route::group([
        'as' => 'api.dashboard.',
        'prefix' => 'dashboard'
    ], function () {
        Route::get('/player/profile', [DashboardController::class, 'getPlayerProfile'])->name('profile');
        Route::post('/player/settings/update', [DashboardController::class, 'updateSettings'])->name('settings.update');
        Route::get('/player/email_verification/resend', [DashboardController::class, 'resendEmailVerification'])->name('email_verification.resend');
    });

    Route::group([
        'as' => 'api.tools.',
        'prefix' => 'tools'
    ], function() {
        Route::get('/servers', [ToolsController::class, 'getServers'])->name('servers');
        Route::post('/account/link', [ToolsController::class, 'linkAccount'])->name('account.link');
        Route::delete('/account/unlink/{id}', [ToolsController::class, 'unlinkAccount'])->name('account.unlink');
        Route::get('/account/link/list', [ToolsController::class, 'linkedAccounts'])->name('account.link.list');
        Route::post('/level/trans/in', [ToolsController::class, 'levelTransIn'])->name('level.trans.in');
        Route::post('/level/trans/out', [ToolsController::class, 'levelTransOut'])->name('level.trans.out');
        Route::get('/song/latest/id', [ToolsController::class, 'getLatestSongID'])->name('song.latest.id.get');
        Route::post('/song/netease/upload', [ToolsController::class, 'uploadNeteaseMusic'])->name('song.netease.upload');
        Route::get('/song/netease/info/{id}', [ToolsController::class, 'getNeteaseMusicInfo'])->name('song.netease.info');
        Route::post('/song/edit', [ToolsController::class, 'editSong'])->name('song.edit');
        Route::post('/song/link/upload', [ToolsController::class, 'uploadLink'])->name('song.link.upload');
        Route::get('/song/list', [ToolsController::class, 'getSongList'])->name('song.list');
        Route::delete('/song/delete/{id}', [ToolsController::class, 'deleteSong'])->name('song.delete');
    });
});
