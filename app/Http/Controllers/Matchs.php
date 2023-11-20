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
    
        $existingMatch = Match::where('tournament_id', $tournamentId)->first();
        if ($existingMatch) {
            return response()->json(['message' => 'Já existe um match para este torneio.'], 200);
        }
    
        $participants = $participants->shuffle();
        $eligibleParticipants = collect();
        foreach ($participants as $participant) {
            if (!empty($participant->name) && !empty($participant->categorie) && isset($participant->sex) && isset($participant->weight)) {
                $eligibleParticipants->push($participant);
            }
        }
    
        $matches = [];
        while ($eligibleParticipants->count() >= 2) {
            $firstParticipant = $eligibleParticipants->shift();
    
            $secondParticipantKey = $eligibleParticipants->search(function ($participant) use ($firstParticipant) {
                return $participant->categorie == $firstParticipant->categorie &&
                       $participant->sex == $firstParticipant->sex &&
                       $participant->weight == $firstParticipant->weight &&
                       $participant->team_name != $firstParticipant->team_name;
            });
    
            if ($secondParticipantKey === false) {
                $secondParticipantKey = $eligibleParticipants->search(function ($participant) use ($firstParticipant) {
                    return $participant->categorie == $firstParticipant->categorie &&
                           $participant->sex == $firstParticipant->sex &&
                           $participant->weight == $firstParticipant->weight;
                });
            }
    
            if ($secondParticipantKey !== false) {
                $secondParticipant = $eligibleParticipants->pull($secondParticipantKey);
                $matchData = $this->createMatchData($firstParticipant, $secondParticipant, $tournamentId);
                Match::create($matchData);
                $matches[] = $this->buildMatchInfo($matchData);
            }
        }
    
        return response()->json($matches, 200);
    }
    
    private function createMatchData($firstParticipant, $secondParticipant, $tournamentId)
    {
        return [
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
    }
    
    private function buildMatchInfo($matchData)
    {
        return [
            'first_fighter' => $matchData['first_fighter'],
            'first_fighter_brand' => $matchData['first_fighter_brand'],
            'second_fighter' => $matchData['second_fighter'],
            'second_fighter_brand' => $matchData['second_fighter_brand'],
            'first_fighter_name' => $matchData['first_fighter_name'],
            'second_fighter_name' => $matchData['second_fighter_name'],
            'first_fighter_categorie' => $matchData['first_fighter_categorie'],
            'second_fighter_categorie' => $matchData['second_fighter_categorie'],
            'result' => $matchData['result'],
            'score' => $matchData['score'],
            'stage' => $matchData['stage'],
        ];
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