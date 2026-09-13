<?php

namespace App\Livewire\Pages\Profesores;

use Livewire\Component;
use App\Models\Competencia;
use App\Models\Materia;
use App\Models\Usuario;
use App\Models\Actividad;
use Livewire\Attributes\On;
use App\Exports\PlanillaNotasPeriodoExport;
use Maatwebsite\Excel\Facades\Excel;

class NotasPeriodo extends Component
{
    public $periodo_id;
    public $materia_id;
    public $grado_id;
    public $grupo_id;
    public $nombre_materia;
    public $grado_nombre;
    public $grupo_nombre;
    public $competencias;
    public $estudiantes;
    public $actividades;
    public $actividadesForm = false;

    public function mount($periodoId, $materiaId)
    {
        $this->periodo_id = $periodoId;
        $this->materia_id = $materiaId;
        $this->getData();
    }

    public function getData()
    {
        $this->getMateriaInfo();
        $this->getCompetencias();
        if (!$this->competencias->isEmpty()) {
            $this->getEstudiantes();
        }
    }

    public function getCompetencias()
    {
        $competencias = Competencia::select("competencias.*")
            ->distinct()
            ->with([
                "actividades" => function ($query) {
                    $query->where("materia_id", $this->materia_id)
                          ->where("periodo_id", $this->periodo_id);
                },
            ])
            ->whereHas("materias", function ($query) {
                $query->where("materias.id", $this->materia_id);
            })
            ->where("competencias.periodo_id", $this->periodo_id)
            ->get();

        if ($competencias->isNotEmpty()) {
            $competencias = $competencias->unique('id');

            foreach ($competencias as $comp) {
                if ($comp->actividades) {
                    $filtradas = $comp->actividades
                        ->filter(function ($act) {
                            return (int) $act->materia_id === (int) $this->materia_id
                                && (int) $act->periodo_id === (int) $this->periodo_id;
                        })
                        ->unique('id')
                        ->values();
                    $comp->setRelation('actividades', $filtradas);
                }
            }

            $this->competencias = $competencias;
            $this->actividades = $this->competencias
                ->pluck("actividades")
                ->collapse()
                ->unique('id')
                ->values();
        } else {
            $this->competencias = collect();
            $this->actividades = collect();
        }
    }

    private function getMateriaInfo()
    {
        $materiaInfo = Materia::select(
            "materia_id",
            "grado_id",
            "grupo_id",
            "grados.grado",
            "grupos.grupo",
            "bm.nombre_materia",
        )
            ->join("base_materia as bm", "materias.materia_id", "=", "bm.id")
            ->join("grados", "materias.grado_id", "=", "grados.id")
            ->join("grupos", "materias.grupo_id", "=", "grupos.id")
            ->where("materias.id", $this->materia_id)
            ->first();

        $this->grado_nombre = $materiaInfo->grado;
        $this->grupo_nombre = $materiaInfo->grupo;
        $this->grado_id = $materiaInfo->grado_id;
        $this->grupo_id = $materiaInfo->grupo_id;
        $this->nombre_materia = $materiaInfo->nombre_materia;
    }

    public function getEstudiantes()
    {
        $this->estudiantes = Usuario::with([
            "grupos",
            "grados",
            "roles",
            "notas" => function ($query) {
                $query->whereIn(
                    "actividad_id",
                    $this->actividades->pluck("id"),
                );
            },
            "notasMateria" => function ($query) {
                $query->where("materia_id", $this->materia_id);
                $query->where("periodo_id", $this->periodo_id);
            },
            "comentariosPeriodo" => function ($query) {
                $query->where("periodo_id", $this->periodo_id);
            },
        ])
            ->whereHas("grados", function ($query) {
                $query->where("grado_id", $this->grado_id);
            })
            ->whereHas("grupos", function ($query) {
                $query->where("grupo_id", $this->grupo_id);
            })
            ->whereHas("roles", function ($query) {
                $query->where("role_id", 2);
            })
            ->orderBy("apellido", "asc")
            ->orderBy("nombre", "asc")
            ->get();
    }

    public function getActividades($competencia_id)
    {
        return Actividad::where("periodo_id", $this->periodo_id)
            ->where("materia_id", $this->materia_id)
            ->where("competencia_id", $competencia_id)
            ->get();
    }

    public function exportarPlanilla()
    {
        $this->getData();

        if (empty($this->actividades) || $this->actividades->isEmpty()) {
            return;
        }

        $materiaSlug = preg_replace('/[^A-Za-z0-9_-]/', '_', $this->nombre_materia);
        $gradoSlug = preg_replace('/[^A-Za-z0-9_-]/', '_', $this->grado_nombre);
        $grupoSlug = preg_replace('/[^A-Za-z0-9_-]/', '_', $this->grupo_nombre);
        $timestamp = date('Ymd_His');

        $fileName = "Planilla_{$materiaSlug}_{$gradoSlug}_{$grupoSlug}_P{$this->periodo_id}_{$timestamp}.xlsx";

        return Excel::download(
            new PlanillaNotasPeriodoExport(
                $this->materia_id,
                $this->nombre_materia,
                $this->grado_nombre,
                $this->grupo_nombre,
                $this->periodo_id,
                $this->competencias,
                $this->estudiantes
            ),
            $fileName
        );
    }

    #[On("actividad-competencia-guardada")]
    public function actividadGuardada()
    {
        $this->getData();
    }

    public function render()
    {
        $this->getData();

        return view("livewire.pages.profesores.notas-periodo");
    }
}
