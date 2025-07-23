<?php

namespace App\Livewire\Forms;

use App\Models\Actividad;
use App\Models\TipoNota;
use App\Models\MateriaHasCompetencia;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ActividadesForm extends Component
{
    public $actividadId = null;
    public $nombre;
    public $descripcion;
    public $tipo;
    public $tipoSelected;
    public $materiasSelected = []; //materias seleccionadas
    public $materia; //materia seleccionada
    public $materias = []; //materias con competencia
    public $periodo;
    public $competencia;
    public $actividadesForAllCheck = false;

    public function mount($id = null, $materia = null, $periodo = null, $competencia = null)
    {
        $this->actividadId = $id;
        $this->tipo = TipoNota::all();

        if ($id) {
            $this->loadActividad();
        } else {
            $this->materia = $materia;
            $this->periodo = $periodo;
            $this->competencia = $competencia;
        }
    }

    protected function rules()
    {
        $rules = [
            'nombre' => 'required|string',
            'descripcion' => 'required|string',
            'tipoSelected' => 'required',
        ];

        if ($this->actividadesForAllCheck) {
            $rules['materiasSelected'] = 'required|array|min:1';
        }

        return $rules;
    }

    public function updatedActividadesForAllCheck()
    {
        if ($this->actividadesForAllCheck) {
            $this->materias = $this->getMaterias();
            $this->materiasSelected = array_column($this->materias, 'id_materia');
        } else {
            $this->materias = [];
            $this->materiasSelected = [];
        }
    }

    protected function getMaterias()
    {
        return MateriaHasCompetencia::join('materias', 'materias.id', '=', 'materia_has_competencia.materia_id')
            ->join('base_materia', 'base_materia.id', '=', 'materias.materia_id')
            ->join('grados', 'grados.id', '=', 'materias.grado_id')
            ->join('grupos', 'grupos.id', '=', 'materias.grupo_id')
            ->select('materias.id as id_materia', 'base_materia.nombre_materia', 'grados.grado', 'grupos.grupo')
            ->where('competencia_id', $this->competencia)
            ->get()
            ->toArray();
    }

    protected function loadActividad()
    {
        $actividad = Actividad::findOrFail($this->actividadId);
        $this->nombre = $actividad->nombre;
        $this->descripcion = $actividad->descripcion;
        $this->tipoSelected = $actividad->tipo_nota;
        $this->materia = $actividad->materia_id;
        $this->periodo = $actividad->periodo_id;
        $this->competencia = $actividad->competencia_id;
    }

    protected function saveActividad($materia_id)
    {
        Actividad::updateOrCreate(
            ['id' => $this->actividadId],
            [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'tipo_nota' => $this->tipoSelected,
                'materia_id' => $materia_id,
                'periodo_id' => $this->periodo,
                'competencia_id' => $this->competencia,
            ]
        );
    }

    public function submit()
    {
        DB::beginTransaction();
        try {
            $this->validate();

            if ($this->actividadesForAllCheck) {
                foreach ($this->materiasSelected as $materia_id) {
                    $this->saveActividad($materia_id);
                }
            } else {
                $this->saveActividad($this->materia);
            }

            DB::commit();
            return $this->afterSave();

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al guardar actividad', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error al guardar la actividad: ' . $e->getMessage());
        }
    }

    protected function afterSave()
    {
        if ($this->actividadId) {
            session()->flash('message', 'Actividad actualizada exitosamente.');
        } else {
            $this->reset('nombre', 'descripcion', 'tipoSelected');
            session()->flash('message', 'Actividad guardada exitosamente.');
            return $this->previousPage();
        }
    }

    public function previousPage()
    {
        return $this->redirect("/actividades/{$this->materia}/{$this->periodo}/{$this->competencia}", navigate: true);
    }

    public function render()
    {
        return view('livewire.forms.actividades-form');
    }
}

