<?php

namespace App\Livewire\Matriculas;

use Livewire\Component;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use App\Models\Usuario;
use App\Livewire\Forms\AcudienteForm;
use Illuminate\Support\Facades\DB;

class FormularioAcudiente extends Component
{
    public $estudianteId;
    
    // Instancia del Form Object
    public AcudienteForm $form;

    // Lista local para mostrar en la tabla sin re-consultar todo el tiempo
    public $acudientes = [];

    public function mount($estudianteId)
    {
        $this->estudianteId = $estudianteId;
        $this->cargarAcudientes();
    }

    public function cargarAcudientes()
    {
        // Tu lógica original adaptada
        $this->acudientes = Usuario::select("usuarios.id", "nombre", "apellido", "nuip", "parentesco")
            ->join("usuario_has_child", "usuarios.id", "=", "usuario_has_child.parent_id")
            ->where("usuario_has_child.child_id", $this->estudianteId)
            ->get();
    }

    // Método llamado desde la vista cuando dan click en "Buscar Acudiente"
    public function buscarAcudiente()
    {
        $encontrado = $this->form->buscarPorDocumento();
        
        if ($encontrado) {
            $this->dispatch('showAlert', ['message' => 'Usuario encontrado. Datos cargados.', 'type' => 'success']);
        } else {
            $this->dispatch('showAlert', ['message' => 'Usuario no encontrado. Por favor registre los datos.', 'type' => 'info']);
        }
    }

    // Método llamado al dar click en "Agregar Acudiente"
    public function guardar()
    {
        $acudientes = DB::table('usuario_has_child')->where('child_id', $this->estudianteId)->get();
        if($acudientes->contains('parentesco', 'Padre') && $this->form->parentesco == 'Padre' && $this->form->acudienteId != $acudientes->where('parentesco', 'Padre')->first()->parent_id){
            $this->dispatch('showAlert', ['type' => 'info', 'message' => 'Ya existe un acudiente padre.']);
            return;
        }

        if($acudientes->contains('parentesco', 'Madre') && $this->form->parentesco == 'Madre' && $this->form->acudienteId != $acudientes->where('parentesco', 'Madre')->first()->parent_id){
            $this->dispatch('showAlert', ['type' => 'info', 'message' => 'Ya existe un acudiente madre.']);
            return;
        }

        $this->form->store($this->estudianteId);
        $this->dispatch('upload-document', ['usuario' => 'acudiente']);
        $this->cargarAcudientes(); // Refrescar tabla
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Acudiente agregado correctamente']);
    }

    public function guardarContacto()
    {
        $this->form->guardarContacto();
    }

    public function quitarContacto($id)
    {
        $this->form->quitarContacto($id);
    }

    public function quitarAcudiente($id)
    {
        // Lógica para desvincular
        DB::table('usuario_has_child')
            ->where('parent_id', $id)
            ->where('child_id', $this->estudianteId)
            ->delete();
            
        $this->cargarAcudientes();
    }
}
