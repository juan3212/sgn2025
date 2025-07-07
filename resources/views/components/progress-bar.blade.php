@php
    $sizeClasses = [
        'small' => 'w-16 h-10',
        'medium' => 'w-20 h-12',
        'large' => 'w-24 h-16',
        'xlarge' => 'w-32 h-20'
    ];
    
    $gaugeSize = $sizeClasses[$size] ?? $sizeClasses['medium'];
    $uniqueId = 'gauge-' . uniqid();
    
    // Calcular el stroke-dashoffset basado en el porcentaje
    $circumference = 94.2; // Aproximadamente la circunferencia del arco
    $dashOffset = $circumference - ($percentage / 100) * $circumference;
@endphp

<div class="gauge-container {{ $attributes->get('class') }}" data-grade="{{ $grade }}" data-max="{{ $maxGrade }}">
    <div class="gauge-wrapper {{ $gaugeSize }} relative mx-auto mb-2">
        <svg class="gauge-svg w-full h-full" viewBox="0 0 80 50" id="{{ $uniqueId }}">
            {{-- Fondo del gauge --}}
            <path 
                class="gauge-bg" 
                d="M 10 40 A 30 30 0 0 1 70 40"
                fill="none"
                stroke="#e5e7eb"
                stroke-width="6"
                stroke-linecap="round"
            />
            
            {{-- Progreso del gauge --}}
            <path 
                class="gauge-fill {{ $colorClass }}" 
                d="M 10 40 A 30 30 0 0 1 70 40"
                fill="none"
                stroke-width="6"
                stroke-linecap="round"
                stroke-dasharray="{{ $circumference }}"
                stroke-dashoffset="{{ $dashOffset }}"
                data-progress="{{ $percentage }}"
                @if($animated)
                style="transition: stroke-dashoffset 1.5s ease-in-out;"
                @endif
            />
        </svg>
        
        {{-- Texto del porcentaje --}}
        <div class="gauge-text absolute bottom-1 left-1/2 transform -translate-x-1/2 font-bold text-xs text-gray-700">
            {{ $percentage }}%
        </div>
    </div>
    
    @if($showDetails)
    <div class="grade-info text-center text-xs text-gray-600">
        <div class="grade-value font-bold text-sm text-gray-800 mb-1">
            {{ number_format($grade, 2) }} / {{ number_format($maxGrade, 2) }}
        </div>
        <div class="grade-description">
            {{ $description }}
        </div>
    </div>
    @endif
</div>

@once
<style>
    .gauge-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 120px;
    }
    
    /* Colores para diferentes rangos de notas */
    .grade-excellent { 
        stroke: #10b981; /* green-500 */
    }
    
    .grade-good { 
        stroke: #06b6d4; /* cyan-500 */
    }
    
    .grade-average { 
        stroke: #f59e0b; /* amber-500 */
    }
    
    .grade-poor { 
        stroke: #f97316; /* orange-500 */
    }
    
    .grade-fail { 
        stroke: #ef4444; /* red-500 */
    }
    
    /* Animación hover para el gauge */
    .gauge-container:hover .gauge-fill {
        filter: brightness(1.1);
    }
    
    /* Animación CSS para el gauge */
    @if($animated)
    .gauge-fill {
        animation: gauge-fill-animation 1.5s ease-in-out;
    }
    
    @keyframes gauge-fill-animation {
        0% {
            stroke-dashoffset: {{ $circumference }};
        }
        100% {
            stroke-dashoffset: {{ $dashOffset }};
        }
    }
    @endif
    
    /* Responsive adjustments */
    @media (max-width: 640px) {
        .gauge-container {
            min-width: 80px;
        }
        
        .gauge-text {
            font-size: 10px;
        }
        
        .grade-info {
            font-size: 10px;
        }
    }
</style>
@endonce
