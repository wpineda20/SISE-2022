<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Institution;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Institution::insert([
              /*[
                'id' => 1,
                'institution_name' => 'Secretaría de Cultura de la Presidencia',
                'description' => null,
                'mission' => 'La Secretaría de Cultura de la Presidencia asegura el derecho a la cultura y al fortalecimiento de las identidades salvadoreñas, ejecutando la rectoría de la protección, conservación, difusión del patrimonio cultural y las expresiones artísticas',
                'vision' => 'Ser la institución que garantiza el derecho a la cultura como factor de identidad y cambio social',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],*/
            [
                'id' => 1,
                'institution_name' => 'Ministerio de Cultura',
                'description' => null,
                'mission' => 'El Ministerio de Cultura asegura el derecho a la cultura y al fortalecimiento de las identidades salvadoreñas, ejecutando la rectoría de la protección, conservación, difusión del patrimonio cultural y las expresiones artísticas',
                'vision' => 'Ser la institución que garantiza el derecho a la cultura como factor de identidad y cambio social',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
