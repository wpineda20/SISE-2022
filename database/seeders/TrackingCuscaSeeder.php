<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\TrackingCusca;
use Illuminate\Database\Seeder;

class TrackingCuscaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TrackingCusca::insert([
            [
                'id' => 1,
                'tracking_detail' =>  'Detalle del seguimiento Uno',
                'executed'=> 'SI',
                //'monthly_actions' => 10,
                'budget_executed' => 50000,
                'observation'=>'Ninguna',
                'reply'=>'null',
                'user_id' => 1,
                //'year_id' => 1,
                //'month_id' => 1,
                'traking_status_id' => 1,
                'actions_cusca_id' => 1,
                // 'tracking_observation_cusca_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'tracking_detail' =>  'Detalle del seguimiento dos',
                'executed'=> 'NO',
                //'monthly_actions' => 10,
                'budget_executed' => 50000,
                'observation'=>'Ninguna',
                'reply'=>'null',
                'user_id' => 1,
                //'year_id' => 1,
                //'month_id' => 1,
                'traking_status_id' => 1,
                'actions_cusca_id' => 1,
                // 'tracking_observation_cusca_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

        ]);
    }
}
