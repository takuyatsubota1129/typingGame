<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstantwinController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\WordsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/instantwin/select', [InstantwinController::class, 'select']);
    Route::post('/instantwin/selectTen', [InstantwinController::class, 'selectTen']);
});
// Route::middleware('auth:sanctum')->get('/dashboard', [DashboardController::class, 'index']);

Route::post('/game/join/{stageId}', [GameController::class, 'joinStage']);
Route::post('/game/leave/{stageId}', [GameController::class, 'leaveStage']);

Route::get('/words', [WordsController::class, 'getWords']);
