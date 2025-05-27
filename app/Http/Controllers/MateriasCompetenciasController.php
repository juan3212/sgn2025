<?php

namespace App\Http\Controllers;

use App\View\Components\progressBar;
use Illuminate\Http\Request;
use App\Models\Materia;
use App\Http\Controllers\Estudiantes\calcularNotasController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class MateriasCompetenciasController extends Controller
{
    //
    public $materia;
    public $periodo;
    public $calcularNotasController;
    public $isAdmin;
    public $isTeacher;
    public $user;

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

    public function getCompetencesFromSubjects($materiaId, $periodo)
    {
        
        $materias = Materia::select('materias.*', 'c.*')
            ->join('materia_has_competencia as mc', 'materias.id', '=', 'mc.materia_id')
            ->leftJoin('competencias as c', 'mc.competencia_id', '=', 'c.id')
            ->where('materias.id', '=', $materiaId)
            ->where('c.periodo_id', '=', $periodo)
            ->get();
        return $materias;
    }

    public function data(Request $request)
    {
        $this->materia = $request->materia;
        $competences = $this->getCompetencesFromSubjects($request->materia, $request->periodo);

        return datatables()->of($competences)
            ->addColumn('checkbox', function ($competence) {
                return '<input type="checkbox" class="select-checkbox form-checkbox h-5 w-5 text-blue-600" data-id="'. $competence->id. '">';
            })
            ->addColumn('actions', function ($competence) {
                return '<a class="btn btn-xs btn-primary edit" href="/edit/competencias/'.$competence->id.'">Edit</a>
                        <button class="btn btn-xs btn-danger delete" data-id="'.$competence->id.'">Delete</button>';
            })
            ->addColumn('notas', function($competencia){
                $notas = $this->calcularNotasController->calcularNotasCompetencia(['estudiante' => $this->user->id, 'competencia' => $competencia->id, 'materia' => $this->materia]);
                $notas = number_format($notas, 1, '.');
                $progressBar = new progressBar($notas, 10);
                return Blade::renderComponent($progressBar);
            })
            ->rawColumns(['checkbox', 'actions', 'notas'])
            ->make(true);

    }

}
