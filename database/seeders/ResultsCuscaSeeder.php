<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\ResultsCusca;

class ResultsCuscaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ResultsCusca::insert([
            [
                'id' => 1,
                'result_description' =>  'Descripción de resultados uno',
                'mesure_unit' =>  'No recuerdo',
                'year_goal'=>10,
                'executed' => 'SI',
                'user_id' => 1,
                'indicator_id'=> 1,
                'organizational_units_id'=>1,
                // 'axis_cusca_id' => 1,
                'year_id' => 1,
                'period_id' => 1,
                'strategy_cusca_id'=>1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'result_description' =>  'Descripción de resultados dos',
                'mesure_unit' =>  'No recuerdo 2',
                'year_goal'=>15,
                'executed' => 'NO',
                'user_id' => 2,
                'indicator_id'=> 2,
                'organizational_units_id'=>2,
                // 'axis_cusca_id' => 2,
                'year_id' => 2,
                'period_id' => 1,
                'strategy_cusca_id'=>1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
           
        ]);
    }
}
