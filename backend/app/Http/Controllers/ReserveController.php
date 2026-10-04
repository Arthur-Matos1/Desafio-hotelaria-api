<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReserveController extends Controller
{
    public function store(Request $request)
    {
        $dados = $request->validate([
            'hotel_id' => 'required|integer|exists:hotels,id',
            'room_id' => 'required|integer|exists:rooms,id',

            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',

            'total' => 'required|numeric|min:0',

            'guests' => 'required|array|min:1',
            'guests.*.name' => 'required|string|max:100',
            'guests.*.last_name' => 'required|string|max:100',
            'guests.*.phone' => 'required|string|max:30',

            'dailies' => 'required|array|min:1',
            'dailies.*.date' => 'required|date',
            'dailies.*.value' => 'required|numeric|min:0',

            'payments' => 'nullable|array',
            'payments.*.method' => 'required|integer',
            'payments.*.value' => 'required|numeric|min:0'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Verifica se o quarto realmente pertence ao hotel informado
        |--------------------------------------------------------------------------
        */

        $quartoValido = DB::table('rooms')
            ->where('id', $dados['room_id'])
            ->where('hotel_id', $dados['hotel_id'])
            ->exists();

        if (!$quartoValido) {
            return response()->json([
                'message' => 'O quarto informado não pertence ao hotel selecionado.'
            ], 422);
        }

        try {

            $reservaId = DB::transaction(function () use ($dados) {

                /*
                |--------------------------------------------------------------------------
                | RESERVA
                |--------------------------------------------------------------------------
                */

                $reservaId = DB::table('reserves')->insertGetId([
                    'hotel_id' => $dados['hotel_id'],
                    'room_id' => $dados['room_id'],
                    'check_in' => $dados['check_in'],
                    'check_out' => $dados['check_out'],
                    'total' => $dados['total']
                ]);

                /*
                |--------------------------------------------------------------------------
                | HÓSPEDES
                |--------------------------------------------------------------------------
                */

                foreach ($dados['guests'] as $guest) {

                    DB::table('guests')->insert([
                        'reserve_id' => $reservaId,
                        'name' => $guest['name'],
                        'last_name' => $guest['last_name'],
                        'phone' => $guest['phone']
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | DIÁRIAS
                |--------------------------------------------------------------------------
                */

                foreach ($dados['dailies'] as $daily) {

                    DB::table('dailies')->insert([
                        'reserve_id' => $reservaId,
                        'date' => $daily['date'],
                        'value' => $daily['value']
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | PAGAMENTOS
                |--------------------------------------------------------------------------
                */

                foreach ($dados['payments'] ?? [] as $payment) {

                    DB::table('payments')->insert([
                        'reserve_id' => $reservaId,
                        'method' => $payment['method'],
                        'value' => $payment['value']
                    ]);
                }

                return $reservaId;
            });

            return response()->json([
                'message' => 'Reserva cadastrada com sucesso.',
                'reserve_id' => $reservaId
            ], 201);

        } catch (\Throwable $erro) {

            return response()->json([
                'message' => 'Erro ao cadastrar reserva.',
                'error' => $erro->getMessage()
            ], 500);
        }
    }
}