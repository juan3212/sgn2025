<?php

namespace App\Livewire\Matriculas\Components;

use App\Models\UsuarioContacto;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;

class InfoContacto extends Component
{
    public $estudianteId;
    public $usuarioId;
    public $contactoId;
    public $telefono;
    public $email;
    public $direccion;
    public $tipoUsuario;
    public $guardadoFinal = false;
    public $contacto = [];

    public function mount($tipoUsuario = 'estudiante', $usuarioId = null)
    {
        $this->usuarioId = $usuarioId;
        $tiposUsuario = ['estudiante', 'acudiente'];
        if (!in_array($tipoUsuario, $tiposUsuario, true)) {
            $tipoUsuario = 'estudiante';
        }
        $this->tipoUsuario = $tipoUsuario;

        if ($this->tipoUsuario === 'estudiante') {
            $this->usuarioId = $this->estudianteId;
        }
        $this->getContacto($this->usuarioId);
    }

    public function getContacto($usuarioId)
    {
        if (!$usuarioId) {
            return;
        }

        $contacto = UsuarioContacto::where('usuario_id', $usuarioId)->get();

        if ($contacto && count($contacto) > 0) {
            if ($this->tipoUsuario === 'estudiante') {
                $this->contactoId = $contacto[0]->id;
                $this->telefono = $contacto[0]->telefono;
                $this->email = $contacto[0]->email;
                $this->direccion = $contacto[0]->direccion;
            } else {
                $this->contacto = $contacto;
            }
        }
    }

    #[On('saveData')]
    public function saveContacto()
    {
        if ($this->tipoUsuario === 'estudiante') {
            try {
                $this->validate([
                    'direccion' => 'required|string|max:255',
                    'email' => 'nullable|email|max:255',
                    'telefono' => 'nullable|string|max:50',
                ], [
                    'direccion.required' => 'La dirección de residencia del estudiante es obligatoria.',
                    'email.email' => 'El correo del estudiante debe tener un formato válido.',
                ]);
            } catch (ValidationException $e) {
                $this->setErrorBag($e->validator->errors());
                $this->dispatch('estudiante-paso-validado', [
                    'componente' => 'info_contacto',
                    'valido' => false,
                    'errores' => $e->validator->errors()->all(),
                ]);

                return;
            }

            $this->resetValidation();

            DB::beginTransaction();
            try {
                if ($this->contactoId) {
                    UsuarioContacto::where('id', $this->contactoId)
                        ->where('usuario_id', $this->usuarioId)
                        ->update([
                            'telefono' => $this->telefono,
                            'email' => $this->email,
                            'direccion' => trim((string) $this->direccion),
                        ]);
                } else {
                    $registro = UsuarioContacto::updateOrCreate(
                        ['usuario_id' => $this->usuarioId],
                        [
                            'telefono' => $this->telefono,
                            'email' => $this->email,
                            'direccion' => trim((string) $this->direccion),
                        ]
                    );
                    $this->contactoId = $registro->id;
                }

                DB::commit();
                $this->dispatch('estudiante-paso-validado', [
                    'componente' => 'info_contacto',
                    'valido' => true,
                    'errores' => [],
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                $this->dispatch('estudiante-paso-validado', [
                    'componente' => 'info_contacto',
                    'valido' => false,
                    'errores' => ['Ocurrió un error al guardar la información de contacto del estudiante.'],
                ]);
            }

            return;
        }

        // Flujo heredado si este componente se usa para acudiente
        if (count($this->contacto) < 1) {
            $this->validate([
                'telefono' => 'required',
                'email' => 'required|email',
                'direccion' => 'required',
            ]);
        } elseif ($this->guardadoFinal && count($this->contacto) >= 1) {
            $this->dispatch('guardar-contacto', ['estado' => true]);

            return;
        } else {
            $this->validate([
                'telefono' => 'required',
            ]);
        }

        DB::beginTransaction();
        try {
            DB::table('usuario_contacto')
                ->updateOrInsert(
                    [
                        'id' => $this->contactoId,
                        'usuario_id' => $this->usuarioId,
                    ],
                    [
                        'telefono' => $this->telefono,
                        'email' => $this->email,
                        'direccion' => $this->direccion,
                    ]
                );

            DB::commit();
            $this->dispatch('contacto-guardado', ['estado' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('contacto-guardado', ['estado' => false]);
            throw $e;
        }
    }

    public function deleteContacto($contactoId)
    {
        DB::table('usuario_contacto')
            ->where('id', $contactoId)
            ->delete();

        $this->getContacto($this->usuarioId);
    }

    #[On('agregar-acudiente')]
    public function agregarContacto()
    {
        $this->dispatch('guardar-acudiente', ['temporal' => true]);
        $this->guardadoFinal = true;
    }

    #[On('acudiente-guardado')]
    public function guardarContacto($data)
    {
        if ($data['estado']) {
            if ($this->tipoUsuario === 'estudiante') {
                return;
            }
            try {
                $this->saveContacto();
                $this->getContacto($this->usuarioId);
                $this->reset(['contactoId', 'telefono', 'email', 'direccion']);
            } catch (\Exception $e) {
                $this->dispatch('contacto-guardado', ['estado' => false]);
                throw $e;
            }
        }
    }

    public function render()
    {
        return view('livewire.matriculas.components.info-contacto');
    }
}
