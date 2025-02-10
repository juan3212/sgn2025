<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grado;

class GradosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $grados = [
            ["grado"=>"PREKINDER"],
            ["grado"=>"KINDER"],
            ["grado"=>"TRANSICIÓN"],
            ["grado"=>"PRIMERO"],
            ["grado"=>"SEGUNDO"],
            ["grado"=>"TERCERO"],
            ["grado"=>"CUARTO"],
            ["grado"=>"QUINTO"],
            ["grado"=>"SEXTO"],
            ["grado"=>"SÉPTIMO"],
            ["grado"=>"OCTAVO"],
            ["grado"=>"NOVENO"],
            ["grado"=>"DÉCIMO"],
            ["grado"=>"UNDÉCIMO"]
        ];
        foreach ($grados as $grado) {
           Grado::create($grado);
        }
    }
}
