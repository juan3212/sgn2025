<?php

namespace App\Livewire\Matriculas\Components;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;

class Ubicacion extends Component
{
    public $estudianteId;
    public $tipoUbicacion;
    public $departamentosLista;
    public $municipiosLista;
    #[Modelable]
    public $value = [];
    public $departamento;
    public $municipio;
    public $tipoUsuario;
    
    public function mount($tipoUbicacion = null, $tipoUsuario = null)
    {
        if ($tipoUbicacion) {
            $this->tipoUbicacion = $tipoUbicacion;
        } 
        $this->tipoUsuario = $tipoUsuario;

        $this->getDepartamentos();
        if(isset($this->value['departamento'])){
            $this->searchMunicipios();
        }
    }
    public function getDepartamentos()
    {
        $data = Http::withoutVerifying()->get(
            "https://api-colombia.com/api/v1/Department",
        );
        $this->departamentosLista = $data->collect();
    }

    public function searchMunicipios()
    {
        
        $this->municipiosLista = null;
        if(isset($this->value['departamento']) && $this->value['departamento']) {
            $data = Http::withoutVerifying()->get(
                "https://api-colombia.com/api/v1/Department/{$this->value['departamento']}/cities",
            );
            $this->municipiosLista = $data->collect();
        }
    }
    public function updatedValue($key, $value)
    {
        if ($value == 'departamento') {
            $this->value['municipio'] = ''; // Limpiar municipio
            $this->searchMunicipios();
        }
    }

    public function updatedMunicipio()
    {
        $this->dispatch("ubicacion-changed", [
            'departamento' => $this->departamento,
            'municipio' => $this->municipio,
            'tipoUbicacion' => $this->tipoUbicacion,
            'tipoUsuario' => $this->tipoUsuario
        ]);
    }

 
    public function render()
    {
        return view('livewire.matriculas.components.ubicacion');
    }
}
