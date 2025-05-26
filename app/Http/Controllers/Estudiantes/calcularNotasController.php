<?php

namespace App\Http\Controllers\Estudiantes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\Competencia;
use App\Models\Materia;
use PhpParser\Node\Expr\Cast\Array_;

class calcularNotasController extends Controller
{
    //

    public function promedioNotas(Array $notas)
    {
        $notasSuma = array_sum($notas);
        $promedio = $notasSuma / count($notas);
        $promedio = round($promedio, 2);
        return $promedio;
    }
    public function notasActividad(Array $data)
    {
        $actividad = $data['actividad'];
        $estudiante = $data['estudiante'];
        $notas = Nota::where('actividad_id', $actividad)
        ->where('estudiante_id', $estudiante)
        ->get()
        ->toArray();
        if(!$notas){
            return 0;
        }
       $notas = $notas[0]['valor'];
        return $notas;
    }

    public function calcularNotasCompetencia(Array $data)
    {
        
        $estudiante = $data['estudiante'];
        $competencia = $data['competencia'];
        $materia = $data['materia'];
        $notas = Competencia::select('notas.*')
        ->join('materia_has_competencia', 'materia_has_competencia.competencia_id', '=', 'competencias.id')
        ->join('actividades', 'actividades.competencia_id', '=', 'competencias.id')
        ->join('notas', 'notas.actividad_id', '=', 'actividades.id')
        ->where('actividades.materia_id', $materia)
        ->where('actividades.competencia_id', $competencia)
        ->where('notas.estudiante_id', $estudiante)
        ->get();
        $notas = $notas->toArray();
        if(!$notas){
            return 0;
        }
        $notas = array_column($notas, 'valor');
        $promedio = $this->promedioNotas($notas);
        return $promedio;
    }

    public function calcularNotasMateria(Array $data)
    {
        $materia = $data['materia'];
        $estudiante = $data['estudiante'];
        $competencias = Materia::select('competencias.*')
        ->join('materia_has_competencia', 'materia_has_competencia.materia_id', '=', 'materias.id')
        ->join('competencias', 'materia_has_competencia.competencia_id', '=', 'competencias.id')
        ->where('materias.id', $materia)
        ->get()
        ->toArray();
        if(!$competencias){
            return  0;
        }
        $notas = [];
        foreach ($competencias as $competencia) {
            $nota = $this->calcularNotasCompetencia(['competencia' => $competencia['id'], 'estudiante' => $estudiante, 'materia' => $materia]);
            $notas[] = $nota * ($competencia['porcentaje']/100);
        }

        $promedio = $this->promedioNotas($notas);
        return $promedio;
    }
}
