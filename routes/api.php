<?php

use App\Http\Controllers\Admin\AdminReservationController ;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\DrinkController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\ThirdPartyApiController;
use App\Http\Controllers\ReservationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', RegisterUserController::class);
Route::post('login', LoginUserController::class);

Route::apiResource('drinks', DrinkController::class);
Route::get('meals/random', [MealController::class, 'aRandomMeal']);

Route::post('reservation', [ReservationController::class, 'store']);//store the reservation time

Route::group(['prefix' => 'admin', ], function(){
    Route::apiResource('reservations', AdminReservationController::class );
});