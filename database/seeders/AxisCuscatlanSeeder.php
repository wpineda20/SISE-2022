<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\AxisCuscatlan;

class AxisCuscatlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AxisCuscatlan::insert([
            [
                'id' => 1,
                'axis_description' =>  'Esto es una descripción',
                'percentage' => 9.5,
                'create_date' => Carbon::now(),
                'user_id' => 1,
                // 'programmatic_objectives_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'axis_description' =>  'Esto es una descripción',
                'percentage' => 5.95,
                'create_date' => Carbon::now(),
                'user_id' => 2,
                // 'programmatic_objectives_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
