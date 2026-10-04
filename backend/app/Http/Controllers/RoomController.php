<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // Lista todos os quartos
    public function index()
    {
        $rooms = Room::all();

        return response()->json($rooms);
    }

    // Cadastra um novo quarto
    public function store(Request $request)
    {
        $dados = $request->validate([
            'hotel_id' => 'required|integer|exists:hotels,id',
            'name' => 'required|string|max:255'
        ]);

        $room = Room::create($dados);

        return response()->json([
            'message' => 'Quarto cadastrado com sucesso.',
            'room' => $room
        ], 201);
    }

    // Busca um quarto pelo ID
    public function show(string $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json([
                'message' => 'Quarto não encontrado.'
            ], 404);
        }

        return response()->json($room);
    }

    // Atualiza um quarto
    public function update(Request $request, string $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json([
                'message' => 'Quarto não encontrado.'
            ], 404);
        }

        $dados = $request->validate([
            'hotel_id' => 'required|integer|exists:hotels,id',
            'name' => 'required|string|max:255'
        ]);

        $room->update($dados);

        return response()->json([
            'message' => 'Quarto atualizado com sucesso.',
            'room' => $room
        ]);
    }

    // Exclui um quarto
    public function destroy(string $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json([
                'message' => 'Quarto não encontrado.'
            ], 404);
        }

        $room->delete();

        return response()->json([
            'message' => 'Quarto excluído com sucesso.'
        ]);
    }
}