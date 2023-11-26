<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\User;
use App\Models\Result;
use App\Http\Resources\Tournament as TournamentResource;

class Results extends Controller
{
    public function createResult(Request $request)
    {
        
        $data = $request->all();
        try {
            // Tentar criar o torneio
            $result = Tournament::create([
                'match_id' =>  $data['match_id'],
                'arena_id' =>  $data['arena_id'],
                'winner' =>  $data['winner'],
                'p1' => $data['p1'],
                'p2' =>  $data['p2']
         
            ]);

            // Retornar o torneio criado no formato do Resource
            return $result;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar o resultado: ' . $e->getMessage()], 500);
        }
    }


}