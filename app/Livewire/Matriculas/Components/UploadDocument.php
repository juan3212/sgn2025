<?php

namespace App\Livewire\Matriculas\Components;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Validate;

class UploadDocument extends Component
{
    use WithFileUploads;

    #[Locked]
    public $estudianteId;

    #[Locked]
    public $estudianteNuip;

    #[Validate('required|file|max:10240|mimes:pdf,jpg,jpeg,png')]
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

    public function mount($tipoDocumento, $usuario, $nombreDocumento, $usuarioId=null, $usuarioNuip=null)
    {
        $this->tipoDocumento = $tipoDocumento;
        $this->usuario = $usuario;
        $this->nombreDocumento = $nombreDocumento;
        $this->usuarioId = $usuarioId;
        $this->usuarioNuip = $usuarioNuip;
        if(!$usuarioNuip && $usuario == "estudiante"){
           $this->usuarioNuip = $this->getEstudianteNuip();
        }

        if(!$this->usuarioId && $this->usuario == "estudiante"){
            $this->usuarioId = $this->estudianteId;
        }
        $this->getDocumento();

    }

    public function getDocumento()
    {
        $this->documentoUrl = DB::table("usuario_archivos")
        ->where("usuario_id", $this->usuarioId)
        ->where("nombre_archivo", $this->nombreDocumento)
        ->value("ruta_archivo");
    }

    public function getEstudianteNuip()
    {
        $this->estudianteNuip = DB::table("usuarios")
        ->where("id", $this->estudianteId)
        ->value("nuip");
        return $this->estudianteNuip;
    }

    #[On("upload-document")]
    public function saveDocumento($data)
    {
        $this->validate();
        if($this->documento){
            if($data['usuario'] == "acudiente" && $this->usuarioId == $this->estudianteId)
            {
                return true;
            }

            // Validar autorización del usuario actual
            $currentUser = Auth::user();
            if (!$currentUser) {
                $this->dispatch("error", ["message" => "Acción no autenticada."]);
                return;
            }

            $isPrivileged = $currentUser->hasRole(['administrador', 'profesor', 'Super-Admin']);
            $isOwner = ($currentUser->id == $this->usuarioId || $currentUser->id == $this->estudianteId);
            $isParent = DB::table('usuario_has_child')
                ->where('parent_id', $currentUser->id)
                ->where('child_id', $this->estudianteId)
                ->exists();

            if (!$isPrivileged && !$isOwner && !$isParent) {
                $this->dispatch("error", ["message" => "No tiene permiso para subir este documento."]);
                return;
            }

            // Obtener y sanitizar NUIP directamente desde la BD
            $nuipFromDb = DB::table("usuarios")->where("id", $this->usuarioId)->value("nuip");
            $safeNuip = preg_replace('/[^0-9]/', '', (string)($nuipFromDb ?: $this->usuarioNuip));
            if (empty($safeNuip)) {
                $this->dispatch("error", ["message" => "NUIP de usuario no válido."]);
                return;
            }

            // Validar extensión permitida de forma estricta
            $extension = strtolower((string)$this->documento->extension());
            $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
            if (!in_array($extension, $allowedExtensions, true)) {
                $this->dispatch("error", ["message" => "Tipo de archivo no permitido."]);
                return;
            }

            // Sanitizar nombre de documento contra traversal o nombres peligrosos
            $safeNombre = Str::slug($this->nombreDocumento, '_');
            $safeNombre = preg_replace('/[^a-zA-Z0-9_-]/', '', $safeNombre);
            if (empty($safeNombre)) {
                $safeNombre = 'documento_' . time();
            }

            $path = "documentos/estudiantes/" . $safeNuip . "/documentos";
            $name = $safeNombre . "." . $extension;

            try{
                $this->documento->storeAs($path, $name);
                DB::table('usuario_archivos')
                ->updateOrInsert([
                    'usuario_id' => $this->usuarioId,
                    'nombre_archivo' => $this->nombreDocumento,
                ],[
                    'ruta_archivo' => $path."/".$name,
                    'tipo_archivo' => $extension,
                ]);

            }catch(\Exception $e){
                $this->dispatch("error", ["message" => $e->getMessage()]);
            }
        }

    }
    
    public function render()
    {
        return view('livewire.matriculas.components.upload-document');
    }
}
