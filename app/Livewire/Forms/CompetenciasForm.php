<?php

namespace App\Livewire\Forms;
use Illuminate\Support\Facades\Auth;
use App\Models\Materia;
use App\Models\Competencia;
use App\Models\Periodo;
use App\Http\Controllers\CompetenciasServiceController;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CompetenciasForm extends Component
{
    public $isTeacher;
    public $subjects;
    public $periodos;
    public $teacher_id;
    public $teacherSelected;
    public $porcentaje;
    public $periodoSelected;
    public $search;
    public $competenceName;
    public $competenceDescription;
    public $selectedSubjects = [];
    public $paginationInfo = [];
    protected $competenciaService;

    public function boot(CompetenciasServiceController $competenciasServiceController)
    {
        $this->competenciaService = $competenciasServiceController;
        
        $user = Auth::user();
        $this->isTeacher = $user->hasRole('profesor');
        if ($this->isTeacher) {
            $this->teacher_id = $user->id;
        }
    }

    public function updatedSearch()
    {
        $this->subjects = $this->competenciaService->getSubjects($this->search, $this->teacher_id);
    }


    public function submit(){
        $this->validate([
            'selectedSubjects' => 'required|array|min:1',
            'competenceName' => 'required|string|max:255',
            'competenceDescription' => 'required|string|max:1000',
            'periodoSelected' =>'required|integer',
            'porcentaje' => 'required|numeric|min:0|max:100'
        ], [
            'selectedSubjects.required' => 'Debes seleccionar al menos una materia.',
            'competenceName.required' => 'El nombre de la competencia es obligatorio.',
            'competenceDescription.required' => 'La descripción de la competencia es obligatoria.',
            'periodoSelected.required' => 'El periodo es obligatorio.',
            'porcentaje.required' => 'El porcentaje es obligatorio.',
            'porcentaje.numeric' => 'El porcentaje debe ser un número.',
            'porcentaje.min' => 'El porcentaje debe ser mayor o igual a 1.',
            'porcentaje.max' => 'El porcentaje debe ser menor o igual a 100.',
        ]);
        

        try {
            DB::beginTransaction();

            // Crear la competencia
            $competencia = Competencia::create([
                'nombre' => $this->competenceName,
                'descripcion' => $this->competenceDescription,
                'porcentaje' => $this->porcentaje,
                'periodo_id' =>$this->periodoSelected,
                'profesor_id' => $this->teacher_id,
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
        //dd($this->teacher_id);
        
        $this->periodos = $this->competenciaService->getPeriodo();
        return view('livewire.forms.competencias-form');
    }
}
