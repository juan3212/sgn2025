<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\Grado;
use App\Models\UsuarioGrado;
use App\Models\UsuarioRetirado;

class UsuarioForm extends Component
{
    public $usuarioId;
    protected $currentPassword;
    public $estado;
    public $selectedEstado;
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

    public function mount($usuarioId = null)
    {
        $this->usuarioId = $usuarioId;
        $usuario = $this->getUsuario();
        if ($usuario) {
            $this->name = $usuario->nombre;
            $this->last_name = $usuario->apellido;
            $this->email = $usuario->correo ?? null;
            $this->nuip = $usuario->nuip;
            $this->password = $usuario->password_hash;
            $this->currentPassword = $usuario->password_hash;
            $this->roleSelected = $usuario->roles->first()->name;
        }
    }

    private function getUsuario()
    {
        if ($this->usuarioId) {
            $usuario = Usuario::with("retirados")->find($this->usuarioId);
            $role = $usuario->roles->first();
            $this->estado = $usuario->retirados;
            return $usuario;
        }
        return null;
    }

    public function submit()
    {
        if ($this->usuarioId) {
            return $this->updateUser();
        }
        try {
            $this->validate([
                "name" => "string|required",
                "last_name" => "required",
                "nuip" => "required|numeric",
                "password" => "required|min:8",
                "roleSelected" => "required",
            ]);

            $usuario = Usuario::create([
                "nombre" => $this->name,
                "apellido" => $this->last_name,
                "correo" => $this->email,
                "nuip" => $this->nuip,
                "password_hash" => Hash::make($this->password),
            ])->syncRoles($this->roleSelected);

            if ($this->roleSelected == "estudiante") {
                $this->validate([
                    "selectedGrade" => "required",
                    "selectedClass" => "required",
                ]);
                UsuarioGrado::create([
                    "usuario_id" => $usuario->id,
                    "grado_id" => $this->selectedGrade,
                    "grupo_id" => $this->selectedClass,
                ]);
            }

            session()->flash("message", "User created successfully.");
            $this->reset(
                "name",
                "last_name",
                "email",
                "nuip",
                "password",
                "roleSelected",
                "selectedGrade",
                "selectedClass",
            );
        } catch (\Exception $e) {
            session()->flash("error", "An error occurred: " . $e->getMessage());
        }
    }

    public function updateUser()
    {
        try {
            $this->validate([
                "name" => "string|required",
                "last_name" => "required",
                "nuip" => "required|numeric",
                "password" => "required|min:8",
                "roleSelected" => "required",
                "selectedEstado" => "required|in:true,false",
            ]);

            $usuario = Usuario::find($this->usuarioId);
            $usuario->update([
                "nombre" => $this->name,
                "apellido" => $this->last_name,
                "correo" => $this->email,
                "nuip" => $this->nuip,
                "password_hash" =>
                    $this->password != $this->currentPassword
                        ? Hash::make($this->password)
                        : $this->currentPassword,
            ]);
            $usuario->syncRoles([$this->roleSelected]);

            if ($this->selectedEstado == "false") {
                UsuarioRetirado::create([
                    "usuario_id" => $this->usuarioId,
                    "motivo_retiro" => " ",
                ]);
            }

            if ($this->selectedEstado == "true") {
                UsuarioRetirado::where(
                    "usuario_id",
                    $this->usuarioId,
                )->delete();
            }

            session()->flash("success", "Usuario actualizado exitosamente");
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash("error", "An error occurred: " . $e->getMessage());
        }
    }

    public function updatedRoleSelected()
    {
        if ($this->roleSelected == "estudiante") {
            $this->grades = Grado::all();
        } else {
            $this->grades = [];
        }
    }

    public function resetForm()
    {
        $this->reset();
    }

    public function render()
    {
        return view("livewire.forms.usuario-form");
    }
}
