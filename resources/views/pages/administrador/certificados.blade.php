@php
  $materiasPerdidas = 0;
@endphp
   <style>

@font-face {
    font-family: "Oswald";
    src: url("{{ asset('fonts/Oswald-VariableFont_wght.ttf') }}") format('truetype');
    font-weight: 200 700;
    font-style: normal;
  }

body {
    font-family: "Oswald";
    margin: 0;
    padding: 0;
    font-size: 18px;
  }

  header {
    background-color: #fff;
    color: #000;
    padding: 20px;
    text-align: center;
    width: 60%;
    margin: auto;
    padding-bottom: 0;

    p {
      font-size: 1.1em;
      font-family: sans-serif;
      margin-bottom: -2%;
    }
  }

  .gridCol {
    display: grid;
    grid-template-columns: 2fr 1fr;
    grid-gap: 20px;
    text-align: center;
  }

  .gridCol div {
    border: 2px solid #000 ;
    border-radius: 10px;
  }

  .cellLR{
    border-left: 1px solid #000;
    border-right: 1px solid #000;
  }
  .cellEnd{
    border-bottom: 1px solid #000;
  }


  .studentNuip{
    border: none !important;
    margin-top: -5%;
    margin-bottom: 0px;
  }

  .expedition {
   appearance: none;
   border: none;
    font-family: 'Oswald';
    font-size: 1em;
  }

  h1 {
    font-size: 2em;
  }

  main {
    width: 80%;
    margin: auto;

    article {
      font-size: 1.2em;
    }
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  th {
    background-color: #ccc;
    border: 1px solid #000;
    padding: 10px;
    text-align: center;
    width: 200px;
  }



  td {
    border: none;
    padding: 10px;
    padding-bottom: 0;
    padding-top: 5px;
    text-align: left;
  }

  .empty {
    width: 15px;
  }

  .nota {
    background-color: #ccc;
  }

  .comment {
    position: relative;
    margin: auto;
    width: 80%;
    text-align: center;
    padding-bottom: 5%;
  }

  .info {
    font-size: 1.3em;
    display: flex;
    flex-flow: column wrap;
    width: 80%;
    margin: auto;
  }


  .firmas {
    display: grid;
    grid-template-columns: 2fr 2fr;
    grid-gap: 20px;
    width: 80%;
    margin: 4% auto auto auto;
    text-align: center;
    justify-content: space-between;
  }

  .img_firma{
    width: 15%;
    position: absolute;
    margin-top: -5%;
    margin-left: -7%;
  }
  .img_firma_mr{
    width: 15%;
    position: absolute;
    margin-top: -3%;
    margin-left: -7%;
  }

  .firma_mr {
    width: 15%;
    margin-top: -2%;
    margin-left: -8%;
  }

  .comment {
    tr:first-child {
        td:first-child {
          text-align: right;
          width: 40%;
          background-color: #ccc;
        }
    }
    tr:last-child {
      td{
        border: 1px solid #000;
      }
    }
}

  @media print {
    @page {
      margin-top: 35mm;
      margin-bottom: 20mm;
    }
  }
    </style>
</head>
<body>

    {{-- Lógica de evaluación (Replicando JS function evaluation) --}}
    @php
        function getDesempeno($grade) {
            if ($grade >= 1 && $grade < 6) return 'BAJO';
            if ($grade >= 6 && $grade < 8) return 'BASICO';
            if ($grade >= 8 && $grade < 9.3) return 'ALTO';
            if ($grade >= 9.3) return 'SUPERIOR';
            return 'N/A';
        }
    @endphp

    <div id="printArea">
        <header>
            <p>Res. No. 14-023 del 23 de septiembre de 2014</p>
            <p>Res. No. 14-032 del 25 de octubre de 2011</p>
            <p>DANE. 311001041423</p>
            <h1 id="titulo">CERTIFICADO ESCOLAR DE VALORACIÓN AÑO {{ $year }}</h1>
            <h2>Los suscritos <br> RECTORA Y SECRETARIO ACADÉMICO</h2>
            <h2>CERTIFICAN</h2>
        </header>

        <main>
            <article id="article-main">
                @if($estudiante)
                    <p>
                        Que el estudiante <strong>{{ $estudiante->nombre . ' ' . $estudiante->apellido }}</strong>
                        identificado con NUIP N° <strong>{{ $estudiante->nuip ?? $estudiante->usuario }}</strong>
                        cursó y aprobó los logros previstos en el Plan de Estudios correspondientes al grado
                        <strong>{{ strtoupper($estudiante->grados[0]->grado ?? 'N/A') }}</strong>,
                        durante el año lectivo <strong>{{ $year }}</strong>, en concordancia con los fines y objetivos de la
                        Ley General de Educación 115 de 1994, el Decreto 1860 de Agosto 3 de 1994 y los criterios de Evaluación y
                        Promoción de la Institución, con las valoraciones e intensidad horaria que se relacionan a continuación:
                    </p>
                @else
                    <p>No se ha seleccionado un estudiante.</p>
                @endif
            </article>

            @foreach($groupedMaterias as $groupType => $group)
                @if(!empty($group['materias']))
                    <table id="subject-{{ $groupType }}">
                        <thead>
                            <tr>
                                <th colspan="2" style="text-align: left; border: 1px solid black; border-bottom: 1px solid black;">
                                    {{ $group['name'] }}
                                </th>
                                <th>INTENSIDAD HORARIA</th>
                                <th>TOTAL</th>
                                <th>DESEMPEÑO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group['materias'] as $materia)
                                <tr>
                                    <td class="empty"></td>


                                    <td>{{ $materia['nombre_es'] }}</td>


                                    <td style="text-align: center;">{{ $materia['ih'] }}</td>


                                    <td style="font-weight: bold; text-align: center;" class="nota">{{ number_format($materia['promedio'], 1) }}</td>
                                    @php
                                    if($materia['promedio'] < 6) $materiasPerdidas++;
                                    @endphp
                                    <td style="text-align: center;">{{ getDesempeno($materia['promedio']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endforeach

        </main>

        <footer>
            <table class="comment">
                <tr>
                    <td>CONCEPTO DEL COMITÉ DE EVALUACIÓN Y PROMOCIÓN</td>
                    <td id="comment" style="text-align: center;">
                        @php
                            $gradoNombre = strtoupper($estudiante->grados[0]->grado ?? '');
                            $esOnce = strpos($gradoNombre, 'UNDÉCIMO') !== false || strpos($gradoNombre, 'UNDECIMO') !== false;
                        @endphp

                        @if($esOnce)
                            EL ESTUDIANTE SE ENCUENTRA GRADUADO COMO BACHILLER BILINGÜE ACADÉMICO CON ÉNFASIS ARTÍSTICO.
                        @elseif($materiasPerdidas > 0)
                            EL ESTUDIANTE NO SE PROMUEVE AL SIGUIENTE GRADO.
                        @else
                            EL ESTUDIANTE SE PROMUEVE AL SIGUIENTE GRADO.
                        @endif
                    </td>
                </tr>
            </table>

            <div class="firmas">
                <div>
                    {{-- Asegúrate de que las rutas de las imágenes sean accesibles desde public/ --}}
                    <img class="img_firma" src="{{ asset('img/firma_teacher.avif') }}" alt="Firma Rectora">
                    <p>LIZZETH HERNANDEZ DUQUE<br>RECTORA</p>
                </div>
                <div>
                    <img class="img_firma_mr" src="{{ asset('img/firma_mr.avif') }}" alt="Firma Secretario">
                    <p>JUAN CARLOS ACEVEDO<br>SECRETARIO ACADÉMICO</p>
                </div>
            </div>

            <div class="info">
                <small>
                    Se expide en Bogotá, a los <span>{{ $fechaActual['dia'] }}</span> días del mes de
                    <span>{{ $fechaActual['mes'] }}</span> de <span>{{ $fechaActual['anio'] }}</span>
                </small>
                <small>Nota: Es fiel copia de los archivos que reposan en el plantel</small>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.nota').forEach(element => {
            let grade = {{ $estudiante->grados[0]->id}};
            if(grade < 4){
            let nota = element.textContent;
            console.log(nota);
            if(nota > 8){
                element.innerHTML = `
                <td><img src="{{ asset('img/icons/boletin/happy-face.png') }}" alt="Happy Emoji" style="width: 30px;"></td>
                `;
                element.nextElementSibling.textContent = 'Lo lograste';
            }
            else if(nota == 'N/A'){

            }
            else{
                element.innerHTML = `
                <td><img src="{{ asset('img/icons/boletin/sad-face.png') }}" alt="Prejudice Emoji" style="width: 30px;"></td>
                `;
                element.nextElementSibling.textContent = 'En proceso';
            }
            }
        });
        });
    </script>
