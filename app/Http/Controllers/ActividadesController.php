<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actividad;
use App\Http\Controllers\Estudiantes\calcularNotasController;
use Illuminate\Support\Facades\Auth;

class ActividadesController extends Controller
{
    //
    public $calcularNotasController;
    public $user;
    public $isAdmin;
    public $isTeacher;

    public function __construct(calcularNotasController $calcularNotasController)
    {
        $this->calcularNotasController = $calcularNotasController;
        $this->getUserData();
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
        // Validación de parámetros (opcional pero recomendado)
        if (!$materia || !$periodo || !$competencia) {
            return response()->json(['error' => 'Los parámetros materia_id, competencia_id y periodo_id son obligatorios'], 400);
        }
    
        // Consulta inicial (sin ejecutarla)
        $query = Actividad::with('tipoNota')
                            ->where('materia_id', $materia)
                            ->where('periodo_id', $periodo)
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
                $nota = $this->calcularNotasController->notasActividad(['actividad' => $actividad->id, 'estudiante' => $this->user->id]);
                $nota = number_format($nota, 1, '.');
                return ''.$nota.' / 10';
                
            })
            ->rawColumns(['checkbox', 'action']) // Indica que estas columnas contienen HTML
            ->make(true);
    }

    public function delete($id){
        $actividad = Actividad::findOrFail($id);
        $actividad->delete();
        return response()->json(['message' => 'Actividad eliminada con éxito']);
    }
}
