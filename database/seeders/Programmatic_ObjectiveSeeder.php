<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Programmatic_Objective;

class Programmatic_ObjectiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Programmatic_Objective::insert([
            [
                'id' => 1,
                'description' =>  'Test',
                'strategy_objective' => 'Si',
                'create_date'=>Carbon::now(),
                'percentage' => 25,
                'institution_id' =>1 ,
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'description' =>  'Test2',
                'strategy_objective' => 'No',
                'create_date'=>Carbon::now(),
                'percentage' => 50,
                'institution_id' =>1 ,
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
