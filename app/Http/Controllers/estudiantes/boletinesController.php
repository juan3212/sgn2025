<?php

namespace App\Http\Controllers\estudiantes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\getUserDataService;
use App\Models\Materia;
use App\Models\NotaFinalMateria;
use App\Models\NotaFinalCompetencia;
use Illuminate\Support\Facades\DB;
class boletinesController extends Controller
{
    //
    public $estudianteID;
    public $user;
    public $year;
    public $month;
    public $date;
    public $day;
    public $periodo;
    public function getStudentData()
    {
        $user = new getUserDataService;
        $user = $user->getUserDataFromID($this->estudianteID);
        return $user;
    }

    public function getDate()
    {
        $this->date = date('d F Y');
        $this->year = date('Y');
        $this->month = date('m');
        $this->day = date('d');
    }

    public function getSubjects()
    {
        $subjects = Materia::where('grado', $this->user['grados'])
        ->where('grupo', $this->user['grupos'])
        ->get();
        return $subjects;
    }

    public function getNotasMateria($materiaId, $estudianteId)
    {
        $notas = NotaFinalMateria::where('materia_id', $materiaId)
        ->where('estudiante_id', $estudianteId)
        ->get();
        return $notas;
    }

    public function getCompetencias($materiaId, $periodoId)
    {
        $competencias = DB::table('competencias_has_materias')
        ->select('competencia.id','competencia.periodo_id', 'competencia.descripcion')
        ->join('competencias', 'competencias.id', '=', 'competencias_has_materias.competencia_id')
        ->where('competencias_has_materias.materia_id', $materiaId)
        ->where('competencias.periodo_id', $periodoId)
        ->get();
        return $competencias;
    }
    public function getNotasCompetencia($estudianteId, $materiaId, $competenciaId)
    {
        $notas = NotaFinalCompetencia::where('estudiante_id', $estudianteId)
        ->where('materia_id', $materiaId)
        ->where('competencia_id', $competenciaId)
        ->get();
        return $notas;
    }
    
    public function calcularNotas($periodoId)
    {
        $materias = $this->getSubjects();
        $competencias = $this->getCompetencias($materiaId, $this->periodoId);
        foreach ($materias as $materia) {
            $notas = $this->getNotasMateria($materia->id, $this->estudianteID, $periodoId);
        }

    }

    public function getPeriod()
    {
        switch(true){
            case $this->month >= 1 && $this->month <= 7:
                $this->periodo = 1;
                break;
            case $this->month >= 8 && $this->month <= 9:
                $this->periodo = 2;
                break;
            case $this->month >= 10 && $this->month <= 11:
                $this->periodo = 3;
                break;
            default:
                $this->periodo = 4;
                break;
        }
    }
    public function render($estudianteID)
    {
        $this->estudianteID = $estudianteID;
        $this->user = $this->getStudentData();
        $this->getDate();
        return view('pages.estudiantes.boletin', [
            'user' => $this->user,
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day
        ]);
    }
}
