<?php

namespace App\Livewire\Pages\Administrador;

use Livewire\Component;
use App\Models\Grado;
use App\Models\Grupo;
use App\Models\Materia;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\administrador\informesController;

class InformeMaterias extends Component
{
    public $grados = [];
    public $gradoSelected;
    public $grupos = [];
    public $grupoSelected;
    public $materias = [];
    public $materiaSelected;
    public $informe = [];
    public $informeMessage = 'Seleccione un grado, grupo para generar el informe.';

    public function mount()
    {
        $this->getInitialData();
    }
    public function getInitialData()
    {
        $this->grados = Grado::all();
        $this->grupos = Grupo::all();
    }

    public function updatedGradoSelected()
    {
        $this->getMaterias($this->gradoSelected);
    }
  
    public function getMaterias($grado)
    {
        $this->materias = Materia::select('base_materia.id as id', 'base_materia.nombre_materia as nombre')
            ->join("base_materia", "base_materia.id", "=", "materias.materia_id")
            ->where("materias.grado_id", $grado)
            ->distinct()
            ->get();
    }

    public function getInforme()
    {
        $validated = $this->validate([
            'gradoSelected' => 'required',
            'grupoSelected' => 'required',
        ]);
       $informe = new informesController();
       $this->informe = $informe->generarInforme(
            $validated['gradoSelected'],
            $validated['grupoSelected'],
            $this->materiaSelected,
            'materia',
        );

        $this->reset(['gradoSelected', 'grupoSelected', 'materiaSelected']);
        if(empty($this->informe)){
            $this->informeMessage = 'No hay información disponible para este grado, grupo y materia.';
        }



    }

    public function render()
    {
        return view('livewire.pages.administrador.informe-materias');
    }
}
