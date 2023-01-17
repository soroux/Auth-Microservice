<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
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

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('login/classic',[AuthController::class,'classicLogin']);
    Route::post('login/SSO/SendCod',[AuthController::class,'SSOSendCode']);
    Route::post('login/SSO/verify',[AuthController::class,'SSOLogin']);
    Route::get('login/provider', [AuthController::class,'redirectToProvider']);
    Route::get('login/provider/callback', [AuthController::class,'ProviderCallBackLogin']);

});
Route::apiResource('services', ServiceController::class);
Route::apiResource('types', TypeController::class);
Route::apiResource('users', UserController::class);
Route::post('services/addType',[ServiceController::class,'addType']);
Route::post('services/removeType',[ServiceController::class,'removeType']);
Route::post('register',[AuthController::class,'register']);



