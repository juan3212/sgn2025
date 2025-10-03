<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\Grado;
use App\Models\UsuarioGrado;

class UsuarioForm extends Component
{

    public $name;
    public $last_name;
    public $email;
    public $nuip;
    public $password;
    public $roles;
    public $roleSelected;
    public $grades;
    public $selectedGrade;
    public $classes;
    public $selectedClass;

    public function boot()
    {
        $this->roles = Role::all();
    }

    public function submit()
    {
        try {
            $this->validate([
                'name' => 'string|required',
                'last_name' => 'required',
                'nuip' => 'required|numeric',
                'password' => 'required|min:8',
                'roleSelected' => 'required',
            ]);

            $usuario = Usuario::create([
                'nombre' => $this->name,
                'apellido' => $this->last_name,
                'correo' => $this->email,
                'nuip' => $this->nuip,
                'password_hash' => Hash::make($this->password),
            ])->assignRole($this->roleSelected);

            if($this->roleSelected == "estudiante") {
                $this->validate([
                    'selectedGrade' => 'required',
                    'selectedClass' => 'required',
                ]);
                UsuarioGrado::create([
                    'usuario_id' => $usuario->id,
                    'grado_id' => $this->selectedGrade,
                    'grupo_id' => $this->selectedClass,
                ]);
            }

            session()->flash('message', 'User created successfully.');
            $this->reset('name', 'last_name', 'email', 'nuip', 'password', 'roleSelected', 'selectedGrade', 'selectedClass');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }

    }

    public function updatedRole(){
        if($this->roleSelected == "estudiante") {
            $this->grades = Grado::all();
        } else {
            $this->grades = [];
        }
    }


    public function resetForm(){
        $this->reset();
    }

    
    public function render()
    {
        return view('livewire.forms.usuario-form');
    }
}
