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
                'description' =>  'Objetivo programático Uno',
                'executed' => 'SI',
                'axis_cusca_id' => 1,
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'description' =>  'Objetivo programático Dos',
                'executed' => 'NO',
                'axis_cusca_id' => 2,
                'user_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
           
        ]);
    }
}
