<?php

namespace App\Livewire\Matriculas\Components;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Validate;

class UploadDocument extends Component
{
    use WithFileUploads;
    public $estudianteId;
    public $estudianteNuip;
    #[Validate('required|file|max:10240|mimes:pdf,jpg,jpeg,png')]
    public $documento;
    public $documentoUrl;   
    public $tipoDocumento;
    public $usuario;
    public $usuarioId;
    public $usuarioNuip;
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
            $extension = $this->documento->extension();
            
            $path = "documentos/estudiantes/".$this->usuarioNuip."/documentos";
            $name = $this->nombreDocumento.".".$extension;
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
