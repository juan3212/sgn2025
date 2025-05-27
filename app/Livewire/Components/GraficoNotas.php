<?php

namespace App\Livewire\Components;

use Livewire\Component;

class GraficoNotas extends Component
{
    public $nota;
    public $color;
    public $porcentajeNota;
    public function mount($nota)
    {
        $this->nota = $nota;
    }
    public function changeColor($nota)
    {
        switch ($nota) {
            case $nota < 6:
                $this->color = 'red';
                break;
            case $nota >= 6 && $nota <= 7.5:
                $this->color = 'yellow';
                break;
            case $nota > 7.5:
                $this->color = 'green';
                break;
        }
    }
    public function render()
    {
        $this->porcentajeNota = round($this->nota * 100);
        $this->changeColor($this->nota);
        return view('livewire.components.grafico-notas');
    }
}
