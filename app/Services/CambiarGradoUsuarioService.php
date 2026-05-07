<?php

namespace App\Services;

use App\Models\Materia;
use App\Models\NotaFinalMateria;
use App\Models\Actividad;
use App\Models\Nota;
use App\Models\NotaFinalCompetencia;
use App\Models\UsuarioGrado;
use App\Models\NotaRecuperacion;
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
            'recuperaciones' => function ($query) use ($periodo, $usuarioId) {
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
                        dump($materia);
                        dump($actividadesAreEqual);

                        if($actividadesAreEqual){

                            if($actividadesCurrent->isNotEmpty()){
                                $notasCurrent = $actividadesCurrent->pluck('notas')->flatten();

                                if($notasCurrent->isNotEmpty()){

                                    $newNotas = $notasCurrent->map(function($nota) use ($actividadesNew, $actividadesCurrent, $materiaNew) {

                                        $currentActivity = $actividadesCurrent->firstWhere('id', $nota->actividad_id);
                                        $actividadNewId = null;

                                        // 1. Intento de Búsqueda Exacta
                                        $actividadNewId = $actividadesNew->where('nombre', $currentActivity->nombre)
                                            ->where('competencia_id', $currentActivity->competencia_id)
                                            ->where('materia_id', $materiaNew->id)
                                            ->first();


                                        // 2. NUEVO Fallback 3: Mapeo posicional por orden de creación (Tu idea)
                                        // Se ejecuta solo si todo lo anterior falló
                                        if (!$actividadNewId) {
                                            // Obtenemos las actividades de esa competencia específica y las ordenamos por ID
                                            $oldActsInComp = $actividadesCurrent->where('competencia_id', $currentActivity->competencia_id)->sortBy('id')->values();
                                            $newActsInComp = $actividadesNew->where('competencia_id', $currentActivity->competencia_id)->sortBy('id')->values();

                                            // Validamos si tienen la misma cantidad de actividades
                                            if ($oldActsInComp->count() > 0 && $oldActsInComp->count() === $newActsInComp->count()) {

                                                // Buscamos qué posición (índice 0, 1, 2...) ocupa la actividad actual en el curso viejo
                                                $index = $oldActsInComp->search(function ($item) use ($currentActivity) {
                                                    return $item->id === $currentActivity->id;
                                                });

                                                // Si encontramos la posición, asignamos la actividad del curso nuevo que esté en esa misma posición
                                                if ($index !== false) {
                                                    $actividadNewId = $newActsInComp->get($index);
                                                }
                                            }
                                        }

                                        // Guardado de la nota si encontramos una equivalencia en cualquiera de los pasos
                                        if ($actividadNewId) {
                                            $saveNota = Nota::updateOrCreate([
                                                'estudiante_id' => $nota->estudiante_id,
                                                'actividad_id' => $actividadNewId->id,
                                            ], [
                                                'valor' => $nota->valor,
                                            ]);

                                            return [
                                                "save" => $saveNota,
                                                "new" => [
                                                    'estudiante_id' => $nota->estudiante_id,
                                                    'actividad_id' => $actividadNewId->id,
                                                    'valor' => $nota->valor,
                                                ]
                                            ];
                                        }
                                    });

                                    $notaMateriaCurrent = $materia->notasMateria;
                                    if (!$notaMateriaCurrent->isEmpty()) {
                                        dump($notaMateriaCurrent);
                                        $notaMateriaNew = NotaFinalMateria::updateOrCreate([
                                            'estudiante_id' => $usuarioId,
                                            'materia_id' => $materiaNew->id,
                                            'periodo_id' => $periodo,
                                        ], [
                                            'nota_final' => $notaMateriaCurrent->first()->nota_final?? 0,
                                        ]);
                                    }

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

                                    $recuperacionesCurrent = $materia->recuperaciones;
                                    dump($recuperacionesCurrent);
                                    $recuperacionesNew = NotaRecuperacion::updateOrCreate([
                                        'estudiante_id' => $usuarioId,
                                        'materia_id' => $materiaNew->id,
                                        'periodo_id' => $periodo,
                                    ], [
                                        'nota_recuperacion' => $recuperacionesCurrent->first()->nota_final ?? 0,
                                    ]);

                                }
                            }
                        }else{
                            if($actividadesCurrent->count() > $actividadesNew->count()){
                                $notasCurrent = $actividadesCurrent->pluck('notas')->flatten();
                                $actividadesCurrent->each(function($actividad) use ($actividadesNew, $materiaNew, $usuarioId){
                                    $newActividad = Actividad::create([
                                        'nombre' => $actividad->nombre,
                                        'descripcion' => $actividad->descripcion,
                                        'tipo_nota' => $actividad->tipo_nota,
                                        'competencia_id' => $actividad->competencia_id,
                                        'materia_id' => $materiaNew->id,
                                        'periodo_id' => $actividad->periodo_id,
                                        'porcentaje' => $actividad->porcentaje,
                                    ]);
                                    $nota = $actividad->notas->first();
                                    dump($nota);
                                    Nota::create([
                                        'estudiante_id' => $usuarioId,
                                        'actividad_id' => $newActividad->id,
                                        'valor' => $nota->valor??0,
                                    ]);
                                });
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


    public function changeGradeUserAllPeriods($usuarioId, $gradoActual, $cursoActual, $nuevoGrado, $nuevoCurso, $periodo)
    {
        $isTheSameGrade = $gradoActual == $nuevoGrado;
        $update = $isTheSameGrade;
        dump($update);
        $materiasNewGrade = Materia::where('grado_id', $nuevoGrado)->where('grupo_id', $nuevoCurso)->get();

        $materias = Materia::where('grado_id', $gradoActual)->where('grupo_id', $cursoActual)->get();

        dump($materias);
        dump($materiasNewGrade);


        DB::beginTransaction();
        try {
            foreach ($materias as $materia) {
                dump($materia->materia_id);
                $newMateriaId = $materiasNewGrade->where('materia_id', $materia->materia_id)->first()->id ?? null;
                if ($newMateriaId === null) {
                    continue;
                }


                dump($newMateriaId, $materia->id);
                $notasCompetencias = NotaFinalCompetencia::where('estudiante_id', $usuarioId)
                    ->join('competencias', 'competencias.id', '=', 'notas_finales_competencias.competencia_id')
                    ->where('notas_finales_competencias.materia_id', $materia->id)
                    ->where('competencias.periodo_id', '<', $periodo)
                    ->select('notas_finales_competencias.*');

                $notasCompetencias->each(function ($query) use ($newMateriaId, $update, $periodo, $usuarioId) {
                    if ($update) {
                        $query->update(['materia_id' => $newMateriaId]);
                    } else {
                        dump($query->nota_final);

                        NotaFinalCompetencia::create([
                            'estudiante_id' => $usuarioId,
                            'competencia_id' => $query->competencia_id,
                            'materia_id' => $newMateriaId,
                            'periodo_id' => $periodo,
                            'nota_final' => $query->nota_final,
                        ]);
                    }
                });


                $notaFinalMateria = NotaFinalMateria::where('estudiante_id', $usuarioId)
                    ->where('periodo_id', '<', $periodo)
                    ->where('materia_id', $materia->id)
                    ->select('notas_finales_materias.*');

                $notaFinalMateria->each(function ($query) use ($newMateriaId, $update, $usuarioId) {
                    if ($update) {
                        $query->update(['materia_id' => $newMateriaId]);
                    } else {
                        dump($query->nota_final);

                        NotaFinalMateria::create([
                            'estudiante_id' => $usuarioId,
                            'materia_id' => $newMateriaId,
                            'periodo_id' => $query->periodo_id,
                            'nota_final' => $query->nota_final,
                        ]);
                    }
                });

                $notaRecuperacion = NotaRecuperacion::where('estudiante_id', $usuarioId)
                    ->where('periodo_id', '<', $periodo)
                    ->where('materia_id', $materia->id)
                    ->select('notas_recuperaciones.*');
                $notaRecuperacion->each(function ($query) use ($newMateriaId, $usuarioId, $update) {
                    if ($update) {
                        $query->update(['materia_id' => $newMateriaId]);
                    } else {
                        dump($query->nota_final);
                        NotaRecuperacion::create([
                            'estudiante_id' => $usuarioId,
                            'materia_id' => $newMateriaId,
                            'periodo_id' => $query->periodo_id,
                            'nota_recuperacion' => $query->nota_final,
                        ]);
                    }
                });

                dump($notaFinalMateria);

            }
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
