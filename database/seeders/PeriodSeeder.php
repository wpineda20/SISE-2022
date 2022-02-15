<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Period;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Period::insert([
            [
                'id' => 1,
                'period_name' => '2017 - 2019',
                'start_year' => 2017,
                'end_year' => 2019,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'period_name' => '2020 - 2024',
                'start_year' => 2020,
                'end_year' => 2024,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
          
        ]);
    }
}
