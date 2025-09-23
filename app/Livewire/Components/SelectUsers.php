<?php

namespace App\Livewire\Components;
use App\Models\Usuario;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class SelectUsers extends Component
{
    #[props(['role', 'title', 'usuarioSelected', 'usuario_id', 'class'])]

    public $usuarios;
    public $role;
    public $usuarioSelected;

    public $class;

    #[Modelable]
    public $usuario_id;
    
    public $title;
    
    public function mount($role, $title, $usuarioSelected = null, $usuario_id = null, $class = null)
    {
        $this->role = $role;
        $this->title = $title;
        $this->usuarioSelected = $usuarioSelected;
        $this->usuario_id = $usuario_id;
    }
    public function updatedUsuarioSelected()
    {
        $usuarioSelected = explode(" ", $this->usuarioSelected);
        $this->usuarios = Usuario::whereHas('roles', function ($query) {
            $query->where('name', '=', $this->role);
        })
        ->where(function ($query) use ($usuarioSelected) {
            foreach ($usuarioSelected as $part) {
                $query->whereRaw('CONCAT(nombre, " ", apellido) LIKE ? ', ['%' . $part . '%']);
            }
        })
        ->get();
        
    }
    public function render()
    {
        return view('livewire.components.select-users');
    }
}
