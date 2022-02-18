<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\MonthlyClosing;

class MonthlyClosingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MonthlyClosing::insert([
            [
                'id' => 1,
                'year_id' =>  1,
                'month_id' => 1,
                'active' => 'SI',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'year_id' =>  2,
                'month_id' => 2,
                'active' => 'NO',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
