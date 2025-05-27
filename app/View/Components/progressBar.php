<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class progressBar extends Component
{
    public float $nota; // La nota cruda que se pasa
    public string $color;
    public float $porcentajeNota;
    public string $notaFormateada; // Para mostrar "2.1 / 10"

    /**
     * Create a new component instance.
     *
     * @param float $nota La nota numérica (ej: 2.1)
     * @param float $notaMaxima La nota máxima posible (ej: 10)
     */
    public function __construct(float $nota, float $notaMaxima)
    {
        $this->nota = $nota;
        $this->porcentajeNota = round($nota * 10);
        $this->notaFormateada = number_format($nota, 1) . ' / ' . number_format($notaMaxima, 1);
        $this->changeColor($nota);
    }

    public function changeColor($nota)
    {
        switch ($nota) {
            case $nota < 6:
                $this->color ='red';
                break;
            case $nota >= 6 && $nota <= 7.5:
                $this->color = 'yellow';
                break;
            case $nota > 7.5:
                $this->color = 'green';
                break;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.progress-bar');
    }
}
