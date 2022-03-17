<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\TrackingObservationCusca;
use Illuminate\Database\Seeder;

class TrackingObservationCuscaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TrackingObservationCusca::insert([
            [
                'id' => 1,
                'observation' =>  'Ninguna',
                'observation_reply'=> 'Ninguna',
                'reply_date' => Carbon::now(),
                'year_id' => 1,
                'month_id' => 1,
                // 'actions_cusca_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'observation' =>  'Observación Uno',
                'observation_reply'=> 'Respuesta a observación Uno',
                'reply_date' => Carbon::now(),
                'year_id' => 2,
                'month_id' => 2,
                // 'actions_cusca_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

        ]);
    }
}
