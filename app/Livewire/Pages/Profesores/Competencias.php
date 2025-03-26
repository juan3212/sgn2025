<?php

namespace App\Livewire\Pages\Profesores;

use Livewire\Component;
use App\Models\Materia;
use App\Models\Competencia;

class Competencias extends Component
{

    public $subjectId;
    public $periodo;

    public function mount($subjectId){
        $this->subjectId = $subjectId;
        $this->createTable($subjectId);
    }

    public function createTable($id){
        $competencias = Competencia::select('competencias.descripcion', 'base_materia.nombre_materia')
        ->join('materia_has_competencia as mc', 'competencias.id', '=', 'mc.competencia_id')
        ->join('materias as m', 'mc.materia_id', '=', 'm.id')
        ->join('base_materia', 'm.materia_id', '=', 'base_materia.id')
        ->where('m.id', $id)
        ->where('competencias.periodo_id', '=', $this->periodo)
        ->get();

        echo($competencias);
    }

    public function render()
    {
        return view('livewire.pages.profesores.competencias');
    }
}
