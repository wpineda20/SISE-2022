<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\StrategyCusca;
use Illuminate\Database\Seeder;

class StrategyCuscaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StrategyCusca::insert([
            [
                'id' => 1,
                'description_strategy' =>  'Descripción de estrategia Uno',
                'executed'=>'SI',
                'user_id'=>1,
                'organizational_units_id' => 1,
                'programmatic_objectives_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'description_strategy' =>  'Descripción de estrategia Dos',
                'executed'=>'NO',
                'user_id'=>2,
                'organizational_units_id' => 2,
                'programmatic_objectives_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
           
        ]);
    }
}
