<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\Participants;
use App\Http\Controllers\Matchs;
use App\Http\Controllers\Categories;
use App\Http\Controllers\ArenaController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('tournament')->group(function () {
    Route::post('/', [TournamentController::class, 'createTournament']);
    Route::get('/owner/{owner_id}', [TournamentController::class, 'getAllTournaments']);
    Route::get('/', [TournamentController::class, 'getAll']);
    Route::get('/{id}', [TournamentController::class, 'byId']);
});

Route::prefix('participant')->group(function () {
    Route::post('/', [Participants::class, 'insertParticipantInTorunament']);
    Route::post('/{id}', [Participants::class, 'editParticipant']);
    Route::get('/{id}', [Participants::class, 'getTournamentParticipantsByCategorie']);
     Route::get('/people/{id}', [Participants::class, 'getOneParticipant']);
    Route::post('/file/{id}', [Participants::class, 'insertParticipantsFromXlsx']);
    Route::get('/peoples/{id}', [Participants::class, 'getTournamentParticipants']);
    
});

Route::prefix('ranking')->group(function () {
    Route::get('/{id}', [RankController::class, 'getRanksByTournament']);
    Route::put('/{id}', [RankController::class, 'updateRanking']);
    Route::post('/ranking', [RankController::class, 'deleteRanking']);
    Route::post('/', [RankController::class, 'createRanking']);
});

Route::prefix('match')->group(function () {
    Route::post('/', [Matchs::class, 'generateTournamentMatch']);
    Route::get('/{id}', [Matchs::class, 'getTournamentMatchesById']);
    Route::post('/{id}', [Matchs::class, 'createNewMatch']);
});

Route::post('/save/match', [Matchs::class, 'save_matches']);
Route::post('recovery/match', [Matchs::class, 'getRecoveryMatch'])->middleware('throttle:unlimited');;

Route::prefix('categories')->group(function () {
    Route::post('/', [Categories::class, 'create']);
    Route::get('/{id}', [Categories::class, 'getAll']);
});

Route::prefix('arenas')->group(function () {
    Route::post('/', [ArenaController::class, 'create']);
    Route::get('/{id}', [ArenaController::class, 'getAll']);
});

Route::prefix('result')->group(function () {
    Route::post('/', [ResultController::class, 'createResult']);
});

Route::get('/scores', [ScoreController::class, 'index']); // Fetch all scores
Route::get('/scores/{id}', [ScoreController::class, 'show']); // Fetch a specific score
Route::put('/scores/{id}', [ScoreController::class, 'update']); // Update a specific scoree

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
