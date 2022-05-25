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
            DepartmentSeeder::class,
            MunicipalitiesSeeder::class,
            TrakingStatusSeeder::class,
            InstitutionSeeder::class,
            DirectionSeeder::class,
            FinancingSeeder::class,
            PeriodSeeder::class,
            UnitSeeder::class,
            OrganizationalUnitSeeder::class,
            IndicatorSeeder::class,
            MonthSeeder::class,
            YearSeeder::class,
            MonthlyClosingSeeder::class,
            UserSeeder::class,
            PoaClosingSeeder::class,
            AxisCuscaSeeder::class,
            Programmatic_ObjectiveSeeder::class,  
            StrategyCuscaSeeder::class,
            ResultsCuscaSeeder::class,
            ActionsCuscaSeeder::class,
            TrackingCuscaSeeder::class,


        ]);
    }
}
