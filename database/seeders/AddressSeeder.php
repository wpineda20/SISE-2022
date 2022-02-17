<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::insert([
             [
                'id' => 1,
                'address_name' => 'San Salvador',
                'institution_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
             [
                'id' => 2,
                'address_name' => 'San Salvador',
                'institution_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
