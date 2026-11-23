<?php

namespace App\Livewire\Matriculas\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadDocument extends Component
{
    use WithFileUploads;

    #[Locked]
    public $estudianteId;

    #[Locked]
    public $estudianteNuip;

    public $documento;

    #[Locked]
    public $documentoUrl;

    #[Locked]
    public $tipoDocumento;

    #[Locked]
    public $usuario;

    #[Locked]
    public $usuarioId;

    #[Locked]
    public $usuarioNuip;

    #[Locked]
    public $nombreDocumento;

    public function mount($tipoDocumento, $usuario, $nombreDocumento, $usuarioId = null, $usuarioNuip = null)
    {
        $this->tipoDocumento = $tipoDocumento;
        $this->usuario = $usuario;
        $this->nombreDocumento = $nombreDocumento;
        $this->usuarioId = $usuarioId;
        $this->usuarioNuip = $usuarioNuip;

        if (!$usuarioNuip && $usuario === 'estudiante') {
            $this->usuarioNuip = $this->getEstudianteNuip();
        }

        if (!$this->usuarioId && $this->usuario === 'estudiante') {
            $this->usuarioId = $this->estudianteId;
        }

        $this->getDocumento();
    }

    public function updatedDocumento()
    {
        $this->validate([
            'documento' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png',
        ], [
            'documento.file' => 'El archivo seleccionado no es válido.',
            'documento.max' => 'El archivo no debe superar los 10 MB.',
            'documento.mimes' => 'El archivo debe ser PDF, JPG, JPEG o PNG.',
        ]);
    }

    public function getDocumento()
    {
        if (!$this->usuarioId) {
            $this->documentoUrl = null;

            return;
        }

        $this->documentoUrl = DB::table('usuario_archivos')
            ->where('usuario_id', $this->usuarioId)
            ->where('nombre_archivo', $this->nombreDocumento)
            ->value('ruta_archivo');
    }

    public function getEstudianteNuip()
    {
        $this->estudianteNuip = DB::table('usuarios')
            ->where('id', $this->estudianteId)
            ->value('nuip');

        return $this->estudianteNuip;
    }

    #[On('saveData')]
    public function validarYGuardarEstudiante()
    {
        if ($this->usuario !== 'estudiante') {
            return;
        }

        $resultado = $this->procesarDocumento();

        $this->dispatch('estudiante-paso-validado', [
            'componente' => $this->nombreDocumento,
            'valido' => $resultado['valido'],
            'errores' => $resultado['errores'],
        ]);
    }

    #[On('upload-document')]
    public function saveDocumento($data = [])
    {
        $targetUsuario = is_array($data) ? ($data['usuario'] ?? null) : null;
        if ($targetUsuario !== null && $targetUsuario !== $this->usuario) {
            return;
        }

        $this->procesarDocumento();
    }

    /**
     * Valida que el documento exista (ya subido o recién seleccionado) y lo guarda si hay un archivo nuevo.
     *
     * @return array{valido: bool, errores: array<int, string>}
     */
    protected function procesarDocumento(): array
    {
        $this->getDocumento();

        if (!$this->documento) {
            if (!empty($this->documentoUrl)) {
                $this->resetValidation('documento');

                return ['valido' => true, 'errores' => []];
            }

            $mensaje = "Debe subir el documento requerido: {$this->tipoDocumento}.";
            $this->addError('documento', $mensaje);

            return ['valido' => false, 'errores' => [$mensaje]];
        }

        try {
            $this->validate([
                'documento' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png',
            ], [
                'documento.required' => "Debe subir el documento requerido: {$this->tipoDocumento}.",
                'documento.file' => 'El archivo seleccionado no es válido.',
                'documento.max' => 'El archivo no debe superar los 10 MB.',
                'documento.mimes' => 'El archivo debe ser PDF, JPG, JPEG o PNG.',
            ]);
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());

            return ['valido' => false, 'errores' => $e->validator->errors()->all()];
        }

        // Validar autorización del usuario actual
        $currentUser = Auth::user();
        if (!$currentUser) {
            $mensaje = 'Acción no autenticada.';
            $this->addError('documento', $mensaje);
            $this->dispatch('error', ['message' => $mensaje]);

            return ['valido' => false, 'errores' => [$mensaje]];
        }

        $isPrivileged = $currentUser->hasRole(['administrador', 'profesor', 'Super-Admin']);
        $isOwner = ($currentUser->id == $this->usuarioId || $currentUser->id == $this->estudianteId);
        $isParent = DB::table('usuario_has_child')
            ->where('parent_id', $currentUser->id)
            ->where('child_id', $this->estudianteId)
            ->exists();

        if (!$isPrivileged && !$isOwner && !$isParent) {
            $mensaje = 'No tiene permiso para subir este documento.';
            $this->addError('documento', $mensaje);
            $this->dispatch('error', ['message' => $mensaje]);

            return ['valido' => false, 'errores' => [$mensaje]];
        }

        // Obtener y sanitizar NUIP directamente desde la BD
        $nuipFromDb = DB::table('usuarios')->where('id', $this->usuarioId)->value('nuip');
        $safeNuip = preg_replace('/[^0-9]/', '', (string) ($nuipFromDb ?: $this->usuarioNuip));
        if (empty($safeNuip)) {
            $mensaje = 'Debe diligenciar un número de documento válido antes de subir el archivo.';
            $this->addError('documento', $mensaje);
            $this->dispatch('error', ['message' => $mensaje]);

            return ['valido' => false, 'errores' => [$mensaje]];
        }

        // Validar extensión permitida de forma estricta
        $extension = strtolower((string) $this->documento->extension());
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
        if (!in_array($extension, $allowedExtensions, true)) {
            $mensaje = 'Tipo de archivo no permitido. Solo se permiten PDF, JPG, JPEG o PNG.';
            $this->addError('documento', $mensaje);
            $this->dispatch('error', ['message' => $mensaje]);

            return ['valido' => false, 'errores' => [$mensaje]];
        }

        // Sanitizar nombre de documento contra traversal o nombres peligrosos
        $safeNombre = Str::slug($this->nombreDocumento, '_');
        $safeNombre = preg_replace('/[^a-zA-Z0-9_-]/', '', $safeNombre);
        if (empty($safeNombre)) {
            $safeNombre = 'documento_' . time();
        }

        $path = 'documentos/estudiantes/' . $safeNuip . '/documentos';
        $name = $safeNombre . '.' . $extension;

        try {
            $this->documento->storeAs($path, $name);
            DB::table('usuario_archivos')
                ->updateOrInsert([
                    'usuario_id' => $this->usuarioId,
                    'nombre_archivo' => $this->nombreDocumento,
                ], [
                    'ruta_archivo' => $path . '/' . $name,
                    'tipo_archivo' => $extension,
                ]);

            $this->documentoUrl = $path . '/' . $name;
            $this->documento = null;
            $this->resetValidation('documento');

            return ['valido' => true, 'errores' => []];
        } catch (\Exception $e) {
            $mensaje = 'Error al guardar el documento: ' . $e->getMessage();
            $this->addError('documento', $mensaje);
            $this->dispatch('error', ['message' => $e->getMessage()]);

            return ['valido' => false, 'errores' => [$mensaje]];
        }
    }

    public function render()
    {
        return view('livewire.matriculas.components.upload-document');
    }
}
