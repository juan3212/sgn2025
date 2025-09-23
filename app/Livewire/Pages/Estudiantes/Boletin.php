<?php

namespace App\Livewire\Pages\Estudiantes;

use App\Models\Materia;
use App\Models\NotaFinalMateria;
use App\Models\NotaFinalCompetencia;
use App\Models\NotaRecuperacion;
use App\Models\Periodo;
use App\Models\Usuario;
use App\Services\getUserDataService;
use Livewire\Component;

class Boletin extends Component
{
    public $materiasNotas= [];
    public $periodoId;
    public $estudianteID;
    public $user;
    public $directorCurso;
    public $directorCursoNombre;

    public function boot()
    {
        $this->getStudentData();
        $this->Periodos();
        $this->setNotas();
    }
    public function mount($estudianteID)
    {
        $this->estudianteID = $estudianteID;
    }
    public function getStudentData()
    {
        $user = new getUserDataService;
        $user = $user->getUserDataFromID($this->estudianteID);
        $this->user = $user;
    }

    public function getDirectorCurso()
    {
        $director = Usuario::find($this->directorCurso);
        if($director){
            $nombreDirector = $director->nombre . ' ' . $director->apellido;
            $this->dispatch('directorCursoNombre', $nombreDirector);
        }else{
            $this->dispatch('directorCursoNombre', 'N/A');
        }
    }

    public function render()
    {
        return view('livewire.pages.estudiantes.boletin');
    }

    public function Materias()
    {
        $materias = Materia::select('materias.id', 'base_materia.nombre_materia', 'materias.intensidad_horaria', 'materias.profesor_id')
        ->join('base_materia', 'base_materia.id', '=', 'materias.materia_id')
        ->where('grado_id', $this->user['gradoID'])
        ->where('grupo_id', $this->user['grupoID'])
        ->get();
        return $materias;
    }

    public function NotasMateria($materiaId)
    {
        $notas = NotaFinalMateria::select('nota_final', 'periodo_id')
        ->where('materia_id', $materiaId)
        ->where('estudiante_id', $this->user['id'])
        ->where('periodo_id', '<=', $this->periodoId)
        ->orderBy('periodo_id', 'asc')
        ->get();
        $notas->each(function ($nota) {
            $nota->nota_final = number_format($nota->nota_final, 2);
        });
        return $notas;
    }

    public function Periodos()
    {
        $periodo = Periodo::where('fecha_fin', '>', now())
        ->first();
        $this->periodoId = $periodo->id - 1;
    }

    public function NotasCompetencias($materiaId)
    {
        $notasCompetencias = NotaFinalCompetencia::select('competencias.descripcion', 'competencias.porcentaje', 'notas_finales_competencias.nota_final')
        ->where('estudiante_id', $this->user['id'])
        ->where('materia_id', $materiaId)
        ->join('competencias', 'competencias.id', '=', 'notas_finales_competencias.competencia_id')
        ->where('competencias.periodo_id', $this->periodoId)
        ->get();

        $notasCompetencias->each(function ($nota) {
            $nota->nota_final = number_format($nota->nota_final / ($nota->porcentaje / 100), 2);
        });
        return $notasCompetencias; 
    }
    public function NotasRecuperacion($materiaId)
    {
        $notasRecuperacion = NotaRecuperacion::select('nota_final', 'periodo_id')
        ->where('materia_id', $materiaId)
        ->where('estudiante_id', $this->user['id'])
        ->orderBy('periodo_id', 'asc')
        ->get();
        $notasRecuperacion->each(function ($nota) {
            $nota->nota_final = number_format($nota->nota_final, 2);
        });
        return $notasRecuperacion;
    }
    public function promedioFinal($materiaId, $notasMateria, $notasRecuperacion)
    {
        $promedioFinal = 0;
        $promedioPeriodo = 0;
        foreach($notasMateria as $nota){
            $notaRecuperacion = $notasRecuperacion->firstWhere('periodo_id', $nota->periodo_id);
            if($notaRecuperacion){
                $promedioFinal += max($nota->nota_final, $notaRecuperacion->nota_final);
                if($nota->periodo_id == $this->periodoId){
                    $promedioPeriodo = max($nota->nota_final, $notaRecuperacion->nota_final);
                }
            }else{
                $promedioFinal += $nota->nota_final;
                if($nota->periodo_id == $this->periodoId){
                    $promedioPeriodo = $nota->nota_final;
                }
            }
        }
        $promedioFinal = round($promedioFinal / count($notasMateria), 2);
        return [$promedioFinal, $promedioPeriodo];
    }
    public function setNotas()
    {
        $materias = $this->Materias();
        $materiasNotas = [];

        foreach($materias as $materia){
            if($materia->nombre_materia == 'SCHOOL BEHAVIOR'){
                $this->directorCurso = $materia->profesor_id;
            }
            $notas = $this->NotasMateria($materia->id);
            $recuperacion = $this->NotasRecuperacion($materia->id);
            $promedioFinal = $this->promedioFinal($materia->id, $notas, $recuperacion);

            $materiasNotas[] = [
                'materia' => $materia->nombre_materia,
                'intensidad_horaria' => $materia->intensidad_horaria,
                'notas' => $notas,
                'recuperacion' => $recuperacion,
                'promedio' => $promedioFinal[0],
                'promedioPeriodo' => $promedioFinal[1],
                'competencias' => $this->NotasCompetencias($materia->id),
            ];
        }
        $this->materiasNotas = $materiasNotas;
        $this->getDirectorCurso();
    }

}
