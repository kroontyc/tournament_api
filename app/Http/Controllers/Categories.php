<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;
class categories extends Controller
{
    //
    public function getAll(Request $request, $id)
    {
        $getAll = Categorie::where('ower_id', $id)->get();
  
        return response()->json($getAll, 200);
    }
}
