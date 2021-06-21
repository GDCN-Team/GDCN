<?php

/*
 * Copyright (c) 2020. WOSHIZHAZHA120 & GDCN Team
 */


use App\Http\Controllers\Game\AccountBlocksController;
use App\Http\Controllers\Game\AccountCommentsController;
use App\Http\Controllers\Game\AccountFriendRequestsController;
use App\Http\Controllers\Game\AccountFriendsController;
use App\Http\Controllers\Game\AccountMessagesController;
use App\Http\Controllers\Game\AccountSaveDataController;
use App\Http\Controllers\Game\AccountsController;
use App\Http\Controllers\Game\AccountSettingsController;
use App\Http\Controllers\Game\ChallengesController;
use App\Http\Controllers\Game\LevelCommentsController;
use App\Http\Controllers\Game\LevelGauntletsController;
use App\Http\Controllers\Game\LevelPacksController;
use App\Http\Controllers\Game\LevelRatingController;
use App\Http\Controllers\Game\LevelsController;
use App\Http\Controllers\Game\LevelScoresController;
use App\Http\Controllers\Game\MiscController;
use App\Http\Controllers\Game\RewardsController;
use App\Http\Controllers\Game\SongsController;
use App\Http\Controllers\Game\UsersController;
use App\Http\Controllers\Game\UserScoresController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Models Routes
|--------------------------------------------------------------------------
|
| Here is where you can register game routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "game" middleware group. Now create something great!
|
*/

Route::group(['as' => 'game.'], function () {
    Route::post('/accounts/registerGJAccount.php', [AccountsController::class, 'register'])->name('account.register');
    Route::get('/accounts/verifyGJAccount.php', [AccountsController::class, 'verify'])->middleware(['web', 'signed'])->name('account.verify');
    Route::post('/accounts/loginGJAccount.php', [AccountsController::class, 'login'])->name('account.login');
    Route::post('/updateGJUserScore22.php', [UserScoresController::class, 'update'])->name('user.score.update');
    Route::post('/getGJUserInfo20.php', [UsersController::class, 'info'])->name('user.info');
    Route::post('/getGJAccountComments20.php', [AccountCommentsController::class, 'get'])->name('account.comment.list');
    Route::post('/uploadGJAccComment20.php', [AccountCommentsController::class, 'upload'])->name('account.comment.upload');
    Route::post('/deleteGJAccComment20.php', [AccountCommentsController::class, 'delete'])->name('account.comment.delete');
    Route::post('/updateGJAccSettings20.php', [AccountSettingsController::class, 'update'])->name('account.setting.update');
    Route::post('/getGJRewards.php', [RewardsController::class, 'get'])->name('reward.get');
    Route::post('/uploadGJLevel21.php', [LevelsController::class, 'upload'])->name('level.upload');
    Route::post('/getGJChallenges.php', [ChallengesController::class, 'get'])->name('challenge.get');
    Route::post('/getAccountURL.php', [AccountSaveDataController::class, 'getUrl'])->name('account.url.get');
    Route::post('/database/accounts/backupGJAccountNew.php', [AccountSaveDataController::class, 'save'])->name('account.data.save');
    Route::post('/database/accounts/syncGJAccountNew.php', [AccountSaveDataController::class, 'load'])->name('account.data.load');
    Route::post('/getGJLevels21.php', [LevelsController::class, 'search'])->name('level.search');
    Route::post('/downloadGJLevel22.php', [LevelsController::class, 'download'])->name('level.download');
    Route::post('/getGJUsers20.php', [UsersController::class, 'search'])->name('user.search');
    Route::post('/getGJMapPacks21.php', [LevelPacksController::class, 'get'])->name('level.pack.get');
    Route::post('/getGJGauntlets21.php', [LevelGauntletsController::class, 'get'])->name('level.gauntlet.get');
    Route::post('/getGJComments21.php', [LevelCommentsController::class, 'get'])->name('level.comment.get');
    Route::post('/uploadGJComment21.php', [LevelCommentsController::class, 'upload'])->name('level.comment.upload');
    Route::post('/deleteGJComment20.php', [LevelCommentsController::class, 'delete'])->name('level.comment.delete');
    Route::post('/getGJDailyLevel.php', [LevelsController::class, 'getDaily'])->name('daily.level.get');
    Route::post('/deleteGJLevelUser20.php', [LevelsController::class, 'delete'])->name('level.delete');
    Route::post('/requestUserAccess.php', [UsersController::class, 'requestAccess'])->name('user.request.access');
    Route::post('/likeGJItem211.php', [MiscController::class, 'likeItem'])->name('item.like');
    Route::post('/restoreGJItems.php', [MiscController::class, 'restoreItem'])->name('item.restore');
    Route::post('/getGJScores20.php', [UserScoresController::class, 'get'])->name('score.get');
    Route::post('/getGJLevelScores211.php', [LevelScoresController::class, 'get'])->name('level.score.get');
    Route::post('/getGJCommentHistory.php', [LevelCommentsController::class, 'history'])->name('level.comment.history');
    Route::post('/uploadGJMessage20.php', [AccountMessagesController::class, 'send'])->name('account.message.send');
    Route::post('/uploadFriendRequest20.php', [AccountFriendRequestsController::class, 'upload'])->name('account.friend.request.send');
    Route::post('/deleteGJFriendRequests20.php', [AccountFriendRequestsController::class, 'delete'])->name('account.friend.request.delete');
    Route::post('/getGJMessages20.php', [AccountMessagesController::class, 'get'])->name('account.message.get');
    Route::post('/deleteGJMessages20.php', [AccountMessagesController::class, 'delete'])->name('account.message.delete');
    Route::post('/getGJFriendRequests20.php', [AccountFriendRequestsController::class, 'get'])->name('account.friend.request.get');
    Route::post('/readGJFriendRequest20.php', [AccountFriendRequestsController::class, 'read'])->name('account.friend.request.read');
    Route::post('/acceptGJFriendRequest20.php', [AccountFriendRequestsController::class, 'accept'])->name('account.friend.request.accept');
    Route::post('/downloadGJMessage20.php', [AccountMessagesController::class, 'download'])->name('account.message.download');
    Route::post('/getGJUserList20.php', [UsersController::class, 'list'])->name('user.list');
    Route::post('/removeGJFriend20.php', [AccountFriendsController::class, 'remove'])->name('account.friend.remove');
    Route::post('/blockGJUser20.php', [AccountBlocksController::class, 'block'])->name('account.block.block');
    Route::post('/unblockGJUser20.php', [AccountBlocksController::class, 'unblock'])->name('account.block.unblock');
    Route::post('/getGJSongInfo.php', [SongsController::class, 'get'])->name('song.get');
    Route::post('/getGJTopArtists.php', [SongsController::class, 'getTopArtists'])->name('artists.get');
    Route::post('/reportGJLevel.php', [LevelsController::class, 'report'])->name('level.report');
    Route::post('/suggestGJStars20.php', [LevelRatingController::class, 'suggest'])->name('level.rating.suggest');
    Route::post('/rateGJStars211.php', [LevelRatingController::class, 'rate'])->name('level.rating.rate');
    Route::post('/rateGJDemon21.php', [LevelRatingController::class, 'demon'])->name('level.rating.demon');
    Route::post('/updateGJDesc20.php', [LevelsController::class, 'updateDesc'])->name('level.desc.update');
});
