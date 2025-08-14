<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actividad;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use App\View\Components\progressBar;
use App\Services\CalcularNotasService;
use App\Models\Materia;

class ActividadesController extends Controller
{
    //
    public $calcularNotasService;
    public $user;
    public $isAdmin;
    public $isTeacher;

    public function __construct()
    {
        $this->calcularNotasService = new CalcularNotasService();
        $this->getUserData();
    }

    public function getMateria($materia_id)
    {
        $materia = Materia::select('base_materia.nombre_materia as nombre', 'grados.grado', 'grupos.grupo')
                            ->join('base_materia', 'materias.materia_id', 'base_materia.id')
                            ->join('grados', 'materias.grado_id', 'grados.id')
                            ->join('grupos', 'materias.grupo_id', 'grupos.id')
                            ->where('materias.id', $materia_id)
                            ->first();
        $nombreMateria = $materia->nombre.' '.$materia->grado.' '.$materia->grupo;
        return $nombreMateria;
    }

    public function  getUserData()
    {
        $user = Auth::user();
        $this->user = $user; // Almacenar el objeto usuario para usos potenciales
        $this->isAdmin = $user->hasRole('Super-Admin');
        $this->isTeacher = $user->hasRole('profesor');
    }


    public function data($materia, $periodo, $competencia)
    {
        if (!$materia || !$periodo || !$competencia) {
            return response()->json(['error' => 'Los parámetros materia_id, competencia_id y periodo_id son obligatorios'], 400);
        }
    
        // Consulta inicial
        $query = Actividad::with('tipoNota')
                            ->where('materia_id', $materia)
                            ->where('competencia_id', $competencia);
    
        // Procesamiento con DataTables
        return datatables()->of($query)
            ->addColumn('checkbox', function ($actividad) {
                return '<input type="checkbox" class="select-checkbox form-checkbox h-5 w-5 text-blue-600" data-id="' . $actividad->id . '">';
            })
            ->addColumn('action', function ($actividad) {
                $actions = '<div class="flex flex-wrap gap-1">
                                <a href="/edit/actividades/'.$actividad->id.'" class="btn btn-primary btn-xs">Editar</a>
                                <button class="btn btn-danger btn-xs delete" data-id="'.$actividad->id.'">Eliminar</button>
                            </div>';
                return $actions;
            })
            ->addColumn('notas', function ($actividad) {
                $nota = $this->calcularNotasService->notasActividad(['actividad' => $actividad->id, 'estudiante' => $this->user->id]);
                $nota = number_format($nota, 1, '.');
                $progressBar = new progressBar($nota, 10);

                return Blade::renderComponent($progressBar);
                
            })
            ->rawColumns(['checkbox', 'action', 'notas']) // Indica que estas columnas contienen HTML
            ->make(true);
    }

    public function render($materia, $periodo, $competencia)
    {
        $nombreMateria = $this->getMateria($materia);
        return view('actividades', [
            'nombreMateria' => $nombreMateria, 
            'materia' => $materia, 
            'periodo' => $periodo, 
            'competencia' => $competencia
        ]);   
    }
    
    public function delete($id){
        $actividad = Actividad::findOrFail($id);
        $actividad->delete();
        return response()->json(['message' => 'Actividad eliminada con éxito']);
    }
}
