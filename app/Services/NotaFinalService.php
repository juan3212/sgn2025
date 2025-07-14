<?php

namespace App\Services;
use App\Models\Actividad;
use App\Models\NotaFinalCompetencia;
use App\Models\NotaFinalMateria;
use Illuminate\Support\Facades\DB;
use App\Services\CalcularNotasService;

class NotaFinalService
{

    public $nota;
    public $actividadId;
    public $materiaId;
    public $competenciaId;
    public $periodoId;
    public $estudianteId;
    public $porcentaje;

    public function __construct($nota, $actividadId, $estudianteId)
    {
        $this->nota = $nota;
        $this->actividadId = $actividadId;
        $this->estudianteId = $estudianteId;
        $this->getDataFromActividad();
    }

    public function getDataFromActividad()
    {
        $actividad = Actividad::select('actividades.*', 'competencias.*')
            ->join('competencias', 'actividades.competencia_id', '=', 'competencias.id')
            ->where('actividades.id', $this->actividadId)
            ->first();
        if(!$actividad) {
            return response()->json(['error' => 'Actividad no encontrada'], 404);
        }
        
        $this->materiaId = $actividad->materia_id;
        $this->porcentaje = $actividad->porcentaje / 100;
        $this->competenciaId = $actividad->competencia_id;
        $this->periodoId = $actividad->periodo_id;
    }

    public function updateNotaFinalCompetencia()
    {
        $calcularNotas = new CalcularNotasService();
        $nuevaNota = $calcularNotas->calcularNotasCompetencia([
            'competencia' => $this->competenciaId,
            'estudiante' => $this->estudianteId,
            'materia' => $this->materiaId
        ]);
        return $nuevaNota * $this->porcentaje;
    }



    public function calcularNotaFinalMateria()
    {
        $calcularNotas = new CalcularNotasService();
        $notaActualMateria = $calcularNotas->calcularNotasMateriaPeriodo([
            'materia' => $this->materiaId,
            'estudiante' => $this->estudianteId,
            'periodo' => $this->periodoId
        ]);
        return $notaActualMateria;
    }


    public function getNotaActualData()
    {
        $notaCompetencia = NotaFinalCompetencia::select('id','nota_final', 'competencia_id')
            ->where('estudiante_id', $this->estudianteId)
            ->where('materia_id', $this->materiaId)
            ->where('competencia_id', $this->competenciaId)
            ->first();
        
        $notaMateria = NotaFinalMateria::select('id','nota_final')
            ->where('estudiante_id', $this->estudianteId)
            ->where('materia_id', $this->materiaId)
            ->where('periodo_id', $this->periodoId)
            ->first();

        if($notaCompetencia && $notaMateria) {
            return [
                'notaCompetencia' => $notaCompetencia['nota_final'],
                'notaCompetenciaId'=>$notaCompetencia['competencia_id'],
                'notaMateria' => $notaMateria['nota_final'],
            ];
        }

        return [
            'notaCompetencia' => 0,
            'notaMateria' => 0,
            'notaCompetenciaId' => null
        ];
    }

    public function updateNotaFinal()
    {
        $notaCompetencia = $this->updateNotaFinalCompetencia();
        try{
            DB::beginTransaction();
            DB::table('notas_finales_competencias')->updateOrInsert(
                [
                    'estudiante_id' => $this->estudianteId,
                    'materia_id' => $this->materiaId,
                    'competencia_id' => $this->competenciaId
                ],
                [
                    'nota_final' => $notaCompetencia
                ]
            );
            $notaMateria = $this->calcularNotaFinalMateria();

            DB::table('notas_finales_materias')->updateOrInsert(
                [
                    'estudiante_id' => $this->estudianteId,
                    'materia_id' => $this->materiaId,
                    'periodo_id' => $this->periodoId
                ],
                [
                    'nota_final' => $notaMateria
                ]
            );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}