<?php

namespace App\Livewire\Pages\Administrador;

use Livewire\Component;
use App\Models\Grado;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Periodo;
use App\Http\Controllers\administrador\informesController;
use App\Exports\InformeMateriasReprobadasExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelWriter;

class InformeMaterias extends Component
{
    public $grados = [];
    public $gradoSelected = '';
    public $grupos = [];
    public $grupoSelected = '';
    public $materias = [];
    public $materiaSelected = '';
    public $informe = [];
    public $periodos = [];
    public $periodoSelected = '';
    public $informeMessage = 'Haga clic en "Generar Informe" para consultar todos los estudiantes o aplique los filtros deseados.';

    public function mount()
    {
        $this->getInitialData();
    }

    public function getInitialData()
    {
        $this->grados = Grado::all();
        $this->grupos = Grupo::all();
        $this->periodos = Periodo::all();
        $this->getMaterias();
    }

    public function updatedGradoSelected()
    {
        $this->materiaSelected = '';
        $this->getMaterias($this->gradoSelected);
    }

    public function getMaterias($grado = null)
    {
        $query = Materia::select('base_materia.id as id', 'base_materia.nombre_materia as nombre')
            ->join("base_materia", "base_materia.id", "=", "materias.materia_id");

        if ($grado) {
            $query->where("materias.grado_id", $grado);
        }

        $this->materias = $query->distinct()
            ->orderBy('base_materia.nombre_materia')
            ->get();
    }

    public function getInforme()
    {
        $informeController = new informesController();
        $this->informe = $informeController->generarInforme(
            $this->gradoSelected ?: null,
            $this->grupoSelected ?: null,
            $this->materiaSelected ?: null,
            $this->periodoSelected ?: null,
            'materia'
        );

        if (empty($this->informe)) {
            $this->informeMessage = 'No se encontraron estudiantes reprobando materias con los filtros seleccionados.';
        }
    }

    public function exportar($formato = 'xlsx')
    {
        if (empty($this->informe)) {
            $this->getInforme();
        }

        if (empty($this->informe)) {
            $this->informeMessage = 'No hay información disponible para exportar con los filtros seleccionados.';
            return null;
        }

        $timestamp = date('Ymd_His');
        $formato = strtolower($formato);
        $extension = $formato === 'csv' ? 'csv' : 'xlsx';
        $writerType = $formato === 'csv' ? ExcelWriter::CSV : ExcelWriter::XLSX;

        return Excel::download(
            new InformeMateriasReprobadasExport($this->informe),
            "informe_reprobados_{$timestamp}.{$extension}",
            $writerType
        );
    }

    public function render()
    {
        return view('livewire.pages.administrador.informe-materias');
    }
}
