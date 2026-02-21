<?php

namespace App\Services;

use App\Models\Materia;
use App\Models\NotaFinalMateria;
use App\Models\Actividad;
use App\Models\Nota;
use App\Models\NotaFinalCompetencia;
use App\Models\UsuarioGrado;
use Illuminate\Support\Facades\DB;

class CambiarGradoUsuarioService
{
    public function __construct()
    {
        //
    }

    public function changeGradeUser($usuarioId, $gradoActual, $cursoActual, $nuevoGrado, $nuevoCurso, $periodo)
    {
        $isTheSameGrade = $gradoActual == $nuevoGrado;
        $materiasNewGrade = Materia::with([
            'competencias' => function ($query) use ($periodo, $usuarioId)
            {
                $query->with(['notasCompetencia'=> function($query) use ($periodo, $usuarioId){
                    $query->where('estudiante_id', $usuarioId);
                }]);
            }, 
            'notasMateria' => function ($query) use ($periodo, $usuarioId) {
                $query->where('estudiante_id', $usuarioId);
                $query->where('periodo_id', $periodo);
            },
            'actividades' => function ($query) use ($periodo, $usuarioId) {
                $query->with(['notas' => function ($query) use ($periodo, $usuarioId) {
                    $query->where('estudiante_id', $usuarioId);
                }]);
                $query->where('periodo_id', $periodo);
            }])->where('grado_id', $nuevoGrado)->where('grupo_id', $nuevoCurso)->get();
        $materiasCurrent = Materia::with([
            'competencias' => function ($query) use ($periodo, $usuarioId)
            {
                $query->with(['notasCompetencia'=> function($query) use ($periodo, $usuarioId){
                    $query->where('estudiante_id', $usuarioId);
                }]);
            }, 
            'notasMateria' => function ($query) use ($periodo, $usuarioId) {
                $query->where('estudiante_id', $usuarioId);
                $query->where('periodo_id', $periodo);
            },
            'actividades' => function ($query) use ($periodo, $usuarioId) {
                $query->with(['notas' => function ($query) use ($periodo, $usuarioId) {
                    $query->where('estudiante_id', $usuarioId);
                }]);
                $query->where('periodo_id', $periodo);
            }])->where('grado_id', $gradoActual)->where('grupo_id', $cursoActual)->get();
        dump($materiasCurrent);
        dump($materiasNewGrade);

        $materiasAreEqual = $materiasCurrent->pluck('materia_id')->diff($materiasNewGrade->pluck('materia_id'))->isEmpty();
        dump($materiasAreEqual);

        DB::beginTransaction();
        try {
            foreach($materiasCurrent as $materia){
                $materiaNew = $materiasNewGrade->firstWhere('materia_id', $materia->materia_id);
                if($materiaNew){
                    $competenciasNew = $materiaNew->competencias;
                    $competenciasCurrent = $materia->competencias;
                    $competenciasAreEqual = $competenciasCurrent->pluck('id')->diff($competenciasNew->pluck('id'))->isEmpty();


                    if($competenciasAreEqual){
                        $actividadesNew = $materiasNewGrade->firstWhere('materia_id', $materia->materia_id)->actividades;
                        $actividadesCurrent = $materia->actividades;
                        $actividadesAreEqual = $actividadesCurrent->pluck('competencia_id')->diff($actividadesNew->pluck('competencia_id'))->isEmpty();
                        
                        dump($actividadesCurrent);
                        dump($actividadesNew);
                        if($actividadesCurrent->isNotEmpty()){
                            $notasCurrent = $actividadesCurrent->pluck('notas')->flatten();
                        
                            if($notasCurrent->isNotEmpty()){
                                
                                $newNotas = $notasCurrent->map(function($nota) use ($actividadesNew, $actividadesCurrent, $materiaNew){
                                    $currentActivityName = $actividadesCurrent->firstWhere('id', $nota->actividad_id)->nombre;
                                    $currentActivityDescripcion = $actividadesCurrent->firstWhere('id', $nota->actividad_id)->descripcion;
                                    $currentActivityCompetencia = $actividadesCurrent->firstWhere('id', $nota->actividad_id)->competencia_id;
                                    $actividadNewId = $actividadesNew->firstWhere('nombre', $currentActivityName)
                                        ->where('descripcion', $currentActivityDescripcion)
                                        ->where('competencia_id', $currentActivityCompetencia)
                                        ->where('materia_id', $materiaNew->id)
                                        ->first();
                                    dump($actividadNewId);
                                    if($actividadNewId){
                                        $saveNota = Nota::updateOrCreate([
                                            'estudiante_id' => $nota->estudiante_id,
                                            'actividad_id' => $actividadNewId->id,
                                        ], [
                                            'valor' => $nota->valor,
                                        ]);
                                        return ["save"=> $saveNota, "new"=>[
                                            'estudiante_id' => $nota->estudiante_id,
                                            'actividad_id' => $actividadNewId->id,
                                            'valor' => $nota->valor,
                                        ]];
                                    }
                                });

                                $notaMateriaCurrent = $materia->notasMateria;
                                $notaMateriaNew = NotaFinalMateria::updateOrCreate([
                                    'estudiante_id' => $usuarioId,
                                    'materia_id' => $materiaNew->id,
                                    'periodo_id' => $periodo,
                                ], [
                                    'nota_final' => $notaMateriaCurrent->first()->nota_final,
                                ]);

                                $notasCompetenciasCurrent = $materia->competencias->pluck('notasCompetencia')->flatten();
                                foreach($notasCompetenciasCurrent as $notaCompetencia){
                                    dump($notaCompetencia);
                                    $notaCompetenciaNew = NotaFinalCompetencia::updateOrCreate([
                                        'estudiante_id' => $usuarioId,
                                        'materia_id' => $materiaNew->id,
                                        'competencia_id' => $notaCompetencia->competencia_id,
                                    ], [
                                        'nota_final' => $notaCompetencia->nota_final,
                                    ]);
                                }  

                            }
                        }
                    
                    }
                }
            }

            UsuarioGrado::where('usuario_id', $usuarioId)->update([
                'grado_id' => $nuevoGrado,
                'grupo_id' => $nuevoCurso,
            ]);

            DB::commit();
         } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    

   /* public function cambiarGradoUsuario($usuarioId, $gradoActual, $cursoActual, $nuevoGrado, $nuevoCurso, $periodo)
    {
        $isTheSameGrade = $gradoActual == $nuevoGrado;
        $materiasNewGrade = Materia::where('grado_id', $nuevoGrado)->where('grupo_id', $nuevoCurso)->get();

        $notasMateria = NotaFinalMateria::select('notas_finales_materias.nota_final', 'notas_finales_materias.materia_id', 'materias.materia_id as materia_id_base')
        ->where('estudiante_id', $usuarioId)
        ->where('periodo_id', $periodo)
        ->join('materias', 'materias.id', '=', 'notas_finales_materias.materia_id')
        ->get();

        if ($isTheSameGrade) {
            foreach($notasMateria as $nota){
                $newMateria = $materiasNewGrade->firstWhere('materia_id', $nota->materia_id_base);

                $actividades = Actividad::where('materia_id', $newMateria->id)->get();
                if(!$actividades->isEmpty()){
                    $newNotas = [];
                    foreach($actividades as $actividad){  
                        $newNotas[]=[
                            'estudiante'=>$usuarioId,
                            'actividad_id'=>$actividad->id,
                            'valor'=>$nota->nota_final,
                            'materiaOld'=>$nota->materia_id,
                            'materiaNew'=>$newMateria->id,
                            'periodo'=>$periodo,
                        ];

                        DB::beginTransaction();
                        try {
                            $this->updateNotas($usuarioId, $actividad->id, $nota->nota_final);
                            $this->updateGradoUsuario($usuarioId, $gradoActual, $cursoActual, $nuevoGrado, $nuevoCurso);
                            $this->updateNotasFinalesMateria($usuarioId, $newMateria->id, $nota->nota_final, $periodo);
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            throw $e;
                        }
                    }
                }
            }   
        }
    }

    private function updateNotas($usuarioId, $actividadId, $valor)
    {
        Nota::updateOrCreate([
            'estudiante_id' => $usuarioId,
            'actividad_id' => $actividadId,
        ], [
            'valor' => $valor,
        ]);
    }

    private function updateNotasFinalesMateria($usuarioId, $materiaId, $valor, $periodo)
    {
        NotaFinalMateria::updateOrCreate([
            'estudiante_id' => $usuarioId,
            'materia_id' => $materiaId,
            'periodo_id' => $periodo,
        ], [
            'nota_final' => $valor,
        ]);
    }

    private function updateGradoUsuario($usuarioId, $gradoActual, $cursoActual, $nuevoGrado, $nuevoCurso)
    {
        UsuarioGrado::where('usuario_id', $usuarioId)->update([
            'grado_id' => $nuevoGrado,
            'grupo_id' => $nuevoCurso,
        ]);
    }*/
}