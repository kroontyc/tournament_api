<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;
use App\Models\Participant;
use App\Models\Tournament;
class Categories extends Controller
{
    //
  public function getAll(Request $request, $id)
  {
    $tournament_id = $request->header('tournament');
    $getAllCategories = Categorie::where('owner_id', $id)->get()->map(function ($category) {
        // Decodifica o campo 'ruler' que está armazenado como JSON no banco de dados
        $category->ruler = json_decode($category->ruler);
        return $category;
    });

    return response()->json($getAllCategories, 200);
  }
  public function create(Request $request)
  {
      
      $data = $request->all();
      
      $categorie = [
          'min_weight'  =>   $data['min_weight'] ?? 'VAZIO',
          'max_weight'  =>   $data['max_weight'] ?? 'VAZIO',
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
