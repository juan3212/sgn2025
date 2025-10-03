<?php

namespace App\Livewire\Forms\Administrador;

use App\Services\RolesyPermisosService;
use Livewire\Attributes\On;

use Livewire\Component;

class PermisosForm extends Component
{
    public $name;
    public $guard_name;
    public $show = false;

    #[On('open-modal')]
    public function open()
    {
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
    }
    
    public function save()
    {
        $RolesyPermisosService = new RolesyPermisosService();
        try {
            $RolesyPermisosService->createPermissions([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
            ]);
            $this->dispatch('alert', type: 'success', message: 'Permiso creado exitosamente');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Error al crear el permiso');
        }
        $this->reset();
    }
    public function render()
    {
        return view('livewire.forms.administrador.permisos-form');
    }
}
