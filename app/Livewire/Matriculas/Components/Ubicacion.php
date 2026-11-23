<?php

namespace App\Livewire\Matriculas\Components;

use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Modelable;
use Livewire\Component;

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
        if (!empty($this->value['departamento'])) {
            $this->searchMunicipios();
        }
    }

    public function getDepartamentos()
    {
        try {
            $data = Http::withoutVerifying()
                ->timeout(5)
                ->get('https://api-colombia.com/api/v1/Department');
            $this->departamentosLista = $data->successful() ? $data->collect() : collect();
        } catch (\Throwable $e) {
            $this->departamentosLista = collect();
        }
    }

    public function searchMunicipios()
    {
        $this->municipiosLista = null;
        if (!empty($this->value['departamento'])) {
            $departamentoId = $this->value['departamento'];
            if (!is_numeric($departamentoId) && $this->departamentosLista) {
                $encontrado = collect($this->departamentosLista)->first(
                    fn ($d) => isset($d['name']) && strcasecmp((string) $d['name'], (string) $departamentoId) === 0
                );
                if ($encontrado && isset($encontrado['id'])) {
                    $departamentoId = $encontrado['id'];
                    $this->value['departamento'] = $departamentoId;
                }
            }

            try {
                $data = Http::withoutVerifying()
                    ->timeout(5)
                    ->get("https://api-colombia.com/api/v1/Department/{$departamentoId}/cities");
                $this->municipiosLista = $data->successful() ? $data->collect() : collect();
            } catch (\Throwable $e) {
                $this->municipiosLista = collect();
            }
        }
    }

    public function updatedValue($key, $value)
    {
        if ($value === 'departamento') {
            $this->value['municipio'] = '';
            $this->searchMunicipios();
        }
    }

    public function updatedMunicipio()
    {
        $this->dispatch('ubicacion-changed', [
            'departamento' => $this->departamento,
            'municipio' => $this->municipio,
            'tipoUbicacion' => $this->tipoUbicacion,
            'tipoUsuario' => $this->tipoUsuario,
        ]);
    }

    public function render()
    {
        if (!empty($this->value['departamento']) && empty($this->municipiosLista)) {
            $this->searchMunicipios();
        }

        return view('livewire.matriculas.components.ubicacion');
    }
}
