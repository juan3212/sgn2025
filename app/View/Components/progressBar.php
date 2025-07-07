<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class progressBar extends Component
{
    public float $grade;
    public float $maxGrade;
    public float $percentage;
    public string $colorClass;
    public string $description;
    public string $size;
    public bool $showDetails;
    public bool $animated;
    
    /**
     * Create a new component instance.
     */
    public function __construct(
        float $grade = 0,
        float $maxGrade = 10,
        string $size = 'medium',
        bool $showDetails = true,
        bool $animated = true
    ) {
        $this->grade = $grade;
        $this->maxGrade = $maxGrade;
        $this->percentage = $maxGrade > 0 ? round(($grade / $maxGrade) * 100, 1) : 0;
        $this->colorClass = $this->getColorClass($this->percentage);
        $this->description = $this->getDescription($this->percentage);
        $this->size = $size;
        $this->showDetails = $showDetails;
        $this->animated = $animated;
    }

    /**
     * Get the color class based on percentage
     */
    private function getColorClass(float $percentage): string
    {
        return match (true) {
            $percentage >= 90 => 'grade-excellent',
            $percentage >= 80 => 'grade-good',
            $percentage >= 70 => 'grade-average',
            $percentage >= 60 => 'grade-poor',
            default => 'grade-fail'
        };
    }

    /**
     * Get description based on percentage
     */
    private function getDescription(float $percentage): string
    {
        return match (true) {
            $percentage >= 90 => 'Excelente',
            $percentage >= 80 => 'Muy bueno',
            $percentage >= 70 => 'Bueno',
            $percentage >= 60 => 'Aceptable',
            $percentage > 0 => 'Necesita mejorar',
            default => 'Sin calificar'
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.progress-bar');
    }
}
