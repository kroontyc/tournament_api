<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\Participant;
use App\Http\Resources\Tournament as TournamentResource;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Participants extends Controller
{
    public function insertParticipantInTorunament(Request $request)
    {
       $data = $request->all();
       //echo($data['name']);
       $verifyExistsParticipant = Participant::where('name', $data['name'])->where('tournament_id', $data['tournament_id'])->get();
       if(count($verifyExistsParticipant) > 0) {
        return response()->json(['error' => 'Erro ao inserir participante: o participante' . ' ' . $data['name'] . ' ' . 'já esta nesse torneio'], 400);
       }else {
        $participant = Participant::create([
            "name" => $data['name'],
            "age" => $data['age'],
            "weight" => $data['weight'],
            "rank" => $data['rank'],
            "gub" => $data['gub'],
            "sex" => $data['sex'],
            "categorie" => $data['categorie'],
            "team_name" => $data['team_name'],
            "height" => $data['height'],
            "tournament_id" => $data['tournament_id'],
        ]);

        return response()->json(['success' => 'Participante inserido com sucesso!', 'data' => $participant], 200);
       }
    }

    public function getTournamentParticipants(Request $request)
    {
       $data = $request->all();
       //echo($data['name']);
       $verifyExistTournament = Tournament::where('id', $data['tournament_id'])->get();
       //echo $verifyExistTournament;
       if(count($verifyExistTournament) > 0) {
        $participants = Participant::where('tournament_id', $data['tournament_id'])->get();
        return response()->json(['data' => $participants], 200);
       }else {
        return response()->json(['data' => $verifyExistTournament], 200);
       }
    }



    public function insertParticipantsFromXlsx(Request $request, $id)
    {
        // Define o mapeamento de colunas
        $columnMapping = [
            'ATLETA' => 'name',
            'PESO' => 'weight',
            
            'GRADUACAO' => 'gub',
            'SEXO' => 'sex',
            'CATEGORIA' => 'categorie',
            'EQUIPE' => 'team_name',
        ];
        

        // Verifica se um arquivo foi enviado no request
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Verifica se o arquivo é um arquivo válido (opcional)
            if ($file->isValid()) {
                $spreadsheet = IOFactory::load($file->getRealPath());

                // Obtém todas as abas do arquivo XLSX
                $tabs = $spreadsheet->getSheetNames();

                foreach ($tabs as $tab) {
                    $worksheet = $spreadsheet->getSheetByName($tab);

                    foreach ($worksheet->getRowIterator() as $row) {
                        // Ignora a primeira linha, que normalmente contém os cabeçalhos
                        if ($row->getRowIndex() === 1) {
                            $headerRow = $row->getCellIterator();
                            $headerValues = [];
                            foreach ($headerRow as $cell) {
                                $headerValues[] = $cell->getValue();
                            }
                            continue;
                        }

                        // Lê os valores das colunas
                        $rowData = $row->getCellIterator();
                        $data = [];
                        foreach ($rowData as $cell) {
                            $data[] = $cell->getValue();
                        }
                        $participantData = [];
                        foreach ($data as $key => $value) {
                            if (isset($columnMapping[$headerValues[$key]])) {
                                $participantData[$columnMapping[$headerValues[$key]]] = $value;
                            }
                        }
                    
                        // Verifique e defina como 'VAZIO' se o campo estiver vazio
                        $fieldsToCheck = ['weight', 'name', 'gub', 'rank', 'sex', 'categorie', 'team_name'];
                    
                        foreach ($fieldsToCheck as $field) {
                            if (!isset($participantData[$field]) || empty($participantData[$field])) {
                                $participantData[$field] = 'VAZIO';
                            }
                        }
                    
                        // Adicione campos adicionais
                        $participantData['age'] = 0;
                        $participantData['rank'] = 'VAZIO';
                        $participantData['height'] = 'VAZIO';
                        $participantData['tournament_id'] = $id;
                    
                        // Crie o participante com os dados
                        Participant::create($participantData);
                    }
                }

                // Retorne uma resposta de sucesso
                return response()->json(['message' => 'Participantes criados com sucesso'], 200);
            } else {
                return response()->json(['error' => 'Arquivo inválido'], 400);
            }
        } else {
            return response()->json(['error' => 'Nenhum arquivo enviado'], 400);
        }
    }


   
}