<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Estudiantes\calcularNotasController;
use Illuminate\Http\Request;
use App\Models\Competencia;
use App\Models\Materia;
use App\Models\Usuario;
use App\Models\Periodo;
use App\Models\Grado;
use App\Models\Grupo;
use App\Models\BaseMateria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CompetenciasController extends Controller
{
    //
    public $isAdmin;
    public $isTeacher;
    public $user;
    public $periodo;
    public $grado;
    public $grupo;
    public $materias;

    public function  getUserData()
    {
        $user = Auth::user();
        $this->user = $user; // Almacenar el objeto usuario para usos potenciales
        $this->isAdmin = $user->hasRole('Super-Admin');
        $this->isTeacher = $user->hasRole('profesor');
    }

    public function loadData()
    {
        $this->getUserData();

        $compentencias = Competencia::select(
            'competencias.id',
            'competencias.nombre',
            'competencias.descripcion',
            'competencias.periodo_id',
            'competencias.porcentaje'
        )
        ->leftJoin('materia_has_competencia', 'competencias.id', '=', 'materia_has_competencia.competencia_id')
        ->leftJoin('materias', 'materia_has_competencia.materia_id', '=', 'materias.id')
        ->when(!$this->isAdmin, function($q){
            $q->where('materias.profesor_id', $this->user->id);
        })
        ->distinct();
        
        return $compentencias;
    }

    public function loadDataForFilters()
    {
        $this->getUserData();

        $this->periodo = Periodo::all();

       
        $materiasQuery = Materia::query();
        if ($this->isTeacher) {
            $materiasQuery->where('profesor_id', $this->user->id);
        }
        
        $materias = $materiasQuery->get();

        $gradoIds  = $materias->pluck('grado_id')->unique()->toArray();
        $grupoIds  = $materias->pluck('grupo_id')->unique()->toArray();
        $materiasIds = $materias->pluck('materia_id')->unique()->toArray();

        $this->grado  = Grado::whereIn('id', $gradoIds)->get();
        $this->grupo  = Grupo::whereIn('id', $grupoIds)->get();
        $this->materias = BaseMateria::whereIn('id', $materiasIds)->get();

        return [$this->periodo, $this->grado, $this->grupo, $this->materias];
    }
    
    public function data(Request $request){
        $compentencias = $this->loadData();

       if ($request->grado) {
            $compentencias->where('materias.grado_id', $request->grado);
        }

        if ($request->grupo) {
            $compentencias->where('materias.grupo_id', $request->grupo);
        }

        if ($request->materia) {
            $compentencias->where('materias.materia_id', $request->materia);
        }

        if ($request->periodo) {
            $compentencias->where('periodo_id', $request->periodo);
        }
       
        return DataTables()->of($compentencias)
        ->addColumn('checkbox', function($competencia){
            return '<input type="checkbox" class="select-checkbox form-checkbox h-5 w-5 text-blue-600" data-id="' . $competencia->id . '">';
        })
        ->addColumn('actions', function($competencia){
            $actions = '<div class="flex flex-wrap gap-1">
                            <a class="btn btn-xs btn-primary edit" href="/edit/competencias/'.$competencia->id.'">Edit</a>
                            <button class="btn btn-xs btn-danger delete" data-id="'.$competencia->id.'">Delete</button>
                        </div>';
            return $actions;
        })
        ->rawColumns(['checkbox','actions'])
        ->make(true); 
    }


    public function delete($id){
        try{
            $competencia = Competencia::findOrFail($id);
            $competencia->delete();
            return response()->json(['success' => true, 'message' => 'Competencia eliminada con éxito']);
        }catch(\Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al eliminar la competencia']);
        }
    }

    public function render()
    {
        $this->loadDataForFilters();
        return view('competencias', [
            'periodos' => $this->periodo,
            'grados' => $this->grado,
            'grupos' => $this->grupo,
            'materias' => $this->materias,
        ]);
    }

}
