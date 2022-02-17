<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Direction;

class DirectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Direction::insert([
             [
                'id' => 1,
                'direction_name' => 'Dirección Administrativa',
                'institution_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
             [
                'id' => 2,
                'direction_name' => 'Dirección Nacional de Formación en Artes',
                'institution_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
