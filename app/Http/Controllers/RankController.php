<?php
// RankController.php

namespace App\Http\Controllers;

use App\Models\Rank;
use Illuminate\Http\Request;

class RankController extends Controller
{
    /**
     * Retorna todos os rankings para um determinado tournament_id.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRanksByTournament($id)
    {
        $ranks = Rank::where('tournament_id', $id)->get();

        return response()->json($ranks);
    }

    /**
     * Atualiza um ranking específico.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRanking(Request $request, $id)
    {
        $rank = Rank::find($id);

        if (!$rank) {
            return response()->json(['message' => 'Ranking not found'], 404);
        }

        $rank->name = $request->input('name');
        $rank->points = $request->input('points');
        $rank->save();

        return response()->json($rank);
    }

    public function deleteRanking(Request $request)
    {
        $ranking_id = $request->input('ranking_id');
        $tournament_id = $request->input('tournament_id');
    
        // Verifica se o ranking existe e pertence ao torneio especificado
        $rank = Rank::where('id', $ranking_id)
                    ->where('tournament_id', $tournament_id)
                    ->first();
    
        if (!$rank) {
            return response()->json(['error' => 'Ranking not found or does not belong to the specified tournament'], 404);
        }
    
        $rank->delete();
    
        return response()->json(['message' => 'Ranking deleted successfully'], 200);
    }

    public function createRanking(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string',
        'points' => 'required|integer',
        'tournament_id' => 'required|integer|exists:tournaments,id',
    ]);

    $rank = Rank::create($validated);

    return response()->json($rank, 201);
}
}
