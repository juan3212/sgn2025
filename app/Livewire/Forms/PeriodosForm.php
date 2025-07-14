<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use App\Models\Periodo;

class PeriodosForm extends Component
{

    public $periodo;
    public $fecha_inicio;
    public $fecha_fin;
    public $id;
    public function mount($id = null)
    {
        if($id){
            $this->id = $id;
            $this->getCurrentData();
        }
    }
    public function getCurrentData()
    {
        $periodo = Periodo::findOrFail($this->id);
        $this->periodo = $periodo->periodo;
        $this->fecha_inicio = $periodo->fecha_inicio;
        $this->fecha_fin = $periodo->fecha_fin;
    }
    public function submit()
    {
        try {
            $this->validate([
                'periodo' => 'required|string|max:255',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            ], [
                'periodo.required' => 'El nombre del período es obligatorio',
                'fecha_inicio.required' => 'La fecha de inicio es obligatoria',
                'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida',
                'fecha_fin.required' => 'La fecha de finalización es obligatoria',
                'fecha_fin.date' => 'La fecha de finalización debe ser una fecha válida',
                'fecha_fin.after_or_equal' => 'La fecha de finalización debe ser igual o posterior a la fecha de inicio',
            ]);

            $periodo = Periodo::updateOrCreate(
                ['id' => $this->id ?? null],
                [
                    'periodo' => $this->periodo,
                    'fecha_inicio' => $this->fecha_inicio,
                    'fecha_fin' => $this->fecha_fin,
                ]
            );

            $this->dispatch('periodosUpdated');
            
            // Emitir evento de éxito
            $this->dispatch('periodoSaved', 
                message: $this->id ? 'Período actualizado exitosamente' : 'Período creado exitosamente'
            );
            
            // Resetear el formulario si es una creación
            if (!$this->id) {
                $this->reset(['periodo', 'fecha_inicio', 'fecha_fin']);
            }
            
        } catch (\Exception $e) {
            // Manejo de errores específicos
            if ($e instanceof \Illuminate\Database\QueryException) {
                if (str_contains($e->getMessage(), 'Duplicate entry')) {
                    $this->addError('periodo', 'Ya existe un período con este nombre');
                    $this->dispatch('periodoError', message: 'Error: Ya existe un período con este nombre');
                    return;
                }
            }
            
            // Error general
            $this->dispatch('periodoError', 
                message: 'Ocurrió un error al ' . ($this->id ? 'actualizar' : 'crear') . ' el período: ' . $e->getMessage()
            );
            
            // Log del error
            \Log::error('Error en PeriodosForm: ' . $e->getMessage(), [
                'exception' => $e,
                'data' => $this->only(['periodo', 'fecha_inicio', 'fecha_fin'])
            ]);
        }
    }
    public function render()
    {
        return view('livewire.forms.periodos-form');
    }
}
