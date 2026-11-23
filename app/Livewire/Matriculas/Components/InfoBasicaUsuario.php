<?php

namespace App\Livewire\Matriculas\Components;

use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;

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
        $usuario = Usuario::select(
            'nombre',
            'apellido',
            'nuip',
            'tipo_documento',
            'departamento_expedicion',
            'municipio_expedicion',
            'fecha_nacimiento',
            'departamento_nacimiento',
            'municipio_nacimiento',
            'sexo'
        )
            ->leftJoin('usuario_informacion', 'usuario_informacion.usuario_id', '=', 'usuarios.id')
            ->leftJoin('usuario_info_documento', 'usuario_info_documento.usuario_id', '=', 'usuarios.id')
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

    #[On('saveData')]
    public function saveUsuario()
    {
        try {
            $this->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'documentoTipo' => 'required|in:CC,TI,RC',
                'numeroDocumento' => [
                    'required',
                    'numeric',
                    Rule::unique('usuario_info_documento', 'numero_documento')->ignore($this->estudianteId, 'usuario_id'),
                ],
                'valueUbicacionExpedicion.departamento' => 'required',
                'valueUbicacionExpedicion.municipio' => 'required',
                'fechaNacimiento' => 'required|date',
                'valueUbicacionNacimiento.departamento' => 'required',
                'valueUbicacionNacimiento.municipio' => 'required',
                'sexo' => 'required|in:Masculino,Femenino',
            ], [
                'nombre.required' => 'El nombre del estudiante es obligatorio.',
                'apellido.required' => 'El apellido del estudiante es obligatorio.',
                'documentoTipo.required' => 'El tipo de documento es obligatorio.',
                'documentoTipo.in' => 'Seleccione un tipo de documento válido.',
                'numeroDocumento.required' => 'El número de documento es obligatorio.',
                'numeroDocumento.numeric' => 'El número de documento debe ser numérico.',
                'numeroDocumento.unique' => 'Este número de documento ya está registrado para otro usuario.',
                'valueUbicacionExpedicion.departamento.required' => 'El departamento de expedición es obligatorio.',
                'valueUbicacionExpedicion.municipio.required' => 'El municipio de expedición es obligatorio.',
                'fechaNacimiento.required' => 'La fecha de nacimiento es obligatoria.',
                'fechaNacimiento.date' => 'La fecha de nacimiento no es válida.',
                'valueUbicacionNacimiento.departamento.required' => 'El departamento de nacimiento es obligatorio.',
                'valueUbicacionNacimiento.municipio.required' => 'El municipio de nacimiento es obligatorio.',
                'sexo.required' => 'El sexo del estudiante es obligatorio.',
                'sexo.in' => 'Seleccione una opción válida para sexo.',
            ]);
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
            $this->dispatch('estudiante-paso-validado', [
                'componente' => 'info_basica',
                'valido' => false,
                'errores' => $e->validator->errors()->all(),
            ]);

            return;
        }

        $this->resetValidation();

        DB::beginTransaction();
        try {
            DB::table('usuarios')
                ->updateOrInsert(
                    [
                        'id' => $this->estudianteId,
                    ],
                    [
                        'nombre' => trim((string) $this->nombre),
                        'apellido' => trim((string) $this->apellido),
                        'nuip' => trim((string) $this->numeroDocumento),
                    ]
                );

            DB::table('usuario_info_documento')
                ->updateOrInsert(
                    [
                        'usuario_id' => $this->estudianteId,
                    ],
                    [
                        'tipo_documento' => $this->documentoTipo,
                        'numero_documento' => trim((string) $this->numeroDocumento),
                        'departamento_expedicion' => $this->valueUbicacionExpedicion['departamento'],
                        'municipio_expedicion' => $this->valueUbicacionExpedicion['municipio'],
                    ]
                );

            DB::table('usuario_informacion')
                ->updateOrInsert(
                    [
                        'usuario_id' => $this->estudianteId,
                    ],
                    [
                        'fecha_nacimiento' => $this->fechaNacimiento,
                        'departamento_nacimiento' => $this->valueUbicacionNacimiento['departamento'],
                        'municipio_nacimiento' => $this->valueUbicacionNacimiento['municipio'],
                        'sexo' => $this->sexo,
                    ]
                );

            DB::commit();
            $this->dispatch('estudiante-paso-validado', [
                'componente' => 'info_basica',
                'valido' => true,
                'errores' => [],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('estudiante-paso-validado', [
                'componente' => 'info_basica',
                'valido' => false,
                'errores' => ['Ocurrió un error al guardar la información básica del estudiante.'],
            ]);
        }
    }

    public function render()
    {
        return view('livewire.matriculas.components.info-basica-usuario');
    }
}
