<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\Match;
use App\Models\Arena;
use App\Models\Categorie;
use App\Models\Participant;
use App\Http\Resources\Tournament as TournamentResource;

class Matchs extends Controller
{
    public function generateTournamentMatch(Request $request)
    {
        $data = $request->all();
        $tournamentId = $data['tournament_id'];
    
        // Get all participants for the tournament
        $participants = Participant::where('tournament_id', $tournamentId)->get();
    
        if ($participants->isEmpty()) {
            return response()->json(['message' => 'Nenhum participante encontrado para este torneio.'], 404);
        }
    
        $groupedCategories = [];
    
        // Iterate over each participant to determine their category, weight range, and sex
        foreach ($participants as $participant) {
            $participantCategory = $participant->categorie;
            $participantWeight = floatval(str_replace(['kg', '+', '-'], '', $participant->weight));
            $participantSex = $participant->sex;
    
            // Find a matching category where the name contains the participant's category keyword
            $category = Categorie::where('name', 'LIKE', '%' . $participantCategory . '%')->first();
    
            if (!$category) {
                continue; // Skip if no matching category is found
            }
    
            // Decode the ruler JSON to get weight ranges
            $ruler = json_decode($category->ruler, true);
            $matchedRuler = null;
    
            // Find the matching weight range for the participant
            foreach ($ruler as $range) {
                // Extract the weight limits from the range string (e.g., "De 58.1kg até 68kg")
                preg_match('/(\d+(\.\d+)?)kg/', $range, $matches);
                $lowerLimit = isset($matches[1]) ? floatval($matches[1]) : 0;
    
                preg_match('/até (\d+(\.\d+)?)kg/', $range, $matches);
                $upperLimit = isset($matches[1]) ? floatval($matches[1]) : PHP_INT_MAX;
    
                // Check if the participant's weight falls within the range (inclusive of upper limit)
                if ($participantWeight >= $lowerLimit && $participantWeight <= $upperLimit) {
                    $matchedRuler = $range;
                    break;
                }
            }
    
            if (!$matchedRuler) {
                continue; // Skip if no matching ruler is found
            }
    
            // Format the category key with sex
            $categoryKey = $participantSex . ' - Categoria: ' . $category->name . ' - ' . $matchedRuler;
    
            // Group participants by category and sex
            if (!isset($groupedCategories[$categoryKey])) {
                $groupedCategories[$categoryKey] = [
                    'category' => $category->name,
                    'ruler' => $matchedRuler,
                    'sex' => $participantSex,
                    'participants' => []
                ];
            }
    
            // Add the participant to the respective group
            $groupedCategories[$categoryKey]['participants'][] = [
                'name' => $participant->name,
                'weight' => $participant->weight,
                'categorie' => $participant->categorie,
                'sex' => $participantSex
            ];
        }
    
        // Prepare the final response
        $response = array_values($groupedCategories);
    
        return response()->json($response);
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
     
    
        // Get all participants for the tournament
        $participants = Participant::where('tournament_id', $id)->get();
    
        if ($participants->isEmpty()) {
            return response()->json(['message' => 'Nenhum participante encontrado para este torneio.'], 404);
        }

        $existMatch = Match::where('tournament_id', $id)->first();

        if($existMatch) {
            return response()->json($existMatch);
        }
    
        $groupedCategories = [];
    
        // Iterate over each participant to determine their category, weight range, and sex
        foreach ($participants as $participant) {
            $participantCategory = $participant->categorie;
            $participantWeight = floatval(str_replace(['kg', '+', '-'], '', $participant->weight));
            $participantSex = $participant->sex;
    
            // Find a matching category where the name contains the participant's category keyword
            $category = Categorie::where('name', 'LIKE', '%' . $participantCategory . '%')->first();
    
            if (!$category) {
                continue; // Skip if no matching category is found
            }
    
            // Decode the ruler JSON to get weight ranges
            $ruler = json_decode($category->ruler, true);
            $matchedRuler = null;
    
            // Find the matching weight range for the participant
            foreach ($ruler as $range) {
                // Extract the weight limits from the range string (e.g., "De 58.1kg até 68kg")
                preg_match('/(\d+(\.\d+)?)kg/', $range, $matches);
                $lowerLimit = isset($matches[1]) ? floatval($matches[1]) : 0;
    
                preg_match('/até (\d+(\.\d+)?)kg/', $range, $matches);
                $upperLimit = isset($matches[1]) ? floatval($matches[1]) : PHP_INT_MAX;
    
                // Check if the participant's weight falls within the range (inclusive of upper limit)
                if ($participantWeight >= $lowerLimit && $participantWeight <= $upperLimit) {
                    $matchedRuler = $range;
                    break;
                }
            }
    
            if (!$matchedRuler) {
                continue; // Skip if no matching ruler is found
            }
    
            // Format the category key with sex
            $categoryKey = $participantSex . ' - Categoria: ' . $category->name . ' - ' . $matchedRuler;
    
            // Group participants by category and sex
            if (!isset($groupedCategories[$categoryKey])) {
                $groupedCategories[$categoryKey] = [
                    'category' => $category->name,
                    'ruler' => $matchedRuler,
                    'sex' => $participantSex,
                    'participants' => []
                ];
            }
    
            // Add the participant to the respective group
            $groupedCategories[$categoryKey]['participants'][] = [
                'name' => $participant->name,
                'weight' => $participant->weight,
                'categorie' => $participant->categorie,
                'team' => $participant->team_name,
                'sex' => $participantSex
            ];
        }
    
        // Prepare the final response
        $response = array_values($groupedCategories);
    
        return response()->json($response);
    }

    public function createNewMatch(Request $request, $id)
    {
        $request->validate([
            'json_match' => 'required',
        ]);
    
        // Procura o match pelo ID e cria um novo se não existir
        $match = Match::updateOrCreate(
            ['tournament_id' => $id], // Condição para encontrar o registro
            ['json_match' => $request->json_match] // Dados para atualização/criação
        );
    
        return response()->json(['match' => $match], 200);
    }
    
}