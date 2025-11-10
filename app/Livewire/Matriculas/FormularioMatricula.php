<?php

namespace App\Livewire\Matriculas;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Session;

class FormularioMatricula extends Component
{
    public $estudianteId;
    public $nombre;
    public $apellido;
    public $email;

    public $basicData;

    public function mount($estudianteId)
    {
        $this->estudianteId = $estudianteId;
    }

    #[On("basicUserData")]
    public function updateUserData(array $data)
    {
        // Update user data logic here
        $this->basicData = $data;
    }


    public function render()
    {
        return view("livewire.matriculas.formulario-matricula");
    }

}
