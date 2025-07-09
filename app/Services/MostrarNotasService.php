<?php

namespace App\Services;

use App\Models\NotaFinalCompetencia;
use App\Models\NotaFinalMateria;
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
        $fecha = date('m.d');
        switch (true) {
            case $fecha >= '01.01' && $fecha <= '04.30':
                return 1;
            case $fecha >= '05.01' && $fecha <= '06.30':
                return 1;
            case $fecha >= '07.01' && $fecha <= '09.30':
                return 1;
            default:
                return 4;
        }
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
        ->first()
        ->toArray();
        $nota =  $nota['nota_final'] / $nota['porcentaje'] * 100;
        return $nota ?? 0;
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