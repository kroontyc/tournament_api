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

    // Obtendo todas as categorias que correspondem ao owner_id
    $getAllCategories = Categorie::where('owner_id', $id)->get();

    // Obtendo todos os participantes que correspondem ao tournament_id
    $allCategoriesParticipants = Participant::where('tournament_id', $tournament_id)->get();

    // Criando um array para armazenar os nomes já adicionados
    $addedNames = [];

    // Criando um novo array para armazenar os participantes formatados e as categorias
    $combinedData = [];

    // Adicionando as categorias ao array combinado
    foreach ($getAllCategories as $category) {
        $combinedData[] = $category;
    }

    // Usando foreach para formatar e adicionar cada participante ao array combinado
    foreach ($allCategoriesParticipants as $participant) {
        $categoryName = $participant->categorie != 'vazio' ? $participant->categorie : 'vazio';

        // Verificando se o nome da categoria já foi adicionado
        if (!in_array($categoryName, $addedNames)) {
            $combinedData[] = [
                "id" => $participant->id,
                "min_weight" => $participant->weight != 'vazio' ? $participant->weight : "VAZIO",
                "max_weight" => $participant->weight != 'vazio' ? $participant->weight : "VAZIO",
                "height" => $participant->weight != 'vazio' ? $participant->weight : "VAZIO",
                "name" => $categoryName,
                "status" => "1",
                "owner_id" => $id,
                "ruler" => $participant->weight != 'vazio' ? $participant->weight : "VAZIO",
            ];

            // Adicionando o nome da categoria à lista de nomes já adicionados
            $addedNames[] = $categoryName;
        }
    }

    return response()->json($combinedData, 200);
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
