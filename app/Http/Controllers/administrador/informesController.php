<?php

namespace App\Http\Controllers\administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Materia;
use App\Models\NotaFinalMateria;
use App\Models\NotaFinalCompetencia;
use App\Models\NotaRecuperacion;
use App\Models\Periodo;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use App\Services\getUserDataService;

class informesController extends Controller
{
    //
    public $grado;
    public $grupo;
    public $materia;
    public $tipoInformeAllowed = ['reprobados', 'materias'];
    public $materiasParaInforme = 1; //cantidad de materias perdidas con las que reprueba o genera informe

    public function generarInforme($grado, $grupo, $materia=null, $tipoInforme='reprobados')
    {
        $this->grado = $grado;
        $this->grupo = $grupo;
        $this->materia = $materia;
       

        if($tipoInforme == 'reprobados'){
            $this->materiasParaInforme = 3;
        }

        // 1. Obtener el Periodo (1 Consulta)
        $periodo = Periodo::where('fecha_fin', '>', now())->first();

        // Manejar si no hay periodo activo
        if (!$periodo) {
            return view('pages.administrador.informe', ['reprobados' => []]);
                   
        }
        $periodoId = $periodo->id - 1;
        if(date("j-n") >= "19-11"){
            $periodoId = $periodo->id;
        }

        
        $estudiantes = Usuario::select(
                'usuarios.id', 'usuarios.nombre', 'usuarios.apellido',
                'grados.id as gradoID', 'grados.grado',
                'grupos.id as grupoID', 'grupos.grupo'
            )
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'usuarios.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('usuario_grado', 'usuario_grado.usuario_id', '=', 'usuarios.id') // ASUNCIÓN
            ->join('grados', 'usuario_grado.grado_id', '=', 'grados.id')
            ->join('grupos', 'usuario_grado.grupo_id', '=', 'grupos.id')
            ->where('roles.name', 'estudiante')
            ->where(function ($query) {
                if($this->grado){
                    $query->where('grados.id', $this->grado);
                }
                if($this->grupo){
                    $query->where('grupos.id', $this->grupo);
                }
            })
            ->get();

        // 3. Obtener TODAS las materias, agrupadas por grado y grupo (1 Consulta)
        $materiasPorGradoGrupo = Materia::select('materias.id', 'base_materia.nombre_materia', 'materias.grado_id', 'materias.grupo_id')
            ->join('base_materia', 'base_materia.id', '=', 'materias.materia_id')
           ->where(function ($query) {
                  if($this->grado){
                        $query->where('materias.grado_id', $this->grado);
                    }
                    if($this->grupo){
                        $query->where('materias.grupo_id', $this->grupo);
                    }
                    if($this->materia){
                        $query->where('base_materia.id', $this->materia);
                    }
            })
            ->get()
            ->groupBy(function ($materia) {
                // Creamos una clave compuesta "gradoID-grupoID" para búsqueda rápida
                return $materia->grado_id . '-' . $materia->grupo_id;
            });
            // Obtener las IDs de las materias para el filtro
        $materiaIds = $materiasPorGradoGrupo->get($this->grado . '-' . $this->grupo)->pluck('id')->toArray();
        
        // 4. Obtener TODAS las notas finales
        //    Agrupadas por estudiante y luego por materia para búsqueda rápida
        $notasFinales = NotaFinalMateria::select('nota_final', 'periodo_id', 'materia_id', 'estudiante_id')
            ->where('periodo_id', '<=', $periodoId)
            ->whereIn('materia_id', $materiaIds)
            ->get()
            ->groupBy('estudiante_id') // Agrupa por estudiante
            ->map(function ($notasEstudiante) {
                return $notasEstudiante->groupBy('materia_id'); // Luego agrupa por materia
            });

        // 5. Obtener TODAS las notas de recuperación
        //    Agrupadas por estudiante, materia y finalmente por periodo_id
        $notasRecuperacion = NotaRecuperacion::select('nota_final', 'periodo_id', 'materia_id', 'estudiante_id')
            ->get()
            ->groupBy('estudiante_id') // Agrupa por estudiante
            ->map(function ($notasEstudiante) {
                return $notasEstudiante->groupBy('materia_id') // Luego por materia
                    ->map(function ($notasMateria) {
                        return $notasMateria->keyBy('periodo_id'); // Clave por periodo para lookup O(1)
                    });
            });


        $informe = [];

        // Bucle 1: Iterar sobre los estudiantes
        foreach ($estudiantes as $estudiante) {
            $materiasPerdidas = [];

            // Obtener las materias del estudiante desde la colección
            $key = $estudiante->gradoID . '-' . $estudiante->grupoID;
            $materiasEstudiante = $materiasPorGradoGrupo->get($key, collect());

            // Obtener todas las notas de este estudiante (búsqueda en memoria)
            $notasPorMateria = $notasFinales->get($estudiante->id, collect());
            $recuperacionesPorMateria = $notasRecuperacion->get($estudiante->id, collect());

            // Bucle 2: Iterar sobre las materias
            foreach ($materiasEstudiante as $materia) {
                
                // Obtener notas de esta materia (búsqueda en memoria)
                $notas = $notasPorMateria->get($materia->id, collect())->sortBy('periodo_id');
                
                // Obtener recuperaciones (búsqueda en memoria)
                $recuperacion = $recuperacionesPorMateria->get($materia->id, collect());

                if ($notas->isEmpty()) {
                    continue; // No hay notas para esta materia
                }

                // --- Lógica de 'promedioFinal' integrada ---
                // lo cual es un error. Esta versión usa los números reales y formatea solo el resultado final.
                $sumaPromedio = 0;
                
                foreach ($notas as $nota) {
                    $notaRecuperacion = $recuperacion->get($nota->periodo_id); // Búsqueda O(1)
                    
                    $notaFinalPeriodo = $nota->nota_final; // Es un número (float/int)

                    if ($notaRecuperacion) {
                        $sumaPromedio += max($notaFinalPeriodo, $notaRecuperacion->nota_final);
                    } else {
                        $sumaPromedio += $notaFinalPeriodo;
                    }
                }
                
                $promedioFinal = 0;
                if ($sumaPromedio > 0) {
                    $promedioFinal = $sumaPromedio / $notas->count();
                }
                // --- Fin de 'promedioFinal' ---

                if ($promedioFinal < 6) {
                    $materiasPerdidas[] = [
                        'materia' => $materia->nombre_materia,
                        'promedio' => round($promedioFinal, 2) // Redondeamos el promedio final
                    ];
                }
            } 

            if (count($materiasPerdidas) >= $this->materiasParaInforme) {
                $informe[] = [
                    'estudiante' => $estudiante->nombre . ' ' . $estudiante->apellido,
                    'cantidad materias perdidas' => count($materiasPerdidas),
                    'grado' => $estudiante->grado, // Ya tenemos este dato, no más consultas
                    'grupo' => $estudiante->grupo, // Ya tenemos este dato, no más consultas
                    'materias' => $materiasPerdidas
                ];
            }
        } 

        // 6. Retornar la vista (Total de 5 consultas)
        return $informe;
    }
}
