<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class NotasController extends Controller
{
    //
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
        return DataTables()->of($students)
            ->addColumn('rates', function ($student) {
                return '<span contenteditable="true" class="editable-cell" data-id="'.$student->id.'">' . $student->notas . '</span>';
            })
            ->rawColumns(['rates'])
            ->make(true);
    }
}
