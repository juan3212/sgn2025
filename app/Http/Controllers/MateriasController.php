<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materia;

class MateriasController extends Controller
{
    //
    public function data(){
        
        $materias = Materia::select('materias.id', 'base_materia.nombre_materia', 'materias.intensidad_horaria', 'usuarios.nombre', 'usuarios.apellido', 'grados.grado', 'grupos.grupo')
        ->join('base_materia', 'materias.materia_id', '=', 'base_materia.id')
        ->join('usuarios', 'materias.profesor_id', '=', 'usuarios.id')
        ->join('grados', 'materias.grado_id', '=', 'grados.id')
        ->join('grupos', 'materias.grupo_id', '=', 'grupos.id')
        ->get();
    
        return datatables()->of($materias)
            ->addColumn('checkbox', function ($materia) {
                return '<input type="checkbox" class="select-checkbox form-checkbox h-5 w-5 text-blue-600" data-id="' . $materia->id . '">';
            })
            ->addColumn('action', function ($materia) {
                return '<a class="btn btn-xs btn-primary edit" href="/edit/materias/'.$materia->id.'">Edit</a>
                        <button class="btn btn-xs btn-danger delete" data-id="'.$materia->id.'">Delete</button>';
            })
            ->rawColumns(['checkbox', 'action'])
            ->make(true);
    }

    public function delete($id){
        $materia = Materia::find($id);
        $materia->delete();
        return response()->json(['success' => 'Materia eliminada exitosamente.']);
    }
}
