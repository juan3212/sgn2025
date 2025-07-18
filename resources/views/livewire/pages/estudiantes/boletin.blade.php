<div>
   
@foreach($materiasNotas as $materia)
<table>
    <thead>
        <th>Materia</th>
        <th>intensidad horaria</th>
        <th>periodo 1</th>
        <th>R</th>
        <th>periodo 2</th>
        <th>R</th>
        <th>periodo 3</th>
        <th>R</th>
        <th>periodo 4</th>
        <th>R</th>
        <th>promedio</th>
    </thead>
    <tbody>
        <tr>
            <td>{{$materia['materia']}}</td>
            <td>{{$materia['intensidad_horaria']}}</td>
            <td>{{$materia['notas']->firstWhere('periodo_id', 1)['nota_final'] ?? 'N/A'}}</td>
            <td>{{$materia['recuperacion']->firstWhere('periodo_id', 1)['nota_final'] ?? 'N/A'}}</td>
            <td>{{$materia['notas']->firstWhere('periodo_id', 2)['nota_final'] ?? 'N/A'}}</td>
            <td>{{$materia['recuperacion']->firstWhere('periodo_id', 2)['nota_final'] ?? 'N/A'}}</td>
            <td>{{$materia['notas']->firstWhere('periodo_id', 3)['nota_final'] ?? 'N/A'}}</td>
            <td>{{$materia['recuperacion']->firstWhere('periodo_id', 3)['nota_final'] ?? 'N/A'}}</td>
            <td>{{$materia['notas']->firstWhere('periodo_id', 4)['nota_final'] ?? 'N/A'}}</td>
            <td>{{$materia['recuperacion']->firstWhere('periodo_id', 4)['nota_final'] ?? 'N/A'}}</td>
            <td>{{$materia['promedio']}}</td>
        </tr>    
        @foreach($materia['competencias'] as $key=>$competencia)
        <tr>
            <td colspan="2">C{{ $key+1 }}</td>
            <td colspan="8">{{$competencia['descripcion']}}</td>
            <td>{{$competencia['nota_final']}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endforeach


</div>
