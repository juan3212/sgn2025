<?php

namespace App\Livewire\Components;
use App\Models\Usuario;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class SelectUsers extends Component
{

    public $usuarios;
    public $role;
    public $usuarioSelected;

    #[Modelable]
    public $usuario_id;
    
    public $title;
    
    public function mount($role, $title, $usuarioSelected = null, $usuario_id = null)
    {
        $this->role = $role;
        $this->title = $title;
        $this->usuarioSelected = $usuarioSelected;
        $this->usuario_id = $usuario_id;
    }
    public function updatedUsuarioSelected()
    {
        $this->usuarios = Usuario::whereHas('roles', function ($query) {
            $query->where('name', '=', $this->role);
        })
        ->where(function($query) {
                $query->where('nombre', 'like', '%'.$this->usuarioSelected.'%')
                      ->orWhere('apellido', 'like', '%'.$this->usuarioSelected.'%');
        })
        ->get();
        
    }
    public function render()
    {
        return view('livewire.components.select-users');
    }
}
