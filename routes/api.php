<?php

use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\TicketController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/ticket/purchase', [TicketController::class, 'purchase']);
    Route::post('/ticket/goOnSale', [TicketController::class, 'toggleGoingOnSale']);
    Route::post('/ticket/unhide', [TicketController::class, 'toggleShow']);

    Route::post('/ticket/checkin', [TicketController::class, 'attend']);
    Route::post('/ticket/cancel', [TicketController::class, 'cancel']);

    Route::post('/event/follow', [EventController::class, 'toggleFollowing']);
});

Route::post('/register', [RegisterController::class, 'create']);
Route::post('/login', [LoginController::class, 'perform']);

Route::apiResource('events', EventController::class);
//Route::get('/event/{event}/followers', [EventController::class, 'followers']);


Route::apiResource('tickets', TicketController::class);

Route::apiResource('profile', ProfileController::class);

Route::get('test', [ProfileController::class, 'test']);

Route::get('/event/test/{event}', function (\App\Models\Event $event) {
    return new \App\Http\Resources\EventResource($event);
});

