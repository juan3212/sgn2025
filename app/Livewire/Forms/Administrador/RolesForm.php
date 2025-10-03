<?php

namespace App\Livewire\Forms\Administrador;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Services\RolesyPermisosService;
use Illuminate\Http\Request;

class RolesForm extends Component
{

    public $role;
    public $name;
    public $permissions;
    public $permissionSelected;

    public function mount($role = null)
    {
        $this->permissions = Permission::all();
        if ($role) {
            $this->role = Role::find($role);
            $this->name = $this->role->name;
            $this->permissionSelected = $this->role->permissions->pluck('name')->toArray();
        }
    }


    public function save()
    {
        $this->validate([
            'name' => 'required|string',
            'permissions' => 'required|array|min:1',
        ]);
        
        $rolesyPermisosService = new RolesyPermisosService();
        try {
            $role = [
                'name' => $this->name,
                'permissions' => $this->permissionSelected
            ];
            if ($this->role) {
                $role['role'] = $this->role->name;
                $rolesyPermisosService->updateRole($role);
            } else {
                $rolesyPermisosService->createRoles($role);
                $this->reset('name', 'permissionSelected', 'role');
            }
            session()->flash('message', 'Role saved successfully');
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.forms.administrador.roles-form');
    }
}
