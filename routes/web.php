<?php

use App\Http\Controllers\Game\GDProxyController;
use App\Http\Controllers\Game\NGProxyController;
use App\Http\Controllers\Web\Auth\ApiController as AuthApiController;
use App\Http\Controllers\Web\Dashboard\ApiController as DashboardApiController;
use App\Http\Controllers\Web\Tools\ApiController as ToolsApiController;
use App\Http\Middleware\Auth as AuthMiddleWare;
use App\Presenters\Web\AuthPresenter;
use App\Presenters\Web\DashboardPresenter;
use App\Presenters\Web\HomePresenter;
use App\Presenters\Web\ToolsPresenter;
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

Route::group([
    'domain' => 'gf.geometrydashchinese.com'
], function () {
    $guestMiddleware = 'guest';
    $authMiddleware = AuthMiddleWare::class;
    $passwordConfirmMiddleware = 'password.confirm:auth.password.confirm';

    Route::get('/', [HomePresenter::class, 'renderHomePage'])->name('home');

    Route::group([
        'prefix' => 'auth',
        'as' => 'auth.'
    ], function () use ($guestMiddleware, $authMiddleware) {
        Route::group([
            'middleware' => $guestMiddleware
        ], function () {
            Route::get('/register', [AuthPresenter::class, 'renderRegisterPage'])->name('register');
            Route::get('/login', [AuthPresenter::class, 'renderLoginPage'])->name('login');
            Route::get('/name/forgot', [AuthPresenter::class, 'renderForgotNamePage'])->name('name.forgot');
            Route::get('/password/forgot', [AuthPresenter::class, 'renderForgotPasswordPage'])->name('password.forgot');
            Route::get('/password/reset', [AuthPresenter::class, 'renderPasswordResetPage'])->middleware('signed')->name('password.reset');

            Route::post('/register', [AuthApiController::class, 'register'])->name('register.api');
            Route::post('/login', [AuthApiController::class, 'login'])->name('login.api');
            Route::post('/name/forgot', [AuthApiController::class, 'forgotName'])->name('name.forgot.api');
            Route::post('/password/forgot', [AuthApiController::class, 'forgotPassword'])->name('password.forgot.api');
            Route::post('/password/reset', [AuthApiController::class, 'resetPassword'])->name('password.reset.api');
        });

        Route::group([
            'middleware' => $authMiddleware
        ], function () {
            Route::get('/password/confirm', [AuthPresenter::class, 'renderPasswordConfirmPage'])->name('password.confirm');

            Route::post('/password/confirm', [AuthApiController::class, 'passwordConfirm'])->name('password.confirm.api');
            Route::post('/logout', [AuthApiController::class, 'logout'])->name('logout.api');
        });
    });

    Route::group([
        'prefix' => 'dashboard',
        'as' => 'dashboard.'
    ], function () use ($passwordConfirmMiddleware, $authMiddleware) {
        Route::get('/', [DashboardPresenter::class, 'renderHomePage'])->name('home');
        Route::get('/account/{account}', [DashboardPresenter::class, 'renderAccountInfoPage'])->name('account.info');
        Route::get('/level/{level}', [DashboardPresenter::class, 'renderLevelInfoPage'])->name('level.info');

        Route::group([
            'middleware' => $authMiddleware
        ], function () use ($passwordConfirmMiddleware) {
            Route::get('player', [DashboardPresenter::class, 'renderProfilePage'])->name('profile');

            Route::group([
                'middleware' => $passwordConfirmMiddleware
            ], function () {
                Route::get('/player/setting', [DashboardPresenter::class, 'renderProfileSettingPage'])->name('profile.setting');

                Route::post('/player/resendVerificationEmail', [DashboardApiController::class, 'resendVerificationEmail'])->name('profile.verification.email.resend.api');
                Route::post('/player/setting', [DashboardApiController::class, 'updateSetting'])->name('profile.setting.api');
                Route::post('/player/name/sync', [DashboardApiController::class, 'syncUserName'])->name('profile.name.sync.api');
            });
        });
    });

    Route::group([
        'prefix' => 'tools',
        'as' => 'tools.'
    ], function () use ($authMiddleware) {
        Route::get('/', [ToolsPresenter::class, 'renderHomePage'])->name('home');

        Route::group([
            'middleware' => $authMiddleware
        ], function () {
            Route::get('/account/link', [ToolsPresenter::class, 'renderAccountLinkPage'])->name('account.link');
            Route::get('/account/links', [ToolsPresenter::class, 'renderAccountLinkListPage'])->name('account.link.list');
            Route::get('/level/trans:in', [ToolsPresenter::class, 'renderLevelTransInPage'])->name('level.trans.in');
            Route::get('/level/trans:out', [ToolsPresenter::class, 'renderLevelTransOutPage'])->name('level.trans.out');
            Route::get('/song/upload:link', [ToolsPresenter::class, 'renderSongUploadLinkPage'])->name('song.upload.link');
            Route::get('/song/upload:netease', [ToolsPresenter::class, 'renderSongUploadNeteasePage'])->name('song.upload.netease');
            Route::get('/song/list', [ToolsPresenter::class, 'renderSongListPage'])->name('song.list');
            Route::get('/song/edit/{song}', [ToolsPresenter::class, 'renderSongEditPage'])->name('song.edit');

            Route::post('/account/link', [ToolsApiController::class, 'linkAccount'])->name('account.link.api');
            Route::delete('/account/unlink/{link}', [ToolsApiController::class, 'unlinkAccount'])->name('account.unlink.api');
            Route::post('/level/trans:in', [ToolsApiController::class, 'transInLevel'])->name('level.trans.in.api');
            Route::post('/level/trans:out', [ToolsApiController::class, 'transOutLevel'])->name('level.trans.out.api');
            Route::post('/song/upload:link', [ToolsApiController::class, 'uploadSongUsingLink'])->name('song.upload.link.api');
            Route::post('/song/upload:netease', [ToolsApiController::class, 'uploadSongUsingNeteaseMusic'])->name('song.upload.netease.api');
            Route::post('/song/edit', [ToolsApiController::class, 'editSong'])->name('song.edit.api');
            Route::delete('/song/delete/{song}', [ToolsApiController::class, 'deleteSong'])->name('song.delete.api');
        });
    });
});

Route::group([
    'domain' => 'dl.geometrydashchinese.com',
    'as' => 'gdproxy.'
], function () {
    Route::post('/{path}', [GDProxyController::class, 'proxy'])->where('path', '.*')->name('proxy');
});

Route::group([
    'domain' => 'ng.geometrydashchinese.com',
    'as' => 'ngproxy.'
], function () {
    Route::get('/info/{songID}', [NGProxyController::class, 'info'])->name('info');
    Route::get('/object/{songID}', [NGProxyController::class, 'object'])->name('object');
});
