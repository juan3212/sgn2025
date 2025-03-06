<?php

namespace App\Livewire\Forms;

use App\Models\BaseMateria;
use Livewire\Component;
use App\Models\Usuario;
use App\Models\Grado;
use App\Models\Grupo;
use App\Models\Materia;

class MateriasForm extends Component
{

    public $subjects;
    public $subjectSelected;
    public $ih;
    public $teacherSelected;
    public $teachers;
    public $teacher_id;

    public $gradeSelected;
    public $grades;
    public $class;
    public $classes;

    public function updatedTeacherSelected()
    {
        $this->teachers = Usuario::where(function($query) {
                $query->where('nombre', 'like', '%'.$this->teacherSelected.'%')
                      ->orWhere('apellido', 'like', '%'.$this->teacherSelected.'%');
            })->get();

    }

    public function submit(){
        try {
            $this->validate([
                'subjectSelected' => 'required',
                'ih' => 'required',
                'teacher_id' => 'required',
                'gradeSelected' => 'required',
                'class' => 'required',
            ]);


            $materia = Materia::create([
                'materia_id' => $this->subjectSelected,
                'intensidad_horaria' => $this->ih,
                'profesor_id' => $this->teacher_id,
                'grado_id' => $this->gradeSelected,
                'grupo_id' => $this->class,
            ]);

            session()->flash('message', 'Materia guardada exitosamente.');
            $this->reset();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: '. $this->teacher_id . $e->getMessage());
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
