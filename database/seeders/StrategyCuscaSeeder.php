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
                'description_strategy' =>  'Test',
                'create_date'=>Carbon::now(),
                'user_create_strategy'=>'Juan Perez',
                'percentage' => 25,
                'organizational_units_id' => 1,
                'programmatic_objectives_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'description_strategy' =>  'Test2',
                'create_date'=>Carbon::now(),
                'user_create_strategy'=>'Talento Humano',
                'percentage' => 25,
                'organizational_units_id' => 1,
                'programmatic_objectives_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 3,
                'description_strategy' =>  'Test3',
                'create_date'=>Carbon::now(),
                'user_create_strategy'=>'Planificación',
                'percentage' => 25,
                'organizational_units_id' => 2,
                'programmatic_objectives_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
