<?php

namespace App\Services;

use App\Models\NotaFinalCompetencia;
use App\Models\NotaFinalMateria;
use App\Services\CalcularNotasService;
use Illuminate\Support\Facades\DB;

class CambiarCompetenciaPorcentajeService
{
    public function __construct()
    {
        //
    }

    public function changeCompetenciaPorcentaje($competenciaId, $porcentaje, $materias)
    {
        $calcularNotas = new CalcularNotasService();
        $competencias = NotaFinalCompetencia::select('notas_finales_competencias.*', 'competencias.porcentaje')
        ->join('competencias', 'notas_finales_competencias.competencia_id', '=', 'competencias.id')
        ->where('notas_finales_competencias.competencia_id', $competenciaId)
        ->get();
        foreach ($competencias as $competencia) {
            $notaCompetencia = $competencia->nota_final / $competencia->porcentaje * $porcentaje;
            $notaCompetencia = round($notaCompetencia, 2);
            
        }

        foreach ($materias as $materia) {
            
           $nota = $calcularNotas->calcularNotasMateriaPeriodo([
            'materia' => $materia['materia_id'],
            'estudiante' => $materia['estudiante_id'],
            'periodo' => $materia['periodo_id']
            ]); 
            $notaFinalMateria = NotaFinalMateria::findOrFail($materia['id']);
            $notaFinalMateria->nota_final = $nota;
            $notaFinalMateria->save();
        };
        
        return true;

    }
}