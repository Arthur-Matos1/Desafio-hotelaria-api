<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportXmlData extends Command
{
    protected $signature = 'hotelaria:import-xml';

    protected $description = 'Importa hotéis, quartos e reservas dos arquivos XML';

    public function handle(): int
    {
        try {

            DB::transaction(function () {

                /*
                |--------------------------------------------------------------------------
                | HOTÉIS
                |--------------------------------------------------------------------------
                */

                $xmlHotels = simplexml_load_file('/data/xml/hotels.xml');

                if ($xmlHotels === false) {
                    throw new \Exception('Não foi possível ler hotels.xml');
                }

                foreach ($xmlHotels->Hotel as $hotel) {

                    DB::table('hotels')->updateOrInsert(
                        [
                            'id' => (int) $hotel['id']
                        ],
                        [
                            'name' => (string) $hotel->Name
                        ]
                    );
                }

                $this->info('Hotéis importados!');


                /*
                |--------------------------------------------------------------------------
                | QUARTOS
                |--------------------------------------------------------------------------
                */

                $xmlRooms = simplexml_load_file('/data/xml/rooms.xml');

                if ($xmlRooms === false) {
                    throw new \Exception('Não foi possível ler rooms.xml');
                }

                foreach ($xmlRooms->Room as $room) {

                    DB::table('rooms')->updateOrInsert(
                        [
                            'id' => (int) $room['id']
                        ],
                        [
                            'hotel_id' => (int) $room['hotelCode'],
                            'name' => (string) $room->Name
                        ]
                    );
                }

                $this->info('Quartos importados!');


                /*
                |--------------------------------------------------------------------------
                | RESERVAS
                |--------------------------------------------------------------------------
                */

                $xmlReserves = simplexml_load_file('/data/xml/reserves.xml');

                if ($xmlReserves === false) {
                    throw new \Exception('Não foi possível ler reserves.xml');
                }

                foreach ($xmlReserves->Reserve as $reserve) {

                    $reserveId = (int) $reserve['id'];

                    /*
                    |--------------------------------------------------------------
                    | Reserva principal
                    |--------------------------------------------------------------
                    */

                    DB::table('reserves')->updateOrInsert(
                        [
                            'id' => $reserveId
                        ],
                        [
                            'hotel_id' => (int) $reserve['hotelCode'],
                            'room_id' => (int) $reserve['roomCode'],
                            'check_in' => (string) $reserve->CheckIn,
                            'check_out' => (string) $reserve->CheckOut,
                            'total' => (string) $reserve->Total
                        ]
                    );


                    /*
                    |--------------------------------------------------------------
                    | Limpa dados antigos da reserva
                    |--------------------------------------------------------------
                    |
                    | Isso evita duplicação caso o importador seja executado
                    | várias vezes.
                    |
                    */

                    DB::table('guests')
                        ->where('reserve_id', $reserveId)
                        ->delete();

                    DB::table('dailies')
                        ->where('reserve_id', $reserveId)
                        ->delete();

                    DB::table('payments')
                        ->where('reserve_id', $reserveId)
                        ->delete();


                    /*
                    |--------------------------------------------------------------
                    | Hóspedes
                    |--------------------------------------------------------------
                    */

                    if (isset($reserve->Guests)) {

                        foreach ($reserve->Guests->Guest as $guest) {

                            DB::table('guests')->insert([
                                'reserve_id' => $reserveId,
                                'name' => (string) $guest->Name,
                                'last_name' => (string) $guest->LastName,
                                'phone' => (string) $guest->Phone
                            ]);
                        }
                    }


                    /*
                    |--------------------------------------------------------------
                    | Diárias
                    |--------------------------------------------------------------
                    */

                    if (isset($reserve->Dailies)) {

                        foreach ($reserve->Dailies->Daily as $daily) {

                            DB::table('dailies')->insert([
                                'reserve_id' => $reserveId,
                                'date' => (string) $daily->Date,
                                'value' => (string) $daily->Value
                            ]);
                        }
                    }


                    /*
                    |--------------------------------------------------------------
                    | Pagamentos
                    |--------------------------------------------------------------
                    */

                    if (isset($reserve->Payments)) {

                        foreach ($reserve->Payments->Payment as $payment) {

                            DB::table('payments')->insert([
                                'reserve_id' => $reserveId,
                                'method' => (int) $payment->Method,
                                'value' => (string) $payment->Value
                            ]);
                        }
                    }
                }

                $this->info('Reservas importadas!');
            });


            /*
            |--------------------------------------------------------------------------
            | FINALIZAÇÃO
            |--------------------------------------------------------------------------
            */

            $this->newLine();
            $this->info('Importação concluída com sucesso!');

            return Command::SUCCESS;

        } catch (\Throwable $erro) {

            $this->error('Erro durante a importação:');
            $this->error($erro->getMessage());

            return Command::FAILURE;
        }
    }
}