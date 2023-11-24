<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arena;
class ArenaController extends Controller
{
    //
     public function getAll(Request $request, $id)
    {
        
        $getAll = Arena::where('owner_id', $id)->get();
  
        return response()->json($getAll, 200);
    }

     public function create(Request $request)
    {
        
        $data = $request->all();
        
        $arena = [
            'name'  =>   $data['name'],
            'owner_id'  =>   $data['owner_id'],
            'status' => $data['status'],
           
        ];

        Arena::create($arena);
  
        return response()->json($arena, 200);
    }

}
