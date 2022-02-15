<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            DepartmentSeeder::class,
            MunicipalitiesSeeder::class,
            TrakingStatusSeeder::class,
            InstitutionSeeder::class,
            DirectionSeeder::class,
            FinancingSeeder::class,
            PeriodSeeder::class,
            UnitSeeder::class,
            OrganizationalUnitSeeder::class,
        ]);
    }
}
