<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\Match;
use App\Models\Participant;
use App\Http\Resources\Tournament as TournamentResource;

class Matchs extends Controller
{
    public function generateTournamentMatch(Request $request)
    {
        $data = $request->all();
        $tournamentId = $data['tournament_id'];
        $participants = Participant::where('tournament_id', $tournamentId)->get();
    
        // Verifique se já existe um match com o mesmo 'tournament_id'
        $existingMatch = Match::where('tournament_id', $tournamentId)->first();
    
        if ($existingMatch) {
            // Já existe um match com o mesmo 'tournament_id', retorne uma mensagem
            return response()->json(['message' => 'Já existe um match para este torneio.'], 200);
        }
    
        // Vamos criar as chaves de luta e preencher a tabela Match
        $matches = [];
    
        // Garanta que a lista de participantes esteja em uma ordem aleatória para criar chaves aleatórias
        $participants = $participants->shuffle();
    
        while ($participants->count() >= 2) {
            $firstParticipant = $participants->shift();
            $secondParticipant = $participants->shift();
    
            // Crie a partida correspondente
            $matchData = [
                'first_fighter' => $firstParticipant->id,
                'second_fighter' => $secondParticipant->id,
                'result' => 'VAZIO',
                'score' => 'VAZIO',
                'tournament_id' => $tournamentId,
                'stage' => 0,
                'categorie' => $firstParticipant->categorie, // Adicionando a categoria
            ];
    
            Match::create($matchData);
    
            // Construa a resposta
            $matchInfo = [
                'first_fighter' => $firstParticipant->id,
                'first_fighter_brand' => $firstParticipant->team_name,
                'second_fighter' => $secondParticipant->id,
                'second_fighter_brand' => $secondParticipant->team_name,
                'first_fighter_name' => $firstParticipant->name,
                'second_fighter_name' => $secondParticipant->name,
                'result' => $matchData['result'],
                'score' => $matchData['score'],
                'stage' => $matchData['stage'],
            ];
    
            // Adicione esta luta à lista de lutas
            $matches[] = $matchInfo;
        }
    
        // Agora, $matches contém as informações sobre as lutas criadas e as correspondências na tabela Match
    
        return response()->json($matches, 200);
    }
    public function getTournamentMatchesById(Request $request, $id)
    {
        // Use o parâmetro $id para obter o tournament_id
        $tournamentId = $id;
        
        // Agora, você pode usar $tournamentId para buscar as partidas
        $matches = Match::where('tournament_id', $tournamentId)->get();
        
        return response()->json($matches, 200);
    }
   
}