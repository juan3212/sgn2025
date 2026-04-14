<?php

namespace App\Services;

use App\Models\NotaFinalCompetencia;
use App\Models\Competencia;
use App\Models\NotaFinalMateria;
use App\Models\Periodo;
use App\Services\getUserDataService;

class MostrarNotasService
{
    public $getUserDataService;
    public $isAdmin;
    public $isTeacher;
    public function __construct($forIndividual = true)
    {
        if ($forIndividual) {
            $this->getUserDataService = new getUserDataService;
            $userData = $this->getUserDataService->getUserDataFromAuth();
            $this->isAdmin = $userData['isAdmin'];
            $this->isTeacher = $userData['isTeacher'];
        }
    }

    public function calcularPeriodo()
    {
      $periodo = Periodo::where('fecha_inicio', '<=', date('Y-m-d'))
      ->where('fecha_fin', '>=', date('Y-m-d'))
      ->first();
      return $periodo->id;
    }

    public function mostrarNotasCompetencia($estudianteId, $competenciaId, $materiaId)
    {
        if ($this->isAdmin || $this->isTeacher) {
            return 0;
        }

        $nota = NotaFinalCompetencia::select('nota_final', 'porcentaje')
        ->join('competencias', 'competencia_id', '=', 'competencias.id')
        ->where('competencia_id', $competenciaId)
        ->where('estudiante_id', $estudianteId)
        ->where('materia_id', $materiaId)
        ->first();
        if (!$nota) {
            return 0;
        }
        $nota =  $nota->nota_final / $nota->porcentaje * 100;
        return $nota;
    }

    public function mostrarNotasMateria($estudianteId, $materiaId, $periodoId = null, $withEvaluation = true)
    {
        $nota = NotaFinalMateria::select('nota_final')
        ->where('estudiante_id', $estudianteId)
        ->where('materia_id', $materiaId)
        ->where('periodo_id', $periodoId??$this->calcularPeriodo())
        ->first();

        if($withEvaluation || $this->isAdmin || $this->isTeacher) {
            return $nota->nota_final ?? 0;
        }
        $evaluation = $this->getEvaluationCompetencia($estudianteId, $materiaId);
        return $nota->nota_final - $evaluation ?? 0;
    }

    private function getEvaluationCompetencia($estudianteId, $materiaId)
    {
        $competencias = Competencia::where(function ($query) {
            $query->where('nombre', 'LIKE', '%assessment%')
                  ->orWhere('nombre', 'LIKE', '%evaluation%')
                  ->orWhere('nombre', '=', 'E')
                  ->orWhere('nombre', 'LIKE', '%sment%')
                  ->orWhere('nombre', 'LIKE', '%exam%');
        })
        ->join('materia_has_competencia', 'id', '=', 'materia_has_competencia.competencia_id')
        ->where('nombre', 'NOT LIKE', '%C')
        ->where('materia_has_competencia.materia_id', $materiaId)
        ->get();

        if ($competencias->isEmpty()) {
            return 0;
        }
        $nota = 0;
        foreach ($competencias as $competencia) {
            $evaluationNota = NotaFinalCompetencia::where('estudiante_id', $estudianteId)
                ->where('competencia_id', $competencia->id)
                ->where('materia_id', $materiaId)
                ->first();
            if ($evaluationNota) {
                $nota += $evaluationNota->nota_final;
            }
        }
        return $nota;
    }
}
