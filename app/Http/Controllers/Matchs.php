<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\Match;
use App\Models\Arena;
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
    // Construindo o nome do match
    $matchName = $firstParticipant->categorie . ' ' . strtoupper($firstParticipant->sex);
    if (isset($firstParticipant->gub)) {
        $matchName .= ' ' . $firstParticipant->gub;
    }
    if (isset($firstParticipant->weight)) {
        $matchName .= ' até ' . $firstParticipant->weight ;
    }

    // Configurando dados do match
    return [
        'tournament_id' => $tournamentId,
        'first_fighter' => $firstParticipant->id,
        'second_fighter' => $secondParticipant->id,
        'first_fighter_name' => $firstParticipant->name,
        'second_fighter_name' => $secondParticipant->name,
        'first_fighter_categorie' => $firstParticipant->categorie,
        'second_fighter_categorie' => $secondParticipant->categorie,
        'first_fighter_sex' => $firstParticipant->sex,
        'second_fighter_sex' => $secondParticipant->sex,
        'first_fighter_weight' => $firstParticipant->weight,
        'second_fighter_weight' => $secondParticipant->weight,
        'name' => $matchName,
        'first_fighter_brand' => $firstParticipant->team_name ?? 'VAZIO',
        'second_fighter_brand' => $secondParticipant->team_name ?? 'VAZIO',
        // Adicione aqui outros campos necessários
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
        'result' => $matchData['result'] ?? 'VAZIO',
        'score' => $matchData['score'] ?? 'VAZIO',
        'stage' => $matchData['stage'] ?? 'VAZIO',
        // Adicione aqui outros campos necessários
    ];
}
    


   public function getTournamentMatchesById(Request $request, $id)
      {
    // Use o parâmetro $id para obter o tournament_id
    $tournamentId = $id;
    $perPage = $request->query('perPage', 20);
    $page = $request->header('page', 1);
    
    // Normalizar os nomes durante a consulta
    $matches = Match::selectRaw("*, LOWER(REPLACE(name, ' ', '')) as normalized_name")
        ->where('tournament_id', $tournamentId)
        ->get()
        ->groupBy('normalized_name');  // Agrupar com base no nome normalizado

    // Use paginate manualmente já que o groupBy não funciona com ele diretamente
    $total = $matches->count();
    $matches = $matches->slice(($page - 1) * $perPage)->take($perPage);

    // Construa a resposta, mantendo a estrutura de paginação
    $response = [
        'data' => $matches,
        'current_page' => $page,
        'per_page' => $perPage,
        'total' => $total,
    ];

    return response()->json($response, 200);
}

    public function createNewMatch(Request $request, $id)
    {
        $data = $request->all();
        $arena = $id;
        $currentArena = Arena::where('id', $arena)->first();
        if( $currentArena) {
            $currentArena->current_match =  $data['match_id'];
            $currentArena->fighter_1 =  $data['fighter_1'];
            $currentArena->fighter_2 =  $data['fighter_2'];
         
            $match = Match::where('id', $data['match_id'])->first();
            $match->arena_id = $currentArena->id;
            $currentArena->first_fighter_name =  $match->first_fighter_name;
            $currentArena->second_fighter_name =  $match->second_fighter_name;
            $match->arena_name = $currentArena->name;
            $match->save();
            $currentArena->save();

        }
        return response()->json(['match' => $currentArena], 200);
    }
   
}