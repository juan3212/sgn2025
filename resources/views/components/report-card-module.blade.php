@props([
    'materia', 'c1', 'c2', 'c3', 'c4', 'c5', 'c6', 'e',
    'n1', 'n2', 'n3', 'n4', 'n5', 'n6', 'ne',
    'F1', 'R1', 'F2', 'R2', 'F3', 'R3', 'F4', 'R4', 'Ft', 'ih'
]) 

<table id="boletin-{{ Str::slug($materia) }}" class="boletin">
    <thead>
        <tr>
            <th>Subject</th>
            <th>I.H</th>
            <th>P I</th>
            <th>Rec</th>
            <th>P II</th>
            <th>Rec</th>
            <th>P III</th>
            <th>Rec</th>
            <th>P IV</th>
            <th>Rec</th>
            <th>Final</th>
        </tr>
    </thead>
    <tbody>
        <tr class="subjectrow">
            <td class="subjectname">{{ $materia }}</td>
            <td>{{ $ih }} h</td>
            <td>{{ $F1 }}</td>
            <td>{{ $R1 }}</td>
            <td>{{ $F2 }}</td>
            <td>{{ $R2 }}</td>
            <td>{{ $F3 }}</td>
            <td>{{ $R3 }}</td>
            <td>{{ $F4 }}</td>
            <td>{{ $R4 }}</td>
            <td class="finalAvg @if($Ft < 6) finalAvg--below-six @endif">{{ $Ft }}</td>
        </tr>
        <tr class="skillrow">
            <td class="tit">C1</td>
            <td class="skills" colspan="9">{{ $c1 }}</td>
            <td>{{ $n1 > 0 ? $n1 : '' }}</td>
        </tr>
        <tr class="skillrow">
            <td class="tit">C2</td>
            <td class="skills" colspan="9">{{ $c2 }}</td>
            <td>{{ $n2 > 0 ? $n2 : '' }}</td>
        </tr>
        <tr class="skillrow">
            <td class="tit">C3</td>
            <td class="skills" colspan="9">{{ $c3 }}</td>
            <td>{{ $n3 > 0 ? $n3 : '' }}</td>
        </tr>
        <tr class="skillrow">
            <td class="tit">C4</td>
            <td class="skills" colspan="9">{{ $c4 }}</td>
            <td>{{ $n4 > 0 ? $n4 : '' }}</td>
        </tr>
        <tr class="skillrow">
            <td class="tit">C5</td>
            <td class="skills" colspan="9">{{ $c5 }}</td>
            <td>{{ $n5 > 0 ? $n5 : '' }}</td>
        </tr>
        <tr class="skillrow">
            <td class="tit">C6</td>
            <td class="skills" colspan="9">{{ $c6 }}</td>
            <td>{{ $n6 > 0 ? $n6 : '' }}</td>
        </tr>
        <tr class="skillrow">
            <td class="tit">Assessment</td>
            <td class="skills" colspan="9"></td> {{-- Asumo que este campo va vacío según tu JS --}}
            <td>{{ $ne > 0 ? $ne : '' }}</td>
        </tr>
    </tbody>
</table>

<style>
    /* Estilos para la tabla, puedes moverlos a un archivo CSS separado */
    .boletin {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .boletin th,
    .boletin td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
    }

    .boletin thead th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    .subjectrow .subjectname {
        font-weight: bold;
    }

    .finalAvg--below-six {
        background-color: #DB4040;
        font-weight: bold;
        color: white; /* Para asegurar que el texto sea legible */
    }

    .skillrow .tit {
        font-weight: bold;
        width: 10%; /* Ajusta si es necesario */
    }

    .skillrow .skills {
        /* Puedes agregar estilos específicos para las descripciones de habilidades */
    }
</style>

<script>
 
</script>