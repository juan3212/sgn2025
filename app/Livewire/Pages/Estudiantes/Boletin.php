<?php

namespace App\Livewire\Pages\Estudiantes;

use App\Models\Materia;
use App\Models\NotaFinalMateria;
use App\Models\NotaFinalCompetencia;
use App\Models\NotaRecuperacion;
use App\Models\Periodo;
use App\Services\getUserDataService;
use Livewire\Component;

class Boletin extends Component
{
    public $materiasNotas= [];
    public $periodoId;
    public $user;

    public function boot()
    {
        $this->getStudentData();
        $this->Periodos();
        $this->setNotas();
    }
    public function getStudentData()
    {
        $user = new getUserDataService;
        $user = $user->getUserDataFromID(4);
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.pages.estudiantes.boletin');
    }

    public function Materias()
    {
        $materias = Materia::select('materias.id', 'base_materia.nombre_materia', 'materias.intensidad_horaria')
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
        ->orderBy('periodo_id', 'asc')
        ->get();
        return $notas;
    }

    public function Periodos()
    {
        $periodo = Periodo::where('fecha_inicio', '<', now())
        ->where('fecha_fin', '>', now())
        ->first();
        $this->periodoId = $periodo->id;
    }

    public function NotasCompetencias($materiaId)
    {
        $notasCompetencias = NotaFinalCompetencia::select('competencias.descripcion', 'notas_finales_competencias.nota_final')
        ->where('estudiante_id', $this->user['id'])
        ->where('materia_id', $materiaId)
        ->join('competencias', 'competencias.id', '=', 'notas_finales_competencias.competencia_id')
        ->where('competencias.periodo_id', 1??$this->periodoId)
        ->get();

        return $notasCompetencias; 
    }
    public function NotasRecuperacion($materiaId)
    {
        $notasRecuperacion = NotaRecuperacion::select('nota_final', 'periodo_id')
        ->where('materia_id', $materiaId)
        ->where('estudiante_id', $this->user['id'])
        ->orderBy('periodo_id', 'asc')
        ->get();
        return $notasRecuperacion;
    }
    public function setNotas()
    {
        $materias = $this->Materias();
        $materiasNotas = [];

        foreach($materias as $materia){
            $notas = $this->NotasMateria($materia->id);
            $promedio = $notas->avg('nota_final');
            $materiasNotas[] = [
                'materia' => $materia->nombre_materia,
                'intensidad_horaria' => $materia->intensidad_horaria,
                'notas' => $notas,
                'recuperacion' => $this->NotasRecuperacion($materia->id),
                'promedio' => $promedio,
                'competencias' => $this->NotasCompetencias($materia->id),
            ];
        }
        $this->materiasNotas = $materiasNotas;
    }

}
