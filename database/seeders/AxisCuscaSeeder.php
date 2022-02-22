<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\AxisCusca;

class AxisCuscaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AxisCusca::insert([
            [
                'id' => 1,
                'axis_description' =>  'Descripción',
                'percentage' => 25,
                'create_date'=>Carbon::now(),
                'user_id' => 1,
                'programmatic_objectives_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'axis_description' =>  'Descripción Dos',
                'percentage' => 30,
                'create_date'=>Carbon::now(),
                'user_id' => 3,
                'programmatic_objectives_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
