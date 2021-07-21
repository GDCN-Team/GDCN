<?php

/*
 * Copyright (c) 2020. WOSHIZHAZHA120 & GDCN Team
 */


use App\Http\Controllers\Game\Account\BlocksController;
use App\Http\Controllers\Game\Account\FriendRequestsController;
use App\Http\Controllers\Game\Account\FriendsController;
use App\Http\Controllers\Game\Account\MessagesController;
use App\Http\Controllers\Game\Account\SaveDataController;
use App\Http\Controllers\Game\Account\SettingsController;
use App\Http\Controllers\Game\AccountsController;
use App\Http\Controllers\Game\ChallengesController;
use App\Http\Controllers\Game\Level\CommentsController;
use App\Http\Controllers\Game\Level\GauntletsController;
use App\Http\Controllers\Game\Level\PacksController;
use App\Http\Controllers\Game\Level\RatingController;
use App\Http\Controllers\Game\Level\ScoresController;
use App\Http\Controllers\Game\LevelsController;
use App\Http\Controllers\Game\MiscController;
use App\Http\Controllers\Game\RewardsController;
use App\Http\Controllers\Game\SongsController;
use App\Http\Controllers\Game\UsersController;
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
    Route::post('/updateGJUserScore22.php', [ScoresController::class, 'update'])->name('user.score.update');
    Route::post('/getGJUserInfo20.php', [UsersController::class, 'info'])->name('user.info');
    Route::post('/getGJAccountComments20.php', [CommentsController::class, 'get'])->name('account.comment.list');
    Route::post('/uploadGJAccComment20.php', [CommentsController::class, 'upload'])->name('account.comment.upload');
    Route::post('/deleteGJAccComment20.php', [CommentsController::class, 'delete'])->name('account.comment.delete');
    Route::post('/updateGJAccSettings20.php', [SettingsController::class, 'update'])->name('account.setting.update');
    Route::post('/getGJRewards.php', [RewardsController::class, 'get'])->name('reward.get');
    Route::post('/uploadGJLevel21.php', [LevelsController::class, 'upload'])->name('level.upload');
    Route::post('/getGJChallenges.php', [ChallengesController::class, 'get'])->name('challenge.get');
    Route::post('/getAccountURL.php', [SaveDataController::class, 'getUrl'])->name('account.url.get');
    Route::post('/database/accounts/backupGJAccountNew.php', [SaveDataController::class, 'save'])->name('account.data.save');
    Route::post('/database/accounts/syncGJAccountNew.php', [SaveDataController::class, 'load'])->name('account.data.load');
    Route::post('/getGJLevels21.php', [LevelsController::class, 'search'])->name('level.search');
    Route::post('/downloadGJLevel22.php', [LevelsController::class, 'download'])->name('level.download');
    Route::post('/getGJUsers20.php', [UsersController::class, 'search'])->name('user.search');
    Route::post('/getGJMapPacks21.php', [PacksController::class, 'get'])->name('level.pack.get');
    Route::post('/getGJGauntlets21.php', [GauntletsController::class, 'get'])->name('level.gauntlet.get');
    Route::post('/getGJComments21.php', [CommentsController::class, 'get'])->name('level.comment.get');
    Route::post('/uploadGJComment21.php', [CommentsController::class, 'upload'])->name('level.comment.upload');
    Route::post('/deleteGJComment20.php', [CommentsController::class, 'delete'])->name('level.comment.delete');
    Route::post('/getGJDailyLevel.php', [LevelsController::class, 'getDaily'])->name('daily.level.get');
    Route::post('/deleteGJLevelUser20.php', [LevelsController::class, 'delete'])->name('level.delete');
    Route::post('/requestUserAccess.php', [UsersController::class, 'requestAccess'])->name('user.request.access');
    Route::post('/likeGJItem211.php', [MiscController::class, 'likeItem'])->name('item.like');
    Route::post('/restoreGJItems.php', [MiscController::class, 'restoreItem'])->name('item.restore');
    Route::post('/getGJScores20.php', [ScoresController::class, 'get'])->name('score.get');
    Route::post('/getGJLevelScores211.php', [ScoresController::class, 'get'])->name('level.score.get');
    Route::post('/getGJCommentHistory.php', [CommentsController::class, 'history'])->name('level.comment.history');
    Route::post('/uploadGJMessage20.php', [MessagesController::class, 'send'])->name('account.message.send');
    Route::post('/uploadFriendRequest20.php', [FriendRequestsController::class, 'upload'])->name('account.friend.request.send');
    Route::post('/deleteGJFriendRequests20.php', [FriendRequestsController::class, 'delete'])->name('account.friend.request.delete');
    Route::post('/getGJMessages20.php', [MessagesController::class, 'get'])->name('account.message.get');
    Route::post('/deleteGJMessages20.php', [MessagesController::class, 'delete'])->name('account.message.delete');
    Route::post('/getGJFriendRequests20.php', [FriendRequestsController::class, 'get'])->name('account.friend.request.get');
    Route::post('/readGJFriendRequest20.php', [FriendRequestsController::class, 'read'])->name('account.friend.request.read');
    Route::post('/acceptGJFriendRequest20.php', [FriendRequestsController::class, 'accept'])->name('account.friend.request.accept');
    Route::post('/downloadGJMessage20.php', [MessagesController::class, 'download'])->name('account.message.download');
    Route::post('/getGJUserList20.php', [UsersController::class, 'list'])->name('user.list');
    Route::post('/removeGJFriend20.php', [FriendsController::class, 'remove'])->name('account.friend.remove');
    Route::post('/blockGJUser20.php', [BlocksController::class, 'block'])->name('account.block.block');
    Route::post('/unblockGJUser20.php', [BlocksController::class, 'unblock'])->name('account.block.unblock');
    Route::post('/getGJSongInfo.php', [SongsController::class, 'get'])->name('song.get');
    Route::post('/getGJTopArtists.php', [SongsController::class, 'getTopArtists'])->name('artists.get');
    Route::post('/reportGJLevel.php', [LevelsController::class, 'report'])->name('level.report');
    Route::post('/suggestGJStars20.php', [RatingController::class, 'suggest'])->name('level.rating.suggest');
    Route::post('/rateGJStars211.php', [RatingController::class, 'rate'])->name('level.rating.rate');
    Route::post('/rateGJDemon21.php', [RatingController::class, 'demon'])->name('level.rating.demon');
    Route::post('/updateGJDesc20.php', [LevelsController::class, 'updateDesc'])->name('level.desc.update');
});
