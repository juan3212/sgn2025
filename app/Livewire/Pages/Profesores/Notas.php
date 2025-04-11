<?php

namespace App\Livewire\Pages\Profesores;

use Livewire\Component;
use App\Models\Nota;
use App\Models\Usuario;
use Yajra\DataTables\Facades\DataTables;

class Notas extends Component
{

    public $grado_id = 7;
    public $grupo_id = 1;
    public $nota;
    public $usuario_id;

    public function getStudents()
    {

        return Usuario::with(['grupos', 'grados', 'notas'])
            ->whereHas('grados', function ($query) {
                $query->where('grado_id', $this->grado_id);
            })
            ->whereHas('grupos', function ($query) {
                $query->where('grupo_id', $this->grupo_id);
            })
            ->get();
    }

    public function table()
    {
        $students = $this->getStudents();
        return DataTables::of($students)
            ->addColumn('checkbox', function ($student) {
                return '<input type="checkbox" class="select-checkbox form-checkbox h-5 w-5 text-blue-600" data-id="' . $student->id . '">';
            })
            ->addColumn('actions', function ($student) {
                return  '<div class="flex flex-wrap gap-1">
                            <a class="btn btn-xs btn-primary edit" href="#">Edit</a>
                            <button class="btn btn-xs btn-danger delete" data-id="'.$student->id.'">Delete</button>
                        </div>';
            })
            ->addColumn('rates', function ($student) {
                return $student->notas;
            })
            ->rawColumns(['checkbox', 'actions'])
            ->make(true);
    }

    public function render()
    {
        echo($this->getStudents());
        return view('livewire.pages.profesores.notas');
    }
}
