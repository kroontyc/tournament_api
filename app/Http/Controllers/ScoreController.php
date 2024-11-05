<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    // Fetch all scores
    public function index()
    {
        $scores = Score::all();
        return response()->json($scores);
    }

    // Fetch a specific score by ID
    public function show($id)
    {
        $score = Score::find($id);

        if ($score) {
            return response()->json($score);
        } else {
            return response()->json(['message' => 'Score not found'], 404);
        }
    }

    // Update a specific score by ID
    public function update(Request $request, $id)
    {
        $score = Score::find($id);

        if ($score) {
            $score->update($request->only(['name', 'points', 'status']));
            return response()->json(['message' => 'Score updated successfully', 'score' => $score]);
        } else {
            return response()->json(['message' => 'Score not found'], 404);
        }
    }
}
