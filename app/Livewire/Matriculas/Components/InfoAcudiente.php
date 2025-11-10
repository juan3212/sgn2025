<?php

namespace App\Livewire\Matriculas\Components;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class InfoAcudiente extends Component
{
    public $estudianteId;
    public $acudienteId;
    public $acudienteNombre;
    public $acudienteApellido;
    public $acudienteTipoDocumento;
    public $acudienteNumeroDocumento;
    public $acudienteParentesco;
    public $departamento;
    public $municipio;
    public $key;

    public function searchAcudiente()
    {
        
             $acudiente = Usuario::select('usuarios.id', 'nombre', 'apellido', 'nuip', 'tipo_documento', 'departamento_expedicion', 'municipio_expedicion', 'parentesco')
            ->leftjoin('usuario_informacion', 'usuario_informacion.usuario_id', '=', 'usuarios.id')
            ->leftjoin('usuario_info_documento', 'usuario_info_documento.usuario_id', '=', 'usuarios.id')    
            ->leftjoin('usuario_has_child', 'usuario_has_child.parent_id', '=', 'usuarios.id')
            ->where('usuarios.nuip', $this->acudienteNumeroDocumento)
            ->first();
            $this->resetProperties(["acudienteNumeroDocumento"]);
            if($acudiente){
                $this->acudienteId = $acudiente->id;
                $this->acudienteNombre = $acudiente->nombre;
                $this->acudienteApellido = $acudiente->apellido;
                $this->acudienteTipoDocumento = $acudiente->tipo_documento;
                $this->acudienteNumeroDocumento = $acudiente->nuip;
                $this->departamento = $acudiente->departamento_expedicion;
                $this->municipio = $acudiente->municipio_expedicion;
                $this->acudienteParentesco = $acudiente->parentesco;

                $this->dispatch("acudiente-cambiado", $acudiente->id);
            }
            else{
                $this->dispatch("acudiente-cambiado", 0);
                $this->dispatch("acudiente", ["title" => "No se encontro ningun usuario", "message" => "Debes agregar todos los datos del acudiente"]);
            }
    }

    #[On("ubicacion-changed")]
    public function actualizarUbicacion($data)
    {
        if ($data['tipoUsuario'] === 'acudiente') {
            $this->departamento = $data['departamento'];
            $this->municipio = $data['municipio'];
        }
    }

    #[On("guardar-acudiente")]
    public function saveAcudiente($data)
    {
        $acudienteExistente = DB::table("usuarios")->where("nuip", $this->acudienteNumeroDocumento)->first();
        if($acudienteExistente){
            return $this->dispatch("acudiente", ["title" => "Usuario existente", "message" => "Ya existe una persona con el mismo numero de documento, para agregarlo debes dar click en el boton Buscar Acudiente"]);
        }
        $this->validate([
            "acudienteNombre" => "required",
            "acudienteApellido" => "required",
            "acudienteTipoDocumento" => "required",
            "acudienteNumeroDocumento" => "required|numeric",
            "acudienteParentesco" => "required",
            "departamento" => "required",
            "municipio" => "required",
        ]);

       
        
        DB::beginTransaction();
        try {

            DB::table("usuarios")->updateOrInsert(
                ["id" => $this->acudienteId],
                [
                    "nombre" => $this->acudienteNombre,
                    "apellido" => $this->acudienteApellido,
                    "nuip" => $this->acudienteNumeroDocumento,
                    "password_hash" => Hash::make($this->acudienteNumeroDocumento)
                ]
            );

            $acudiente = Usuario::where("nuip", $this->acudienteNumeroDocumento)->first();
            $acudiente->assignRole("Padre");
            $this->acudienteId = $acudiente->id;

            DB::table("usuario_info_documento")->updateOrInsert(
                ["usuario_id" => $this->acudienteId],
                [
                    "numero_documento" => $this->acudienteNumeroDocumento,
                    "tipo_documento" => $this->acudienteTipoDocumento,
                    "departamento_expedicion" => $this->departamento,
                    "municipio_expedicion" => $this->municipio,
                ]
            );

            DB::table("usuario_has_child")->updateOrInsert(
                ["parent_id" => $this->acudienteId, "child_id" => $this->estudianteId],
                [
                    "parentesco" => $this->acudienteParentesco,
                ]
            );

            $this->dispatch("acudiente-guardado", ["estado" => true, "temporal" => $data['temporal']]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch("acudiente-guardado", ["estado" => false, "temporal" => $data['temporal']]);
            throw $e;
        }
        DB::commit();
    }

    #[On("financiera-guardada")]
    public function finalizarGuardado($data)
    {
        if($data['estado'] && !$data['temporal']){
            $this->dispatch("acudiente-completado", ["estado" => true]);
            $this->resetProperties();
        }
    }

    public function resetProperties($notReset = [])
    {
        $properties = [
            "acudienteId",
            "acudienteNombre",
            "acudienteApellido",
            "acudienteTipoDocumento",
            "acudienteNumeroDocumento",
            "acudienteParentesco",
            "departamento",
            "municipio",
        ];
        $properties = array_diff($properties, $notReset);
        $this->reset($properties);
    }
    public function render()
    {
        return view("livewire.matriculas.components.info-acudiente");
    }
}
