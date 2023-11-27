<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\User;
use App\Models\Result;
use App\Models\Match;
use App\Models\Arena;
use App\Models\Participant;
use App\Http\Resources\Tournament as TournamentResource;

class ResultController extends Controller
{
    public function createResult(Request $request)
    {
        
        $data = $request->all();
        try {
            // Tentar criar o torneio
            $result = Result::create([
                'match_id' =>  $data['match_id'],
                'arena_id' =>  $data['arena_id'],
                'winner' =>  $data['winner'],
                'p1' => $data['p1'],
                'p2' =>  $data['p2']
         
            ]);
            $arena = Arena::where('id', $result->arena_id)->first();
            $arena->current_match = NULL;
            $people = Participant::where('id', $result->winner)->first();
            $match = Match::where('id', $result->match_id)->first();
            $match->arena_name = 'Concluido';
            $match->arena_id = NULL;
            $match->result = $people->name;
            $match->save();
            $arena->save();

            // Retornar o torneio criado no formato do Resource
            return $result;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar o resultado: ' . $e->getMessage()], 500);
        }
    }


}