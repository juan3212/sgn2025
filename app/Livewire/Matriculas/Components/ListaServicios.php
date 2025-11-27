<?php

namespace App\Livewire\Matriculas\Components;

use App\Models\ServicioAdicional;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use App\Models\UsuarioHasServicio;

class ListaServicios extends Component
{
    public $usuarioId;
    public $serviciosSeleccionados = [];
    public $serviciosActuales = [];

    public function mount()
    {
        $this->cargarServicios();
    }
    public function cargarServicios()
    {
        $this->serviciosActuales = DB::table('usuario_has_servicio')
            ->where('usuario_id', $this->usuarioId)
            ->pluck('servicio_id')
            ->toArray();
        $this->serviciosSeleccionados = $this->serviciosActuales;
    }
    // Variable para controlar qué tarjeta está expandida
    public $servicioExpandidoId = null; 

    // Función que se ejecuta al hacer clic en el encabezado
    public function toggleServicio($id)
    {
        // Si el click es en el mismo que ya está abierto, lo cerramos (null)
        // Si es en otro, ponemos ese ID como activo
        if ($this->servicioExpandidoId === $id) {
            $this->servicioExpandidoId = null;
        } else {
            $this->servicioExpandidoId = $id;
        }
    }

    #[On('inscribir_servicios')]
    public function inscribir()
    {
      
        $serviciosSeleccionados = $this->serviciosSeleccionados;
        $serviciosActuales = $this->serviciosActuales;
        foreach ($serviciosSeleccionados as $servicioId) {
            if (!in_array($servicioId, $serviciosActuales)) {
                UsuarioHasServicio::create([
                    'usuario_id' => $this->usuarioId,
                    'servicio_id' => $servicioId,
                ]);
            }           
        }

        foreach($serviciosActuales as $servicioId){
            if (!in_array($servicioId, $serviciosSeleccionados)) {
                UsuarioHasServicio::where([
                    'usuario_id' => $this->usuarioId,
                    'servicio_id' => $servicioId,
                ])->delete();
            }
        }

        $this->dispatch('showAlert', ['type' => 'success', 'message' => 'Servicios actualizados correctamente']);
    }

    public function render()
    {
        return view('livewire.matriculas.components.lista-servicios', [
            'servicios' => ServicioAdicional::all()
        ]);
    }
}
