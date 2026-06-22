<div>


@foreach($materiasNotas as $materia)
<table class="border">
    <thead class="border">
        <th class="w-2/12 border border-black">Subject</th>
        <th class="w-1/12 border border-black">IH</th>
        <th class="w-1/12 border border-black">P I</th>
        <th class="w-1/12 border border-black">R</th>
        <th class="w-1/12 border border-black">P II</th>
        <th class="w-1/12 border border-black">R</th>
        <th class="w-1/12 border border-black">P III</th>
        <th class="w-1/12 border border-black">R</th>
        <th class="w-1/12 border border-black">P IV</th>
        <th class="w-1/12 border border-black">R</th>
        <th class="w-1/12 border border-black">Final</th>
    </thead>
    <tbody class="border border-black">
        <tr class="bg-gray-300 text-center">
            <td class="border border-black">{{$materia['materia']}}</td>
            <td class="border border-black">{{$materia['intensidad_horaria']}}</td>
            <td class="border border-black nota notaf" id="nota1">{{$materia['notas']->firstWhere('periodo_id', 1)['nota_final'] ?? 'N/A'}}</td>
            <td class="border border-black recuperacion" id="recuperacion1">{{$materia['recuperacion']->firstWhere('periodo_id', 1)['nota_final'] ?? 'N/A'}}</td>
            <td class="border border-black nota notaf" id="nota2">{{$materia['notas']->firstWhere('periodo_id', 2)['nota_final'] ?? 'N/A'}}</td>
            <td class="border border-black recuperacion" id="recuperacion2">{{$materia['recuperacion']->firstWhere('periodo_id', 2)['nota_final'] ?? 'N/A'}}</td>
            <td class="border border-black nota notaf" id="nota3">{{$materia['notas']->firstWhere('periodo_id', 3)['nota_final'] ?? 'N/A'}}</td>
            <td class="border border-black recuperacion" id="recuperacion3">{{$materia['recuperacion']->firstWhere('periodo_id', 3)['nota_final'] ?? 'N/A'}}</td>
            <td class="border border-black nota notaf" id="nota4">{{$materia['notas']->firstWhere('periodo_id', 4)['nota_final'] ?? 'N/A'}}</td>
            <td class="border border-black recuperacion" id="recuperacion4">{{$materia['recuperacion']->firstWhere('periodo_id', 4)['nota_final'] ?? 'N/A'}}</td>
            <td class="border border-black nota  @if($materia['promedio'] < 6) bg-red-500 @endif" id="finalAverage">{{$materia['promedio']}}</td>
            <td class="border border-black nota" hidden id="termAverage">{{$materia['promedioPeriodo']}}</td>
        </tr>
        @foreach($materia['competencias'] as $key=>$competencia)
        <tr class="skillrow">
            <td class="font-bold">C{{ $key+1 }}</td>
            <td colspan="9" class="p-4 text-comentario">{{$competencia['descripcion']}}</td>
            <td class="text-center nota nota_competencia">{{$competencia['nota_final']}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endforeach

<script>
    let finalAverage = 0;
    let termAverage = 0;
    document.addEventListener('DOMContentLoaded', function() {
        const finalAverageElements = document.querySelectorAll('#finalAverage');
        finalAverageElements.forEach(element => {
            finalAverage += parseFloat(element.textContent);
        });
        finalAverage = (finalAverage / finalAverageElements.length).toFixed(2);

        const termAverageElements = document.querySelectorAll('#termAverage');
        termAverageElements.forEach(element => {
            termAverage += parseFloat(element.textContent);
        });
        termAverage = (termAverage / termAverageElements.length).toFixed(2);

        const notas = document.querySelectorAll('.notaf');
        notas.forEach(nota => {
            if (parseFloat(nota.textContent) < 6) {
                nota.classList.add('bg-red-500');
            }
        });
    });
</script>
</div>
