<?php

namespace App\Livewire\Matriculas;

use App\Livewire\Forms\AcudienteForm;
use App\Models\Usuario;
use App\Services\MatriculaValidationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class FormularioAcudiente extends Component
{
    use WithFileUploads;

    public $estudianteId;

    // Instancia del Form Object
    public AcudienteForm $form;

    // Lista local para mostrar en la tabla junto con estado de completitud
    public $acudientes = [];

    public function mount($estudianteId)
    {
        $this->estudianteId = $estudianteId;
        $this->cargarAcudientes();
    }

    public function cargarAcudientes()
    {
        $validator = app(MatriculaValidationService::class);

        $registros = Usuario::select('usuarios.id', 'nombre', 'apellido', 'nuip', 'parentesco')
            ->join('usuario_has_child', 'usuarios.id', '=', 'usuario_has_child.parent_id')
            ->where('usuario_has_child.child_id', $this->estudianteId)
            ->get();

        $this->acudientes = $registros->map(function ($acudiente) use ($validator) {
            $resultado = $validator->validarAcudienteIndividual($acudiente->id, (string) $acudiente->parentesco);

            return [
                'id' => $acudiente->id,
                'nombre' => $acudiente->nombre,
                'apellido' => $acudiente->apellido,
                'nuip' => $acudiente->nuip,
                'parentesco' => $acudiente->parentesco,
                'completo' => $resultado['valido'],
                'errores' => $resultado['errores'],
            ];
        })->toArray();
    }

    public function buscarAcudiente()
    {
        $encontrado = $this->form->buscarPorDocumento($this->estudianteId);

        if ($encontrado) {
            $this->dispatch('showAlert', ['message' => 'Usuario encontrado. Datos cargados.', 'type' => 'success']);
        } else {
            $this->dispatch('showAlert', ['message' => 'Usuario no encontrado. Por favor registre los datos.', 'type' => 'info']);
        }
    }

    public function editarAcudiente($nuip)
    {
        $this->form->numero_documento = $nuip;
        $this->form->buscarPorDocumento($this->estudianteId);
        $this->resetValidation();
        $this->dispatch('scrollToTop');
    }

    public function guardar()
    {
        $acudientes = DB::table('usuario_has_child')->where('child_id', $this->estudianteId)->get();
        $usuarioActual = Usuario::where('nuip', trim((string) $this->form->numero_documento))->first();
        $acudienteIdActual = $this->form->acudienteId ?: ($usuarioActual?->id);

        foreach (['Padre', 'Madre', 'Padre/Madre_cabeza_de_familia'] as $rolUnico) {
            $existente = $acudientes->where('parentesco', $rolUnico)->first();
            if ($existente && $this->form->parentesco === $rolUnico && $acudienteIdActual != $existente->parent_id) {
                $this->dispatch('showAlert', [
                    'type' => 'info',
                    'message' => "Ya existe un acudiente registrado como {$rolUnico}.",
                ]);

                return;
            }
        }

        try {
            $this->form->store($this->estudianteId);
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Complete todos los campos obligatorios y suba los documentos requeridos del acudiente antes de guardar.',
            ]);

            return;
        }

        $this->resetValidation();
        $this->cargarAcudientes();
        $this->dispatch('acudientes-actualizados');
        $this->dispatch('showAlert', ['type' => 'success', 'message' => 'Acudiente guardado correctamente.']);
    }

    public function guardarContacto()
    {
        $guardado = $this->form->guardarContacto();
        if (!$guardado) {
            $this->dispatch('showAlert', [
                'type' => 'info',
                'message' => 'El contacto diligenciado se guardará automáticamente al hacer clic en "Guardar y Agregar a la Lista".',
            ]);
        } else {
            $this->cargarAcudientes();
        }
    }

    public function quitarContacto($id)
    {
        $this->form->quitarContacto($id);
        $this->cargarAcudientes();
    }

    public function quitarAcudiente($id)
    {
        DB::table('usuario_has_child')
            ->where('parent_id', $id)
            ->where('child_id', $this->estudianteId)
            ->delete();

        $this->cargarAcudientes();
        $this->dispatch('acudientes-actualizados');
    }

    public function render()
    {
        return view('livewire.matriculas.formulario-acudiente');
    }
}
