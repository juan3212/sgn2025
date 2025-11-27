<?php

namespace App\Livewire\Matriculas;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Models\UsuarioFacturacion;
use Livewire\Attributes\Session;
use App\Models\MatriculaCompletadaInfo;

class FormularioMatricula extends Component
{
    public $estudianteId;
    public $nombre;
    public $apellido;
    public $email;
    public $secciones = ["estudiante", "acudiente", "servicios", "contratos"];
    public $seccionActiva = "estudiante";
    public $basicData;
    public $checks = [
        'contrato' => false,
        'carta' => false,
        'pagare' => false,
        'autorizacion-factura-electronica' => false,
        'acta' => false,
    ];
    public $acudienteFacturacion;
    public $padres;

    public function mount($estudianteId)
    {
        $this->estudianteId = $estudianteId;

        $this->padres = DB::table("usuario_has_child")
            ->select("*")
            ->where("child_id", $this->estudianteId)
            ->where(function($query) {
                $query->where("parentesco", "Padre")
                      ->orWhere("parentesco", "Madre");
            })
            ->get();

        $registroExistente = DB::table("usuario_facturacion")
            ->select("acudiente_id")
            ->where("estudiante_id", $this->estudianteId)
            ->first();

        $this->acudienteFacturacion = $registroExistente ? $registroExistente->acudiente_id : "";
    }


    #[On("basicUserData")]
    public function updateUserData(array $data)
    {
        // Update user data logic here
        $this->basicData = $data;
    }
    public function getContratosListosProperty()
    {
        return !in_array(false, $this->checks);
    }

    public function siguiente()
    {
       $siguienteSeccion = array_search($this->seccionActiva, $this->secciones) + 1;
       
       switch ($this->seccionActiva) {
           case 'estudiante':
               $this->guardarEstudiante();
               $this->dispatch('scrollToTop');
               break;
           case 'acudiente':
               $this->guardarAcudiente();
               break;
           case 'servicios':
               $this->inscribirServicios();
               break;
           case 'contratos':
               $this->guardarContratos();
               $this->dispatch('scrollToTop');
               break;
       }
       
       if($siguienteSeccion < count($this->secciones)){
           $this->seccionActiva = $this->secciones[$siguienteSeccion];
       }
    }

    public function anterior()
    {
       $anteriorSeccion = array_search($this->seccionActiva, $this->secciones) - 1;
       if($anteriorSeccion >= 0){
           $this->seccionActiva = $this->secciones[$anteriorSeccion];
       }
    }

    public function guardarEstudiante()
    {
        $this->dispatch('saveData');

    }

    #[On("estudiante-guardado")]
    public function estudianteGuardado($data)
    {
        if($data['estado']){
            $this->showEstudiante = false;
            $this->showAcudiente = true;
            $this->dispatch("scrollToTop");
        }
        else{
            $this->dispatch("showAlert", ['message' => "Error al guardar el estudiante", 'type' => "error"]);
        }
    }

    public function guardarAcudiente()
    {
        $acudientes = DB::table("usuario_has_child")->select("*")->where("child_id", $this->estudianteId)
        ->get();

        
        if($acudientes->contains('parentesco', 'Padre') && $acudientes->contains('parentesco', 'Madre')){
            $this->showAcudiente = false;
            $this->showContratos = true;
            $this->dispatch("scrollToTop");
        }
        else{
            $this->dispatch("showAlert", ['message' => "El estudiantes debe tener por lo menos un padre y una madre", 'type' => "error"]);
        }
        
    }

    public function updatedAcudienteFacturacion()
    {
       
        UsuarioFacturacion::updateOrInsert(
            [
                "estudiante_id" => $this->estudianteId,
            ], 
            [
                "acudiente_id" => $this->acudienteFacturacion
            ] 
        );
    }

    public function inscribirServicios()
    {
        $this->dispatch('inscribir_servicios');
        $this->dispatch('scrollToTop');
    }

    public function guardarContratos()
    {
        MatriculaCompletadaInfo::updateOrCreate([
            "estudiante_id" => $this->estudianteId,
        ], [
            "ip" => request()->ip()
        ]);

        $this->dispatch("showAlert", ['message' => "Matricula completada", 'type' => "success"]);
        
    }

    public function render()
    {
        return view("livewire.matriculas.formulario-matricula");
    }

}
