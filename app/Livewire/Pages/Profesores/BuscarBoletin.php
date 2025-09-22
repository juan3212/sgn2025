<?php

namespace App\Livewire\Pages\Profesores;

use Livewire\Component;

class BuscarBoletin extends Component
{
    public $estudiante_id;
    public function buscarBoletin()
    {
        return redirect()->route('boletin', $this->estudiante_id);
    }
    public function render()
    {
        return view('livewire.pages.profesores.buscar-boletin');
    }
}
