<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BaseMateria;

class BaseMateriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $materias = [
            ["nombre_materia"=>"SPANISH"],
            ["nombre_materia"=>"ETHICS AND SOCIAL COEXISTENCE"],
            ["nombre_materia"=>"MATHEMATICAL LOGIC CONNECTION"],
            ["nombre_materia"=>"LITERACY"],
            ["nombre_materia"=>"SABER"],
            ["nombre_materia"=>"SOCIAL AND CULTURAL ENVIRONMENT"],
            ["nombre_materia"=>"ENGLISH"],
            ["nombre_materia"=>"PARENT'S COMMITMENT"],
            ["nombre_materia"=>"SCHOOL BEHAVIOR"],
            ["nombre_materia"=>"MATH"],
            ["nombre_materia"=>"FINEMOTOR"],
            ["nombre_materia"=>"ARTS"],
            ["nombre_materia"=>"SCIENCE"],
            ["nombre_materia"=>"MUSIC"],
            ["nombre_materia"=>"ENGLISH USAGE"],
            ["nombre_materia"=>"SYSTEMS AND DESIGN"],
            ["nombre_materia"=>"RELIGION"],
            ["nombre_materia"=>"SOCIAL STUDIES"],
            ["nombre_materia"=>"DRAMA"],
            ["nombre_materia"=>"CALLIGRAPHY"],
            ["nombre_materia"=>"MUSICAL DRAMA"],
            ["nombre_materia"=>"DANCING"],
            ["nombre_materia"=>"PHYSICS"],
            ["nombre_materia"=>"HUMANITIES AND SPANISH LANGUAGE"],
            ["nombre_materia"=>"ARTISTIC EXPRESSION"],
            ["nombre_materia"=>"INTERACTIVE ENGLISH"],
            ["nombre_materia"=>"PHILOSOPHY"],
            ["nombre_materia"=>"CHEMISTRY"],
            ["nombre_materia"=>"PHYSICAL EDUCATION"],
            ["nombre_materia"=>"FRENCH"],
            ["nombre_materia"=>"POLITICAL SCIENCES"],
            ["nombre_materia"=>"TODOS"]
        ];
        foreach ($materias as $materia) {
            BaseMateria::create($materia);
        }
    }
}
