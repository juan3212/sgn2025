<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Estudiantes\calcularNotasController;
use Illuminate\Http\Request;
use App\Models\Competencia;
use App\Models\Materia;
use App\Models\Usuario;
use App\Models\Periodo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CompetenciasController extends Controller
{
    //
    public $isAdmin;
    public $isTeacher;
    public $user;


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

        if ($this->isAdmin) {
            return Competencia::all();
        } elseif ($this->isTeacher) {
            return Competencia::where('profesor_id', $this->user->id)->get();
        }else {
            return redirect()->back();
        }
    }
    
    public function data(){
        $compentencias = $this->loadData();
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

}
