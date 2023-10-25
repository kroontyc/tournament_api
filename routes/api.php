<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\Participants;
use App\Http\Controllers\Matchs;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('tournament')->group(function () {
    Route::post('/', [TournamentController::class, 'createTournament']);
    Route::get('/owner/{owner_id}', [TournamentController::class, 'getAllTournaments']);
});

Route::prefix('participant')->group(function () {
    Route::post('/', [Participants::class, 'insertParticipantInTorunament']);
    Route::get('/{id}', [Participants::class, 'getTournamentParticipantsByCategorie']);
    Route::post('/file/{id}', [Participants::class, 'insertParticipantsFromXlsx']);
    Route::get('/', [Participants::class, 'getTournamentParticipants']);
    
});

Route::prefix('match')->group(function () {
    Route::post('/', [Matchs::class, 'generateTournamentMatch']);
    Route::get('/{id}', [Matchs::class, 'getTournamentMatchesById']);
});