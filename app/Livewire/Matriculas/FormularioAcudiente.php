<?php

namespace App\Livewire\Matriculas;

use Livewire\Component;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use App\Models\Usuario;

class FormularioAcudiente extends Component
{
    public $estudianteId;
    public $acudienteId;
    public $acudientes;

    public function mount()
    {
        $this->getAcudientes();
    }

    public function getAcudientes()
    {
        $this->acudientes = Usuario::select("usuarios.id", "nombre", "apellido", "nuip", "telefono", "parentesco")
        ->join("usuario_contacto", "usuarios.id", "=", "usuario_contacto.usuario_id")
        ->join("usuario_has_child", "usuarios.id", "=", "usuario_has_child.parent_id")
        ->where("usuario_has_child.child_id", $this->estudianteId)
        ->distinct()
        ->get();
    }

    public function agregarAcudiente()
    {
        $this->dispatch("agregar-acudiente");
    }

    #[On("acudiente-completado")]
    public function acudienteCompletado($data)
    {
        if($data['estado']){
            
        }
    }

    #[On('acudiente-cambiado')]
    public function acudienteCambiado($acudienteId)
    {
        $this->acudienteId = $acudienteId;
    }
    public function render()
    {
        return view('livewire.matriculas.formulario-acudiente');
    }
}
