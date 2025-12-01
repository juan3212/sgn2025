<?php

namespace App\Livewire\Matriculas\Components;

use Livewire\Component;
use Livewire\Attributes\Modelable;
use Illuminate\Support\Facades\Http;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class InfoBasicaUsuario extends Component
{
    public $estudianteId;
    public $nombre;
    public $apellido;
    public $documentoTipo;
    public $departamentoExpedicion;
    public $municipioExpedicion;
    public $numeroDocumento;
    public $fechaNacimiento;
    public $departamentoNacimiento;
    public $municipioNacimiento;
    public $sexo;
    public $valueUbicacionNacimiento = [];
    public $valueUbicacionExpedicion = [];

    public function mount()
    {
        $this->searchUser();
    }
    #[On('ubicacion-changed')]
    public function actualizarUbicacion($data)
    {
        if (isset($data['tipoUbicacion']) && isset($data['tipoUsuario']) && $data['tipoUsuario'] === 'estudiante') {
            if ($data['tipoUbicacion'] === 'expedicion') {
                $this->departamentoExpedicion = $data['departamento'];
                $this->municipioExpedicion = $data['municipio'];
                $this->valueUbicacionExpedicion['departamento'] = $data['departamento'];
                $this->valueUbicacionExpedicion['municipio'] = $data['municipio'];
            } elseif ($data['tipoUbicacion'] === 'nacimiento') {
                $this->departamentoNacimiento = $data['departamento'];
                $this->municipioNacimiento = $data['municipio'];
                $this->valueUbicacionNacimiento['departamento'] = $data['departamento'];
                $this->valueUbicacionNacimiento['municipio'] = $data['municipio'];
            }
        }
    }


    public function searchUser()
    {
        $usuario = Usuario::select('nombre', 'apellido', 'nuip', 'tipo_documento', 'departamento_expedicion', 'municipio_expedicion', 'fecha_nacimiento', 'departamento_nacimiento', 'municipio_nacimiento', 'sexo')
            ->leftjoin('usuario_informacion', 'usuario_informacion.usuario_id', '=', 'usuarios.id')
            ->leftjoin('usuario_info_documento', 'usuario_info_documento.usuario_id', '=', 'usuarios.id')    
            ->where('usuarios.id', $this->estudianteId)
            ->first();
        if ($usuario) {
            $this->nombre = $usuario->nombre;
            $this->apellido = $usuario->apellido;
            $this->numeroDocumento = $usuario->nuip;
            $this->documentoTipo = $usuario->tipo_documento;
            $this->departamentoExpedicion = $usuario->departamento_expedicion;
            $this->municipioExpedicion = $usuario->municipio_expedicion;
            $this->fechaNacimiento = $usuario->fecha_nacimiento;
            $this->departamentoNacimiento = $usuario->departamento_nacimiento;
            $this->municipioNacimiento = $usuario->municipio_nacimiento;
            $this->sexo = $usuario->sexo;
            $this->valueUbicacionExpedicion['departamento'] = $usuario->departamento_expedicion;
            $this->valueUbicacionExpedicion['municipio'] = $usuario->municipio_expedicion;
            $this->valueUbicacionNacimiento['departamento'] = $usuario->departamento_nacimiento;
            $this->valueUbicacionNacimiento['municipio'] = $usuario->municipio_nacimiento;

        }
    }
    #[On("saveData")]
    public function saveUsuario()
    {
        
        // Verificar si tenemos datos para guardar
        DB::beginTransaction();
        try {
            DB::table("usuarios")
            ->updateOrInsert(
            [
                "id" => $this->estudianteId,
            ],
            [
                "nombre" => $this->nombre ?? '',
                "apellido" => $this->apellido ?? '',
                "nuip" => $this->numeroDocumento ?? '',
            ]
            );
            
            // Guardar información del documento
            DB::table("usuario_info_documento")
                ->updateOrInsert(
                [
                    "usuario_id" => $this->estudianteId,
                ],
                [
                    "tipo_documento" => $this->documentoTipo ?? '',
                    "numero_documento" => $this->numeroDocumento ?? '',
                    "departamento_expedicion" => $this->valueUbicacionExpedicion['departamento'] ?? '',
                    "municipio_expedicion" => $this->valueUbicacionExpedicion['municipio'] ?? '',
                ]
            );
            
            // Guardar información personal
            DB::table("usuario_informacion")
                ->updateOrInsert(
                [
                    "usuario_id" => $this->estudianteId,
                ],
                [
                    "fecha_nacimiento" => $this->fechaNacimiento ?? null,
                    "departamento_nacimiento" => $this->valueUbicacionNacimiento['departamento'] ?? '',
                    "municipio_nacimiento" => $this->valueUbicacionNacimiento['municipio'] ?? '',
                    "sexo" => $this->sexo ?? '',
                ]
            );
            
            DB::commit();
            $this->dispatch('estudiante-guardado', ["estado" => true]);
            $this->dispatch('upload-document', ["usuario" => "estudiante"]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function render()
    {
        return view("livewire.matriculas.components.info-basica-usuario");
    }
}
