<?php

use App\Http\Controllers\WebApiController;
use App\Http\Controllers\WebToolsApiController;
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

Route::group(['as' => 'web.api.v1.'], function () {
    Route::group(['middleware' => 'web'], function () {
        Route::post('/register', [WebApiController::class, 'register'])->name('register');
        Route::post('/login', [WebApiController::class, 'login'])->name('login');
        Route::post('/logout', [WebApiController::class, 'logout'])->name('logout');
        Route::post('/account/setting_update', [WebApiController::class, 'updateAccountSetting'])->name('account.setting.update');
        Route::post('/account/update_password', [WebApiController::class, 'updateAccountPassword'])->name('account.update.password');
        Route::post('/account/resend_email', [WebApiController::class, 'resendEmail'])->name('account.resend.email');

        Route::group(['as' => 'tools.'], function() {
            Route::post('/tools/songs/upload:netease', [WebToolsApiController::class, 'uploadNeteaseSong'])->name('songs.upload.netease');
            Route::post('/tools/songs/delete', [WebToolsApiController::class, 'deleteSong'])->name('songs.delete');
            Route::post('/tools/songs/list', [WebToolsApiController::class, 'songList'])->name('songs.list');
            Route::post('/tools/accounts/link', [WebToolsApiController::class, 'linkAccount'])->name('accounts.link');
            Route::post('/tools/accounts/link/list', [WebToolsApiController::class, 'getLinkedAccounts'])->name('accounts.link.list');
            Route::post('/tools/accounts/unlink', [WebToolsApiController::class, 'unlinkLinkedAccount'])->name('accounts.link.unlink');
            Route::post('/tools/levels/reupload', [WebToolsApiController::class, 'reuploadLevel'])->name('levels.reupload');
            Route::post('/tools/levels/level-to-gd', [WebToolsApiController::class, 'levelToGd'])->name('levels.level-to-gd');
        });
    });
});
