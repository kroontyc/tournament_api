<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\Match;
use App\Http\Resources\Tournament as TournamentResource;

class Matchs extends Controller
{
    public function generateTournamentMatch(Request $request)
    {
        $data =$request->all();
        $tournamentId = $data['tournament_id'] ;
    }

   
}