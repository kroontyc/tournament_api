<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;

class Categories extends Controller
{
    //
    public function getAll(Request $request, $id)
    {
        
        $getAll = Categorie::where('owner_id', $id)->get();
  
        return response()->json($getAll, 200);
    }

    public function create(Request $request)
    {
        
        $data = $request->all();
        
        $categorie = [
            'min_weight'  =>   $data['min_weight'],
            'max_weight'  =>   $data['max_weight'],
            'height' => 'VAZIO',
            'name' => $data['name'],
            'ruler' => $data['ruler'],
            'status' => 1,
            'owner_id' => $data['owner_id']
        ];

        Categorie::create($categorie);
  
        return response()->json($categorie, 200);
    }
}
