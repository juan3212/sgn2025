<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MateriasCompetenciasController;

class ReportCardModule extends Component
{
    /**
     * Create a new component instance.
     */
    public $materiasCompetencias;
    public function __construct(MateriasCompetenciasController $getCompetencesFromSubjects)
    {
        $this->materiasCompetencias = $getCompetencesFromSubjects;

    }

    public function getMateriasCompetencias()
    {
        //return $this->materiasCompetencias->getCompetencesFromSubjects()
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.report-card-module');
    }
}
