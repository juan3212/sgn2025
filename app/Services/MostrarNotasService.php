<?php

namespace App\Services;

use App\Models\NotaFinalCompetencia;
use App\Models\NotaFinalMateria;
use App\Models\Periodo;
use App\Services\getUserDataService;

class MostrarNotasService
{
    public $getUserDataService;
    public $isAdmin;
    public $isTeacher;
    public function __construct()
    {
        $this->getUserDataService = new getUserDataService;
        $userData = $this->getUserDataService->getUserDataFromAuth();
        $this->isAdmin = $userData['isAdmin'];
        $this->isTeacher = $userData['isTeacher'];
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

    public function mostrarNotasMateria($estudianteId, $materiaId)
    {
        $nota = NotaFinalMateria::select('nota_final')
        ->where('estudiante_id', $estudianteId)
        ->where('materia_id', $materiaId)
        ->where('periodo_id', $this->calcularPeriodo())
        ->first();
        return $nota->nota_final ?? 0;
    }
}