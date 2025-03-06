<?php

namespace App\Livewire\Forms;
use App\Models\Materia;
use App\Models\Competencia;
use App\Models\Periodo;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CompetenciasForm extends Component
{
    public $subjects;
    public $periodos;
    public $periodoSelected;
    public $search;
    public $competenceName;
    public $competenceDescription;
    public $selectedSubjects = [];
    public $paginationInfo = [];

    public function updatedSearch()
    {
        $this->subjects = $this->getSubjects();
    }
    private function getSubjects()
    {
        return Materia::selectRaw('materias.id as id, CONCAT(nombre_materia, " - ", grado, " - ", grupo) as nombre')
            ->join('base_materia', 'materias.materia_id', '=', 'base_materia.id')
            ->join('grados', 'materias.grado_id', '=', 'grados.id')
            ->join('grupos', 'materias.grupo_id', '=', 'grupos.id')
            ->where('nombre_materia', 'like', '%' . $this->search . '%')
            ->get(); // Filtrar por nombre 
    }
    private function getPeriodo()
    {
        return Periodo::all();
    }

    public function submit(){
        $this->validate([
            'selectedSubjects' => 'required|array|min:1',
            'competenceName' => 'required|string|max:255',
            'competenceDescription' => 'required|string|max:1000',
            'periodoSelected' =>'required|integer'
        ], [
            'selectedSubjects.required' => 'Debes seleccionar al menos una materia.',
            'competenceName.required' => 'El nombre de la competencia es obligatorio.',
            'competenceDescription.required' => 'La descripción de la competencia es obligatoria.',
            'periodoSelected.required' => 'El periodo es obligatorio.',
        ]);
        

        try {
            DB::beginTransaction();

            // Crear la competencia
            $competencia = Competencia::create([
                'nombre' => $this->competenceName,
                'descripcion' => $this->competenceDescription,
                'periodo_id' =>$this->periodoSelected,
            ]);

            $competencia->materias()->sync($this->selectedSubjects);

            DB::commit();

            // Limpiar el formulario
            $this->reset();

            // Mostrar mensaje de éxito
            session()->flash('message', 'Competencia creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            session()->flash('error', 'Ocurrió un error al crear la competencia. Por favor, intenta nuevamente.');
           // \Log::error('Error al crear competencia: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $this->periodos = $this->getPeriodo();
        return view('livewire.forms.competencias-form');
    }
}
