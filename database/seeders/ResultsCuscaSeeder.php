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
                'result_description' =>  'Descripción de resultados',
                'responsible_name' =>  'Responsable',
                'percentage' => 25,
                'create_date'=>Carbon::now(),
                'user_id' => 1,
                'axis_cusca_id' => 1,
                'indicator_id' => 1,
                'organizational_units_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'result_description' =>  'Descripción dos',
                'responsible_name' =>  'Responsable',
                'percentage' => 100,
                'create_date'=>Carbon::now(),
                'user_id' => 2,
                'axis_cusca_id' => 2,
                'indicator_id' => 2,
                'organizational_units_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
           
        ]);
    }
}
