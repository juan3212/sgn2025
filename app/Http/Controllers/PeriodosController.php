<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periodo;
use Yajra\DataTables\Facades\DataTables;

class PeriodosController extends Controller
{
    public $periodo;
    public $fecha_inicio;
    public $fecha_fin;
    public function updatePeriodo(Request $request)
    {
        $request->validate([
            'periodo' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
        $periodo = Periodo::updateOrCreate(
            ['periodo' => $request->periodo],
            [
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
            ]
        );
        return response()->json($periodo);
    }

    public function getPeriodos()
    {
        $periodos = Periodo::all();
        return DataTables::of($periodos)
        ->addColumn('action', function ($periodo) {
            return '<a class="btn btn-xs btn-primary edit" href="/create-periodo/'.$periodo->id.'">Edit</a>';
        })
        ->rawColumns(['action'])
        ->make(true);
        
    }

    public function delete($id){
        $periodo = Periodo::find($id);
        $periodo->delete();
        return response()->json(['success' => 'Periodo eliminado exitosamente.']);
    }
}
