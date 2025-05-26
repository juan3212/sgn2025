<?php

namespace App\Livewire\Pages\Profesores;

use Livewire\Component;
use App\Models\Materia;
use App\Models\Periodo;
use Livewire\Attributes\Layout;

class MateriasCompetencias extends Component
{

    public $periodos = [];
    public $periodoSelected;
    public $materia;
    
    public function mount(Materia $materia)
    {
        $this->materia = $materia->id;
        $this->getData();
    }
    public function getData()
    {
        $materia = Materia::join('base_materia', 'materias.materia_id', '=', 'base_materia.id')
                         ->select('materias.id as id', 'base_materia.nombre_materia as nombre_materia')
                         ->where('materias.id', $this->materia)
                         ->first();

        $periodos = Periodo::all();

        $this->materia = $materia;
        $this->periodos = $periodos;
    }
    
    public function render()
    {
        
        return view('livewire.pages.profesores.materias-competencias'); 
    }
    
}
