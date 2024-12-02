<?php

namespace App\Http\Controllers;

use App\Models\Rank;
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
        if (count($verifyExistsParticipant) > 0) {
            return response()->json(['error' => 'Erro ao inserir participante: o participante' . ' ' . $data['name'] . ' ' . 'já esta nesse torneio'], 400);
        } else {
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

    public function getTournamentParticipants(Request $request, $id)
    {
        $perPage = $request->query('perPage', 20);
        $page = $request->header('page', 1);

        //echo 'asa', $page;

        $verifyExistTournament = Tournament::where('id', $id)->first();
        if ($verifyExistTournament) {
            // Utiliza o método paginate() para obter registros paginados
            $participants = Participant::where('tournament_id', $id)->paginate($perPage, ['*'], 'page', $page);

            return response()->json($participants, 200);
        } else {
            return response()->json(['message' => 'Tournament not found'], 404);
        }
    }

    public function editParticipant(Request $request, $id)
    {

        $participant = Participant::where('id', $id)->first();
        $data = $request->all();

        if ($participant) {
            $participant->name = $data['name'];
            $participant->sex = $data['sex'];
            $participant->age = $data['age'];
            $participant->weight = $data['weight'];
            $participant->height = $data['height'];
            $participant->gub = $data['gub'];
            $participant->team_name = $data['team_name'];
            $participant->save();
            return response()->json($participant, 200);
        } else {
            return response()->json(['message' => 'Tournament not found'], 404);
        }
    }

    public function getOneParticipant(Request $request, $id)
    {

        $participant = Participant::where('id', $id)->first();
        $data = $request->all();

        if ($participant) {

            return response()->json($participant, 200);
        } else {
            return response()->json(['message' => 'Tournament not found'], 404);
        }
    }



    public function insertParticipantsFromXlsx(Request $request, $id)
    {
        // Define the column mapping between the spreadsheet and the database
        $columnMapping = [
            'Nome do Atleta' => 'name',
            'Peso' => 'weight',
            'Graduação' => 'gub',
            'Sexo' => 'sex',
            'Classe' => 'categorie',
            'Estabelecimento' => 'team_name'
        ];

        // Check if a file has been uploaded
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Validate if the file is valid
            if ($file->isValid()) {
                // Load the spreadsheet
                $spreadsheet = IOFactory::load($file->getRealPath());

                // Get the first sheet from the file
                $worksheet = $spreadsheet->getActiveSheet();

                // Initialize variables for reading headers and data
                $headerValues = [];

                foreach ($worksheet->getRowIterator() as $row) {
                    // Read the header row (first row)
                    if ($row->getRowIndex() === 1) {
                        foreach ($row->getCellIterator() as $cell) {
                            $headerValues[] = $cell->getValue();
                        }
                        continue;
                    }

                    // Read the data rows
                    $rowData = [];
                    foreach ($row->getCellIterator() as $cell) {
                        $rowData[] = $cell->getValue();
                    }

                    // Map the data to the participant array using the column mapping
                    $participantData = [];
                    foreach ($rowData as $key => $value) {
                        if (isset($columnMapping[$headerValues[$key]])) {
                            $participantData[$columnMapping[$headerValues[$key]]] = $value;
                        }
                    }

                    // Ensure all required fields are filled with 'VAZIO' if missing
                    $fieldsToCheck = ['name', 'weight', 'gub', 'sex', 'categorie', 'team_name'];
                    foreach ($fieldsToCheck as $field) {
                        if (empty($participantData[$field])) {
                            $participantData[$field] = 'VAZIO';
                        }
                    }

                    // Additional fields
                    $participantData['age'] = 0; // Age field as 'VAZIO' or 0
                    $participantData['rank'] = 'VAZIO'; // Rank as 'VAZIO'
                    $participantData['height'] = 'VAZIO'; // Height as 'VAZIO'
                    $participantData['tournament_id'] = $id; // Assign tournament ID from the request parameter

                    // Insert the participant into the database
                    Participant::create($participantData);
                    $existingRank = Rank::where('name', $participantData['team_name'])->first();
                    if (!$existingRank) {
                        // Insert a new rank entry if it doesn't exist
                        Rank::create([
                            'name' => $participantData['team_name'],
                            'points' => 0,
                            'tournament_id' => $id
                        ]);
                    }
                }

                // Return success response
                return response()->json(['message' => 'Participantes criados com sucesso'], 200);
            } else {
                return response()->json(['error' => 'Arquivo inválido'], 400);
            }
        } else {
            return response()->json(['error' => 'Nenhum arquivo enviado'], 400);
        }
    }
}
