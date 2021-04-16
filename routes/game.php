<?php

/*
 * Copyright (c) 2020. WOSHIZHAZHA120 & GDCN Team
 */


use App\Http\Controllers\GameAccountBlocksController;
use App\Http\Controllers\GameAccountCommentsController;
use App\Http\Controllers\GameAccountFriendRequestsController;
use App\Http\Controllers\GameAccountFriendsController;
use App\Http\Controllers\GameAccountMessagesController;
use App\Http\Controllers\GameAccountSaveDataController;
use App\Http\Controllers\GameAccountsController;
use App\Http\Controllers\GameAccountSettingsController;
use App\Http\Controllers\GameChallengesController;
use App\Http\Controllers\GameLevelCommentsController;
use App\Http\Controllers\GameLevelGauntletsController;
use App\Http\Controllers\GameLevelPacksController;
use App\Http\Controllers\GameLevelRatingController;
use App\Http\Controllers\GameLevelsController;
use App\Http\Controllers\GameLevelScoresController;
use App\Http\Controllers\GameMiscController;
use App\Http\Controllers\GameRewardsController;
use App\Http\Controllers\GameSongsController;
use App\Http\Controllers\GameUsersController;
use App\Http\Controllers\GameUserScoresController;
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
    Route::post('/accounts/registerGJAccount.php', [GameAccountsController::class, 'register'])->name('account.register');
    Route::get('/accounts/verifyGJAccount.php/{id}/{hash}', [GameAccountsController::class, 'verify'])->middleware(['web', 'signed'])->name('verification.verify');
    Route::post('/accounts/loginGJAccount.php', [GameAccountsController::class, 'login'])->name('account.login');
    Route::post('/updateGJUserScore22.php', [GameUserScoresController::class, 'update'])->name('user.score.update');
    Route::post('/getGJUserInfo20.php', [GameUsersController::class, 'info'])->name('user.info');
    Route::post('/getGJAccountComments20.php', [GameAccountCommentsController::class, 'get'])->name('account.comment.list');
    Route::post('/uploadGJAccComment20.php', [GameAccountCommentsController::class, 'upload'])->name('account.comment.upload');
    Route::post('/deleteGJAccComment20.php', [GameAccountCommentsController::class, 'delete'])->name('account.comment.delete');
    Route::post('/updateGJAccSettings20.php', [GameAccountSettingsController::class, 'update'])->name('account.setting.update');
    Route::post('/getGJRewards.php', [GameRewardsController::class, 'get'])->name('reward.get');
    Route::post('/uploadGJLevel21.php', [GameLevelsController::class, 'upload'])->name('level.upload');
    Route::post('/getGJChallenges.php', [GameChallengesController::class, 'get'])->name('challenge.get');
    Route::post('/getAccountURL.php', [GameAccountSaveDataController::class, 'getUrl'])->name('account.url.get');
    Route::post('/database/accounts/backupGJAccountNew.php', [GameAccountSaveDataController::class, 'save'])->name('account.data.save');
    Route::post('/database/accounts/syncGJAccountNew.php', [GameAccountSaveDataController::class, 'load'])->name('account.data.load');
    Route::post('/getGJLevels21.php', [GameLevelsController::class, 'search'])->name('level.search');
    Route::post('/downloadGJLevel22.php', [GameLevelsController::class, 'download'])->name('level.download');
    Route::post('/getGJUsers20.php', [GameUsersController::class, 'search'])->name('user.search');
    Route::post('/getGJMapPacks21.php', [GameLevelPacksController::class, 'get'])->name('level.pack.get');
    Route::post('/getGJGauntlets21.php', [GameLevelGauntletsController::class, 'get'])->name('level.gauntlet.get');
    Route::post('/getGJComments21.php', [GameLevelCommentsController::class, 'get'])->name('level.comment.get');
    Route::post('/uploadGJComment21.php', [GameLevelCommentsController::class, 'upload'])->name('level.comment.upload');
    Route::post('/deleteGJComment20.php', [GameLevelCommentsController::class, 'delete'])->name('level.comment.delete');
    Route::post('/getGJDailyLevel.php', [GameLevelsController::class, 'getDaily'])->name('daily.level.get');
    Route::post('/deleteGJLevelUser20.php', [GameLevelsController::class, 'delete'])->name('level.delete');
    Route::post('/requestUserAccess.php', [GameUsersController::class, 'requestAccess'])->name('user.request.access');
    Route::post('/likeGJItem211.php', [GameMiscController::class, 'likeItem'])->name('item.like');
    Route::post('/restoreGJItems.php', [GameMiscController::class, 'restoreItem'])->name('item.restore');
    Route::post('/getGJScores20.php', [GameUserScoresController::class, 'get'])->name('score.get');
    Route::post('/getGJLevelScores211.php', [GameLevelScoresController::class, 'get'])->name('level.score.get');
    Route::post('/getGJCommentHistory.php', [GameLevelCommentsController::class, 'history'])->name('level.comment.history');
    Route::post('/uploadGJMessage20.php', [GameAccountMessagesController::class, 'send'])->name('account.message.send');
    Route::post('/uploadFriendRequest20.php', [GameAccountFriendRequestsController::class, 'upload'])->name('account.friend.request.send');
    Route::post('/deleteGJFriendRequests20.php', [GameAccountFriendRequestsController::class, 'delete'])->name('account.friend.request.delete');
    Route::post('/getGJMessages20.php', [GameAccountMessagesController::class, 'get'])->name('account.message.get');
    Route::post('/deleteGJMessages20.php', [GameAccountMessagesController::class, 'delete'])->name('account.message.delete');
    Route::post('/getGJFriendRequests20.php', [GameAccountFriendRequestsController::class, 'get'])->name('account.friend.request.get');
    Route::post('/readGJFriendRequest20.php', [GameAccountFriendRequestsController::class, 'read'])->name('account.friend.request.read');
    Route::post('/acceptGJFriendRequest20.php', [GameAccountFriendRequestsController::class, 'accept'])->name('account.friend.request.accept');
    Route::post('/downloadGJMessage20.php', [GameAccountMessagesController::class, 'download'])->name('account.message.download');
    Route::post('/getGJUserList20.php', [GameUsersController::class, 'list'])->name('user.list');
    Route::post('/removeGJFriend20.php', [GameAccountFriendsController::class, 'remove'])->name('account.friend.remove');
    Route::post('/blockGJUser20.php', [GameAccountBlocksController::class, 'block'])->name('account.block.block');
    Route::post('/unblockGJUser20.php', [GameAccountBlocksController::class, 'unblock'])->name('account.block.unblock');
    Route::post('/getGJSongInfo.php', [GameSongsController::class, 'get'])->name('song.get');
    Route::post('/getGJTopArtists.php', [GameSongsController::class, 'getTopArtists'])->name('artists.get');
    Route::post('/reportGJLevel.php', [GameLevelsController::class, 'report'])->name('level.report');
    Route::post('/suggestGJStars20.php', [GameLevelRatingController::class, 'suggest'])->name('level.rating.suggest');
    Route::post('/rateGJStars211.php', [GameLevelRatingController::class, 'rate'])->name('level.rating.rate');
    Route::post('/rateGJDemon21.php', [GameLevelRatingController::class, 'demon'])->name('level.rating.demon');
    Route::post('/updateGJDesc20.php', [GameLevelsController::class, 'updateDesc'])->name('level.desc.update');
});
