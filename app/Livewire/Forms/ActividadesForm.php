<?php

namespace App\Livewire\Forms;

use App\Models\Actividad;
use App\Models\TipoNota;
use Livewire\Component;

class ActividadesForm extends Component
{
    public $id = null;
    public $nombre;
    public $descripcion;
    public $tipo;
    public $tipoSelected;
    public $materia;
    public $periodo;
    public $competencia;
    public function mount($id = null, $materia = null, $periodo = null, $competencia = null)
    {
        if($id){
            $this->id = $id;
            $this->getCurrentData();
        }
        else{
            $this->materia = $materia;
            $this->periodo = $periodo;
            $this->competencia = $competencia;
        }
    }
    public function getCurrentData()
    {
        $actividad = Actividad::findOrFail($this->id);
        $this->nombre = $actividad->nombre;
        $this->descripcion = $actividad->descripcion;
        $this->tipoSelected = $actividad->tipo_nota;
        $this->materia = $actividad->materia_id;
        $this->periodo = $actividad->periodo_id;
        $this->competencia = $actividad->competencia_id;
    }
    public function getTipos()
    {
        $this->tipo = TipoNota::all();
    }
    public function previousPage(){
        return $this->redirect('/actividades/'.$this->materia.'/'.$this->periodo.'/'.$this->competencia, navigate: true);
    }
    public function submit()
    {
        try {
            $this->validate([
                'nombre' => 'required|string',
                'descripcion' => 'required|string',
                'tipoSelected' => 'required',
            ]);
    
            Actividad::updateOrCreate(
                ['id' => $this->id],
                [
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                    'tipo_nota' => $this->tipoSelected,
                    'materia_id' => $this->materia,
                    'periodo_id' => $this->periodo,
                    'competencia_id' => $this->competencia,
                ]
            );
            if($this->id){
                session()->flash('message', 'Actividad actualizada exitosamente.');
            }
            else{
                $this->reset('nombre', 'descripcion', 'tipoSelected'); // Limpia también el ID
                session()->flash('message', 'Actividad guardada exitosamente.');
                return $this->previousPage(); // Redirige a la página anterior después de guardar el forma
            }
    
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la actividad: ' . $e->getMessage());
        }
    }
    public function render()
    {
        $this->getTipos();
        return view('livewire.forms.actividades-form');
    }
}
