<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Competencia;
use App\Models\Materia;
use App\Models\Periodo;
use Illuminate\Support\Facades\DB;

class CompetenciasController extends Controller
{
    //
    public function data(){
        $compentencias = Competencia::all();
        return DataTables()->of($compentencias)
        ->addColumn('checkbox', function($competencia){
            return '<input type="checkbox" class="select-checkbox form-checkbox h-5 w-5 text-blue-600" data-id="' . $competencia->id . '">';
        })
        ->addColumn('actions', function($competencia){
            $actions = '<a class="btn btn-xs btn-primary edit" href="/edit/competencias/'.$competencia->id.'">Edit</a>
                        <button class="btn btn-xs btn-danger delete" data-id="'.$competencia->id.'">Delete</button>';
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

}
