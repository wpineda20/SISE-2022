<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\ActionsCusca;
use Illuminate\Database\Seeder;

class ActionsCuscaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ActionsCusca::insert([
            [
                'id' => 1,
                'action_description' =>  'Descripción de Acción Uno',
                'annual_actions'=> 10,
                'executed' => 'SI',
                'responsable_name'=>'Maria Ruano',
                'verification_method'=>'Documentos',
                'data_source'=>'Fuente de datos',
                'mesure_unit'=>'Documentos',
                'budget_executed'=>520,
                'user_id' => 1,
                'results_cusca_id' => 1,
                'month_id' => 1,
                'year_id' => 1,
                //'financings_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'action_description' =>  'Descripción de Acción Dos',
                'annual_actions'=> 1,
                'executed' => 'NO',
                'responsable_name'=>'Maria Perez',
                'verification_method'=>'Sistemas',
                'data_source'=>'Fuente de datos 2',
                'mesure_unit'=>'No',
                'budget_executed'=>5200,
                'user_id' => 2,
                'results_cusca_id' => 2,
                'month_id' => 2,
                'year_id' => 1,
                //'financings_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            
        ]);
    }
}
