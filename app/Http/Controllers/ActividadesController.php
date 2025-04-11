<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actividad;

class ActividadesController extends Controller
{
    //
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
            ->addColumn('rates', function ($actividad) {
                $rates = '<div class="flex flex-wrap gap-1">
                                <a href="#" class="btn btn-info btn-xs">Notas</a>
                            </div>';
                return $rates;
            })
            ->rawColumns(['checkbox', 'action', 'rates']) // Indica que estas columnas contienen HTML
            ->make(true);
    }

    public function delete($id){
        $actividad = Actividad::findOrFail($id);
        $actividad->delete();
        return response()->json(['message' => 'Actividad eliminada con éxito']);
    }
}
