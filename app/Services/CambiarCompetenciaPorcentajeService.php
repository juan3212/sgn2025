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

    public function changeCompetenciaPorcentaje($competenciaId, $porcentaje, $materias, $periodo)
    {
        $calcularNotas = new CalcularNotasService(); 
        $competencias = NotaFinalCompetencia::select('notas_finales_competencias.*', 'competencias.porcentaje')
        ->join('competencias', 'notas_finales_competencias.competencia_id', '=', 'competencias.id')
        ->where('notas_finales_competencias.competencia_id', $competenciaId)
        ->get()
        ->toArray();
        foreach ($competencias as $competencia) {
            $porcentajeCompetencia = floatval($competencia['porcentaje'] / 100);
            $porcentajeNuevo = floatval($porcentaje / 100);
            $newNotaCompetencia = (floatval($competencia['nota_final']) / $porcentajeCompetencia) * $porcentajeNuevo;
            $notaCompetencia = round($newNotaCompetencia, 2);
            $competenciaSave = NotaFinalCompetencia::find($competencia['id']);
            $competenciaSave->nota_final =  $notaCompetencia;
            $competenciaSave->save();
        }

        $notaMaterias = [];
        foreach ($materias as $materia) {
            $notaMateria = NotaFinalMateria::where('materia_id', $materia['id'])
            ->where('periodo_id', $periodo)
            ->get()
            ->toArray();
            array_push($notaMaterias,...$notaMateria);
        }

        foreach ($notaMaterias as $notaMateria) {
           $nota = $calcularNotas->calcularNotasMateriaPeriodo([
            'materia' => $notaMateria['materia_id'],
            'estudiante' => $notaMateria['estudiante_id'],
            'periodo' => $notaMateria['periodo_id']
            ]); 
            $notaFinalMateria = NotaFinalMateria::findOrFail($notaMateria['id']);
            $notaFinalMateria->nota_final = $nota;
            $notaFinalMateria->save();
        };
        
        return true;

    }
}