@props(['nota', 'color', 'porcentajeNota'])

<div class="progress-container grid grid-cols-1">
    <div>
        <span class="note-value" style="display: block; text-align: center; margin-top: 5px;">{{ $notaFormateada }}</span>
    </div>
    <div class="progress-bar-container"
        style="background-color: #f0f0f0; border-radius: 5px; border-width: 1px; border-color: #000; height: 10px; width: 100%; overflow: hidden;">
        <div class="progress-bar"
            style="background-color: {{ $color }}; height: 10px; width: {{ $porcentajeNota }}%; border-radius: 5px;">
        </div>
    </div>
</div>