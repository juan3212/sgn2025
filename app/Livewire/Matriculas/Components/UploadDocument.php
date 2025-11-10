<?php

namespace App\Livewire\Matriculas\Components;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class UploadDocument extends Component
{
    use WithFileUploads;
    public $estudianteId;
    public $estudianteNuip;
    public $documento;
    public $documentoUrl;   
    public $tipoDocumento;
    public $usuario;
    public $usuarioId;
    public $nombreDocumento;

    public function mount($tipoDocumento, $usuario, $nombreDocumento, $usuarioId=null)
    {
        $this->tipoDocumento = $tipoDocumento;
        $this->usuario = $usuario;
        $this->nombreDocumento = $nombreDocumento;
        $this->usuarioId = $usuarioId;

        $this->getEstudianteNuip();
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

    }

    #[On("saveData")]
    public function saveDocumento()
    {
        if($this->documento){
            $extension = $this->documento->extension();
            $path = "documentos/estudiantes/".$this->estudianteNuip."/".$this->usuario;
            $name = $this->nombreDocumento.".".$extension;
            try{
                $this->documento->storeAs($path, $name);
                DB::table('usuario_archivos')
                ->updateOrInsert([
                    'usuario_id' => $this->estudianteId,
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
