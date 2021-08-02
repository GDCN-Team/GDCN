<?php

use Modules\GDProxy\Http\Controllers\GDProxyApiController;

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

Route::get('/traffics', [GDProxyApiController::class, 'getTraffics']);
Route::get('/get_ngproxy_binded_account', [GDProxyApiController::class, 'getNGProxyBindedAccount']);
