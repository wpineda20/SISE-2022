<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Indicator;

class IndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Indicator::insert([
            [
                'id' => 1,
                'indicator_name' => 'Indicator 1',
                'strategic_indicator' => 0,
                'institution_id' => 1,
                'unit_id' => 1,
                'organizational_unit_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
             [
                'id' => 2,
                'indicator_name' => 'Indicator 2',
                'strategic_indicator' => 1,
                'institution_id' => 2,
                'unit_id' => 2,
                'organizational_unit_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
