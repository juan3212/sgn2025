<?php

namespace App\Livewire\Pages\Edit;

use App\Http\Controllers\CompetenciasServiceController;
use Livewire\Component;
use App\Models\Competencia;
use Yajra\DataTables\DataTables;

/**
 * Clase Componente Competencias
 * Maneja la edición y visualización de competencias en el sistema
 */
class Competencias extends Component
{
    // Propiedades para gestionar datos de competencias
    protected $competenceService;      // Servicio para manejar operaciones relacionadas con competencias
    public $competenceId;
    public $competenceName;           // Almacena el nombre de la competencia
    public $competenceDescription;    // Almacena la descripción de la competencia
    public $periodoSelected;         // Período actualmente seleccionado
    public $periodos;    
    public $subjectsAdded=[];            // Lista de períodos disponibles
    public $subjectsSelected=[];   // Array de materias seleccionadas
    public $subjects;                // Lista de materias disponibles
    public $search;                  // Término de búsqueda para filtrar materias
    public $errorMessage;
    public $successMessage;

    /**
     * Método boot - Inicializa el componente con los servicios requeridos
     */
    public function boot(CompetenciasServiceController $competenciasServiceController){
        $this->competenceService = $competenciasServiceController;
    }

    public function mount($competence){
        $this->competenceId = $competence;
        $this->getCurrentData($this->competenceId);
    }

    /**
     * Maneja las actualizaciones de búsqueda obteniendo materias filtradas
     */
    public function updatedSearch()
    {
        $this->subjects = $this->competenceService->getSubjects($this->search);
    }

    /**
     * Recupera y procesa los datos actuales de la competencia
     */
    private function getCurrentData(int $id)
    {
        $competence = $this->fetchCompetenceWithRelations($id);
        $this->mapCompetenceDataToProperties($this->transformCompetenceData($competence));
    }

    /**
     * Obtiene la competencia con sus datos relacionados (materias, grados, grupos)
     */
    private function fetchCompetenceWithRelations(int $id): Competencia
    {
        return Competencia::with(['materias.materias', 'materias.grado', 'materias.grupo'])
            ->findOrFail($id);
    }

    /**
     * Transforma los datos del modelo de competencia en formato de array
     */
    private function transformCompetenceData(Competencia $competence): array
    {
        return [
            'competenceName' => $competence->nombre,
            'competenceDescription' => $competence->descripcion,
            'periodo' => $competence->periodo_id,
            'subjects' => $this->mapSubjects($competence->materias),
        ];
    }

    /**
     * Mapea las relaciones de materias a una estructura de array simplificada
     */
    private function mapSubjects($materias)
    {
        return $materias->map(function ($materia) {
            return [
                'id' => $materia->id,
                'name' => $materia->materias->nombre_materia,
                'grade' => $materia->grado->grado,
                'group' => $materia->grupo->grupo,
            ];
        });
    }

    /**
     * Asigna los datos transformados a las propiedades del componente
     */
    private function mapCompetenceDataToProperties(array $competenceData): void
    {
        $this->subjectsSelected = $competenceData['subjects'];
        $this->competenceName = $competenceData['competenceName'];
        $this->competenceDescription = $competenceData['competenceDescription'];
        $this->periodoSelected = $competenceData['periodo'];
    }

    /**
     * Crea y configura el DataTable para mostrar las materias
     */
    public function createTable($id)
    {
        $this->getCurrentData($id);
       
        
        return DataTables()->of($this->subjectsSelected)
            ->addColumn('checkbox', $this->getCheckboxColumn())
            ->addColumn('actions', $this->getActionsColumn())
            ->rawColumns(['actions', 'checkbox'])
            ->make(true);
    }

    /**
     * Genera el HTML de la columna de checkbox para el DataTable
     */
    private function getCheckboxColumn()
    {
        return function($subject) {
            return sprintf(
                '<input type="checkbox" class="select-checkbox form-checkbox h-5 w-5 text-blue-600" data-id="%s">',
                $subject['id']
            );
        };
    }

    /**
     * Genera el HTML de la columna de acciones para el DataTable
     */
    private function getActionsColumn()
    {
        return function($subject) {
            return sprintf(
                '<button class="btn btn-xs btn-danger delete" data-id="%s">Delete</button>',
                $subject['id']
            );
        };
    }

    public function save()
    {
        $this->validate([
            'competenceName' => 'required',
            'competenceDescription' => 'required',
            'periodoSelected' => 'required',
        ]);



        $request = [
            'id' => $this->competenceId,
            'nombre' => $this->competenceName,
            'descripcion' => $this->competenceDescription,
            'periodo_id' => $this->periodoSelected,
            'materias' => $this->subjectsAdded,
        ];

        $response = $this->competenceService->update($request);
        $responseData = $response->getData(true); // Extraer datos como array

        // Reiniciar campos
        $this->reset('search', 'subjectsAdded');

        // Manejar la respuesta
        if ($responseData['success']) {
            $this->successMessage = 'Competencia guardada exitosamente.';
            $this->errorMessage = ''; // Limpiar mensaje de error
        } else {
            $this->errorMessage = 'Error al guardar la competencia.';
            $this->successMessage = ''; // Limpiar mensaje de éxito
        }

       
    }

    /**
     * Renderiza la vista del componente con los datos requeridos
     */
    public function render()
    {
    
        $this->periodos = $this->competenceService->getPeriodo();
        
        return view('livewire.pages.edit.competencias');
    }
}
