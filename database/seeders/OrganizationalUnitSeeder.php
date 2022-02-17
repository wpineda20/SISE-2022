<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\OrganizationalUnit;

class OrganizationalUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrganizationalUnit::insert([
             [
                'id' => 1,
                'ou_name' => 'Archivo General de la Nación',
                'direction_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
             [
                'id' => 2,
                'ou_name' => 'Biblioteca Nacional de El Salvador "Francisco Gavidia"',
                'direction_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
