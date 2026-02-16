<?php

namespace App\Services;

use App\Models\Materia;
use App\Models\NotaFinalMateria;
use App\Models\Actividad;
use App\Models\Nota;
use Illuminate\Support\Facades\DB;
use App\Models\UsuarioGrado;

class CambiarGradoUsuarioService
{
    public function __construct()
    {
        //
    }

    public function cambiarGradoUsuario($usuarioId, $gradoActual, $cursoActual, $nuevoGrado, $nuevoCurso, $periodo)
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
    }
}