<?php

namespace App\Livewire\Matriculas\Components;

use Livewire\Component;
use App\Models\UsuarioInfoMedica;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB; 

class InfoMedica extends Component
{
    public $estudianteId;
    public $infoMedicaId;
    public $rh;
    public $eps;
    public $enfermedades;
    public $alergias;
    public $medicamentos;

    public function boot()
    {
        $this->getInfoMedica();
    }
    public function getInfoMedica()
    {
        $medica = UsuarioInfoMedica::where("usuario_id", $this->estudianteId)
            ->first();
        if($medica) {
            $this->infoMedicaId = $medica->id;
            $this->rh = $medica->rh;
            $this->eps = $medica->eps;
            $this->enfermedades = $medica->enfermedades;
            $this->alergias = $medica->alergias;
            $this->medicamentos = $medica->medicamentos;
        }
    }

    #[On("saveData")]
    public function saveInfoMedica()
    {
        DB::table("usuario_info_medica")
            ->updateOrInsert(
            [
                "id" => $this->infoMedicaId,
                "usuario_id" => $this->estudianteId,
            ],
            [
                "rh" => $this->rh,
                "eps" => $this->eps,
                "enfermedades" => $this->enfermedades,
                "alergias" => $this->alergias,
                "medicamentos" => $this->medicamentos,
            ]
        );
    }
    public function render()
    {
        return view('livewire.matriculas.components.info-medica');
    }
}
