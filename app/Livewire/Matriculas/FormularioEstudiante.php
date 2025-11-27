<?php

namespace App\Livewire\Matriculas;

use Livewire\Component;

class FormularioEstudiante extends Component
{
    public $estudianteId;

    public function render()
    {
        return view('livewire.matriculas.formulario-estudiante');
    }
}
