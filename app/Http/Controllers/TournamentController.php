<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Http\Resources\Tournament as TournamentResource;

class TournamentController extends Controller
{
    public function createTournament(Request $request)
    {
        

        try {
            // Tentar criar o torneio
            $tournament = Tournament::create([
                'owner_id' => $request->owner_id,
                'name' => $request->name,
                'data' => $request->data,
                'reward' => $request->reward,
            ]);

            // Retornar o torneio criado no formato do Resource
            return new TournamentResource($tournament);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar o torneio: ' . $e->getMessage()], 500);
        }
    }

    public function getAllTournaments($owner_id)
    {
        try {
            $tournaments = Tournament::where('owner_id', $owner_id)->get();
            return TournamentResource::collection($tournaments);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter os torneios: ' . $e->getMessage()], 500);
        }
    }
}