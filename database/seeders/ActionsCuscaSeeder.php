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
                'action_description' =>  'Acción Uno',
                'annual_actions'=>2,
                'executed' => 'SI',
                'responsable_name'=>'Maria Ruano',
                'user_id' => 1,
                'results_cusca_id' => 1,
                'year_id' => 1,
                'financings_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'action_description' =>  'Acción Dos',
                'annual_actions'=>2,
                'executed' => 'SI',
                'responsable_name'=> 'Unidad de informática',
                'user_id' => 1,
                'results_cusca_id' => 1,
                'year_id' => 1,
                'financings_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 3,
                'action_description' =>  'Acción Tres',
                'annual_actions'=> 5,
                'executed' => 'NO',
                'responsable_name'=> 'Unidad de planificación',
                'user_id' => 2,
                'results_cusca_id' => 2,
                'year_id' => 2,
                'financings_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
