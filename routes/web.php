<?php

use App\Http\Controllers\Game\GDProxyController;
use App\Http\Controllers\Game\NGProxyController;
use App\Http\Controllers\Web\Admin\ApiController as AdminApiController;
use App\Http\Controllers\Web\Auth\ApiController as AuthApiController;
use App\Http\Controllers\Web\Dashboard\ApiController as DashboardApiController;
use App\Http\Controllers\Web\Tools\ApiController as ToolsApiController;
use App\Http\Middleware\Auth as AuthMiddleWare;
use App\Presenters\Web\AdminPresenter;
use App\Presenters\Web\Admin\GroupManagerPresenter;
use App\Presenters\Web\Admin\LevelGauntletManagerPresenter;
use App\Presenters\Web\Admin\LevelPackManagerPresenter;
use App\Presenters\Web\AuthPresenter;
use App\Presenters\Web\DashboardPresenter;
use App\Presenters\Web\GDProxyPresenter;
use App\Presenters\Web\HomePresenter;
use App\Presenters\Web\NGProxyPresenter;
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
    Route::redirect('password.confirm', 'auth.password.confirm');
    Route::get('/', [HomePresenter::class, 'renderHomePage'])->name('home');

    Route::group([
        'middleware' => AuthMiddleWare::class,
        'prefix' => 'admin',
        'as' => 'admin.'
    ], function () {
        Route::get('/', [AdminPresenter::class, 'renderHomePage'])->name('home');

        Route::group([
            'middleware' => 'permission_can:ADMIN_MANAGE_GROUPS'
        ], function () {
            Route::get('/groups', [GroupManagerPresenter::class, 'renderListPage'])
                ->middleware('permission_can:ADMIN_LIST_GROUP')
                ->name('group.list');

            Route::put('/group', [AdminApiController::class, 'createGroup'])
                ->middleware('permission_can:ADMIN_CREATE_GROUP')
                ->name('group.create');

            Route::get('/group/{group}', [GroupManagerPresenter::class, 'renderManagePage'])
                ->middleware('permission_can:ADMIN_MANAGE_GROUP')
                ->name('group.manage');

            Route::patch('/group/{group}', [AdminApiController::class, 'updateGroup'])
                ->middleware('permission_can:ADMIN_UPDATE_GROUP')
                ->name('group.update');

            Route::delete('/group/{group}', [AdminApiController::class, 'deleteGroup'])
                ->middleware('permission_can:ADMIN_DELETE_GROUP')
                ->name('group.delete');

            Route::put('/group/{group}/member/{account}', [AdminApiController::class, 'addMemberToGroup'])
                ->middleware('permission_can:ADMIN_ADD_MEMBER_TO_GROUP')
                ->name('group.manage.add.member');

            Route::delete('/group/{group}/member/{account}', [AdminApiController::class, 'deleteMemberFromGroup'])
                ->middleware('permission_can:ADMIN_DELETE_MEMBER_FROM_GROUP')
                ->name('group.manage.delete.member');

            Route::put('/group/{group}/flag/{flag}', [AdminApiController::class, 'addFlagToGroup'])
                ->middleware('permission_can:ADMIN_ADD_FLAG_TO_GROUP')
                ->name('group.manage.add.flag');

            Route::delete('/group/{group}/flag/{flag}', [AdminApiController::class, 'deleteFlagToGroup'])
                ->middleware('permission_can:ADMIN_ADD_FLAG_TO_GROUP')
                ->name('group.manage.delete.flag');
        });

        Route::group([
            'as' => 'level.',
            'prefix' => 'level'
        ], function () {
            Route::group([
                'middleware' => 'permission_can:ADMIN_MANAGE_LEVEL_GAUNTLETS'
            ], function () {
                Route::get('/gauntlets', [LevelGauntletManagerPresenter::class, 'renderListPage'])
                    ->middleware('permission_can:ADMIN_LIST_LEVEL_GAUNTLETS')
                    ->name('gauntlet.list');

                Route::get('/gauntlet/{gauntlet}', [LevelGauntletManagerPresenter::class, 'renderManagePage'])
                    ->middleware('permission_can:ADMIN_MANAGE_LEVEL_GAUNTLET')
                    ->name('gauntlet.manage');

                Route::put('/gauntlet', [AdminApiController::class, 'createLevelGauntlet'])
                    ->middleware('permission_can:ADMIN_CREATE_LEVEL_GAUNTLET')
                    ->name('gauntlet.create');

                Route::patch('/gauntlet/{gauntlet}', [AdminApiController::class, 'updateLevelGauntlet'])
                    ->middleware('permission_can:ADMIN_UPDATE_LEVEL_GAUNTLET')
                    ->name('gauntlet.update');

                Route::delete('/gauntlet/{gauntlet}', [AdminApiController::class, 'deleteLevelGauntlet'])
                    ->middleware('permission_can:ADMIN_DELETE_LEVEL_GAUNTLET')
                    ->name('gauntlet.delete');
            });

            Route::group([
                'middleware' => 'permission_can:ADMIN_MANAGE_LEVEL_PACKS'
            ], function () {
                Route::get('/packs', [LevelPackManagerPresenter::class, 'renderListPage'])
                    ->middleware('permission_can:ADMIN_LIST_LEVEL_PACKS')
                    ->name('pack.list');

                Route::put('/pack', [AdminApiController::class, 'createLevelPack'])
                    ->middleware('permission_can:ADMIN_CREATE_LEVEL_PACK')
                    ->name('pack.create');

                Route::get('/pack/{pack}', [LevelPackManagerPresenter::class, 'renderManagePage'])
                    ->middleware('permission_can:ADMIN_MANAGE_LEVEL_PACK')
                    ->name('pack.manage');

                Route::patch('/pack/{pack}', [AdminApiController::class, 'updateLevelPack'])
                    ->middleware('permission_can:ADMIN_UPDATE_LEVEL_PACK')
                    ->name('pack.update');

                Route::delete('/pack/{pack}', [AdminApiController::class, 'deleteLevelPack'])
                    ->middleware('permission_can:ADMIN_DELETE_LEVEL_PACK')
                    ->name('pack.delete');
            });
        });
    });

    Route::group([
        'prefix' => 'auth',
        'as' => 'auth.'
    ], function () {
        Route::group([
            'middleware' => 'guest'
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
            'middleware' => AuthMiddleWare::class
        ], function () {
            Route::get('/password/confirm', [AuthPresenter::class, 'renderPasswordConfirmPage'])->name('password.confirm');

            Route::post('/password/confirm', [AuthApiController::class, 'passwordConfirm'])->name('password.confirm.api');
            Route::post('/logout', [AuthApiController::class, 'logout'])->name('logout.api');
        });
    });

    Route::group([
        'prefix' => 'dashboard',
        'as' => 'dashboard.'
    ], function () {
        Route::get('/', [DashboardPresenter::class, 'renderHomePage'])->name('home');
        Route::get('/account/{account}', [DashboardPresenter::class, 'renderAccountInfoPage'])->name('account.info');
        Route::get('/level/{level}', [DashboardPresenter::class, 'renderLevelInfoPage'])->name('level.info');

        Route::group([
            'middleware' => AuthMiddleWare::class
        ], function () {
            Route::get('player', [DashboardPresenter::class, 'renderProfilePage'])->name('profile');

            Route::group([
                'middleware' => 'password.confirm'
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
    ], function () {
        Route::get('/', [ToolsPresenter::class, 'renderHomePage'])->name('home');

        Route::group([
            'middleware' => AuthMiddleWare::class
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
            Route::delete('/account/{link}', [ToolsApiController::class, 'unlinkAccount'])->name('account.unlink.api');
            Route::post('/level/trans:in', [ToolsApiController::class, 'transInLevel'])->name('level.trans.in.api');
            Route::post('/level/trans:out', [ToolsApiController::class, 'transOutLevel'])->name('level.trans.out.api');
            Route::post('/song/upload:link', [ToolsApiController::class, 'uploadSongUsingLink'])->name('song.upload.link.api');
            Route::post('/song/upload:netease', [ToolsApiController::class, 'uploadSongUsingNeteaseMusic'])->name('song.upload.netease.api');
            Route::post('/song/edit', [ToolsApiController::class, 'editSong'])->name('song.edit.api');
            Route::delete('/song/{song}', [ToolsApiController::class, 'deleteSong'])->name('song.delete.api');
        });
    });
});

Route::group([
    'domain' => 'dl.geometrydashchinese.com',
    'as' => 'gdproxy.'
], function () {
    Route::get('/', [GDProxyPresenter::class, 'renderHomePage'])->name('home');
    Route::post('/{path}', [GDProxyController::class, 'proxy'])->where('path', '.*')->name('proxy');
});

Route::group([
    'domain' => 'ng.geometrydashchinese.com',
    'as' => 'ngproxy.'
], function () {
    Route::get('/', [NGProxyPresenter::class, 'renderHomePage'])->name('home');
    Route::get('/{songID}/info', [NGProxyController::class, 'info'])->name('info');
    Route::get('/{songID}/object', [NGProxyController::class, 'object'])->name('object');
});
