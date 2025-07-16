<?php

namespace App\Livewire\Forms;

use App\Models\BaseMateria;
use Livewire\Component;
use App\Models\Usuario;
use App\Models\Grado;
use App\Models\Grupo;
use App\Models\Materia;
use Illuminate\Support\Facades\Auth;

class MateriasForm extends Component
{
    public $isNewSubject;
    public $subjectId;
    public $subjects;
    public $subjectSelected;
    public $ih;
    public $teacherSelected;
    public $teachers;
    public $teacher_id;
    public $isTeacher;

    public $gradeSelected;
    public $grades;
    public $classSelected;
    public $classes;

    public function boot()
    {
        $user = Auth::user();
        $this->isTeacher = $user->hasRole('profesor');
        if ($this->isTeacher) {
            $this->teacher_id = $user->id;
        }
    }
    public function mount($subjectId = null)
    {
        $this->subjectId = $subjectId;
        if($subjectId){
            $this->getCurrentData($subjectId);
            $this->isNewSubject = false;
        }else{
            $this->isNewSubject = true;
        }
    }

    public function getCurrentData($id)
    {
        $materia = Materia::with( 'profesor')->find($id);
        $this->subjectSelected = $materia->materia_id;
        $this->gradeSelected = $materia->grado_id;
        $this->classSelected = $materia->grupo_id;
        $this->teacherSelected = $materia->profesor->nombre.' '.$materia->profesor->apellido;
        $this->teacher_id = $materia->profesor->id;
        $this->ih = $materia->intensidad_horaria;
    }

    public function subjectAlreadyExist()
    {
        $materia = Materia::where('materia_id', $this->subjectSelected)
            ->where('grado_id', $this->gradeSelected)
            ->where('grupo_id', $this->classSelected)
            ->first();
        if($materia){
            return true;
        }
        return false;
    }

    public function submit()
    {
        try {
            if($this->isNewSubject){
                $exist = $this->subjectAlreadyExist();
                if($exist){
                    session()->flash('error', 'La materia ya existe.');
                    return;
                }
            }
            
            $this->validate([
                'subjectSelected' => 'required',
                'ih' => 'required',
                'gradeSelected' => 'required',
                'classSelected' => 'required',
            ]);

            // Lógica updateOrCreate
            $materia = Materia::updateOrCreate(
                ['id' => $this->subjectId], // Condición para buscar (si existe, actualiza)
                [
                    'materia_id' => $this->subjectSelected,
                    'intensidad_horaria' => $this->ih,
                    'profesor_id' => $this->teacher_id,
                    'grado_id' => $this->gradeSelected,
                    'grupo_id' => $this->classSelected,
                ]
            );

            session()->flash('message', 'Materia guardada exitosamente.');
            $this->reset(['subjectSelected', 'ih', 'teacher_id', 'gradeSelected', 'classSelected']);
            
            // Si es una actualización, mantener el ID para futuras ediciones
            if ($this->subjectId) {
                $this->subjectId = $materia->id;
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $this->subjects = BaseMateria::all();
        $this->grades = Grado::all();
        $this->classes = Grupo::all();
        return view('livewire.forms.materias-form');
    }
}
