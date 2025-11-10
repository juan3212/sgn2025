<?php

namespace App\Livewire\Matriculas\Components;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Models\UsuarioEstadoLaboral;

class InfoFinanciera extends Component
{
    public $estudianteId;
    public $usuarioId;
    public $estadoLaboral;
    public $empresa;
    public $certificadoLaboral;
    public $camaraComercio;

    public function mount($usuarioId)
    {
        $this->usuarioId = $usuarioId;
        $this->getFinanciera($usuarioId);
    }
    public function getFinanciera($usuarioId)
    {
        $financiera = UsuarioEstadoLaboral::where("usuario_id", $usuarioId)
            ->first();
        if($financiera) {
            $this->estadoLaboral = $financiera->estado_laboral;
            $this->empresa = $financiera->empresa;
        }
    }

    #[On("acudiente-guardado")]
    public function save($data)
    {
        if($data['estado'] && !$data['temporal']){
            DB::beginTransaction();
            try {
                DB::table("usuario_estado_laboral")->updateOrInsert(
                    ["usuario_id" => $this->usuarioId],
                    [
                    "estado_laboral" => $this->estadoLaboral,
                    "empresa" => $this->empresa,
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch("financiera-guardada", ["estado" => false]);
            throw $e;
        }
            DB::commit();
            $this->dispatch("financiera-guardada", ["estado" => true]);
        }
    }

    public function render()
    {
        return view('livewire.matriculas.components.info-financiera');
    }
}
