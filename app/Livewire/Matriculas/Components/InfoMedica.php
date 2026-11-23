<?php

namespace App\Livewire\Matriculas\Components;

use App\Models\UsuarioInfoMedica;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;

class InfoMedica extends Component
{
    public $estudianteId;
    public $infoMedicaId;
    public $rh;
    public $eps;
    public $enfermedades;
    public $alergias;
    public $medicamentos;

    public function mount()
    {
        $this->getInfoMedica();
    }

    public function getInfoMedica()
    {
        $medica = UsuarioInfoMedica::where('usuario_id', $this->estudianteId)
            ->first();
        if ($medica) {
            $this->infoMedicaId = $medica->id;
            $this->rh = $medica->rh;
            $this->eps = $medica->eps;
            $this->enfermedades = $medica->enfermedades;
            $this->alergias = $medica->alergias;
            $this->medicamentos = $medica->medicamentos;
        }
    }

    #[On('saveData')]
    public function saveInfoMedica()
    {
        try {
            $this->validate([
                'rh' => 'required|string|max:20',
                'eps' => 'required|string|max:255',
                'enfermedades' => 'nullable|string|max:1000',
                'alergias' => 'nullable|string|max:1000',
                'medicamentos' => 'nullable|string|max:1000',
            ], [
                'rh.required' => 'El tipo sanguíneo (RH) del estudiante es obligatorio.',
                'eps.required' => 'La EPS del estudiante es obligatoria.',
            ]);
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
            $this->dispatch('estudiante-paso-validado', [
                'componente' => 'info_medica',
                'valido' => false,
                'errores' => $e->validator->errors()->all(),
            ]);

            return;
        }

        $this->resetValidation();

        try {
            $registro = UsuarioInfoMedica::updateOrCreate(
                [
                    'usuario_id' => $this->estudianteId,
                ],
                [
                    'rh' => trim((string) $this->rh),
                    'eps' => trim((string) $this->eps),
                    'enfermedades' => $this->enfermedades,
                    'alergias' => $this->alergias,
                    'medicamentos' => $this->medicamentos,
                ]
            );

            $this->infoMedicaId = $registro->id;

            $this->dispatch('estudiante-paso-validado', [
                'componente' => 'info_medica',
                'valido' => true,
                'errores' => [],
            ]);
        } catch (\Exception $e) {
            $this->dispatch('estudiante-paso-validado', [
                'componente' => 'info_medica',
                'valido' => false,
                'errores' => ['Ocurrió un error al guardar la información médica del estudiante.'],
            ]);
        }
    }

    public function render()
    {
        return view('livewire.matriculas.components.info-medica');
    }
}
