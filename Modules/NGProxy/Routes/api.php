<?php

use Modules\NGProxy\Http\Controllers\NGProxyController;

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

Route::get('/info/{songID}', [NGProxyController::class, 'getInfo']);
Route::get('/object/{songID}', [NGProxyController::class, 'getObject']);
Route::get('/download', [NGProxyController::class, 'download'])->middleware(['signed'])->name('song.download');
Route::post('/activeCode/{userID}/{code}', [NGProxyController::class, 'activeCode']);
