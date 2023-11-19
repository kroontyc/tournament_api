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

        // Use uma coleção temporária para armazenar participantes com 'categorie' e 'name' definidos
        $eligibleParticipants = collect();

        foreach ($participants as $participant) {
            // Verifique se o participante tem um 'name' e 'categorie' definido
            if (!empty($participant->name) && !empty($participant->categorie)) {
                $eligibleParticipants->push($participant);
            }
        }

        // Agora, itere sobre os participantes elegíveis
        while ($eligibleParticipants->count() >= 2) {
        $firstParticipant = $eligibleParticipants->shift();
        
        // Encontre um segundo participante com a mesma 'categorie'
        $secondParticipantKey = $eligibleParticipants->search(function ($participant) use ($firstParticipant) {
            return $participant->categorie == $firstParticipant->categorie;
        });

        // Se não encontrou um par, continue para o próximo participante
        if ($secondParticipantKey === false) {
            continue;
        }

        $secondParticipant = $eligibleParticipants->pull($secondParticipantKey);

        // Crie a partida correspondente
        $matchData = [
            'first_fighter' => $firstParticipant->id,
            'second_fighter' => $secondParticipant->id,
            'first_fighter_brand' => $firstParticipant->team_name ?? 'VAZIO',
            'second_fighter_brand' => $secondParticipant->team_name ?? 'VAZIO',
            'first_fighter_name' => $firstParticipant->name,
            'second_fighter_name' => $secondParticipant->name,
            'first_fighter_categorie' => $firstParticipant->categorie,
            'second_fighter_categorie' => $secondParticipant->categorie,
            'result' => 'VAZIO',
            'score' => 'VAZIO',
            'tournament_id' => $tournamentId,
            'stage' => 0,
            'categorie' => $firstParticipant->categorie,
        ];

        Match::create($matchData);

        // Construa a resposta
        $matchInfo = [
            'first_fighter' => $firstParticipant->id,
            'first_fighter_brand' => $firstParticipant->team_name ?? 'VAZIO',
            'second_fighter' => $secondParticipant->id,
            'second_fighter_brand' => $secondParticipant->team_name ?? 'VAZIO',
            'first_fighter_name' => $firstParticipant->name,
            'second_fighter_name' => $secondParticipant->name,
            'first_fighter_categorie' => $firstParticipant->categorie,
            'second_fighter_categorie' => $secondParticipant->categorie,
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
        $perPage = $request->query('perPage', 20); 
        $page = $request->header('page', 1);
        // Agora, você pode usar $tournamentId para buscar as partidas
        $matches = Match::where('tournament_id', $tournamentId)->paginate($perPage, ['*'], 'page', $page);
        
        return response()->json($matches, 200);
    }
   
}