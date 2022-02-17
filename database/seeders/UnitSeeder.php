<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::insert([
            [
                'id' => 1,
                'unit_name' => 'Anual',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'unit_name' => 'Mensual',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 3,
                'unit_name' => 'Semanal',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

        ]);
    }
}
