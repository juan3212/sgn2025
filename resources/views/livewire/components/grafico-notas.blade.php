<div class="progress-container grid grid-cols-1">
    <div>
        <span class="note-value" style="display: block; text-align: center; margin-top: 5px;">{{ $nota }} / 10</span>
    </div>
    <div class="progress-bar-container"
        style="background-color: #f0f0f0; border-radius: 5px; height: 10px; width: 100%; overflow: hidden;">
        <div id="progress-bar" class="progress-bar"
            style="background-color: {{ $color }}; height: 10px; width: {{ $porcentajeNota }}%; border-radius: 5px;"></div>
    </div>
</div>
