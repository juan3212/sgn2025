<x-app-layout>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Knewave&display=swap" rel="stylesheet">
<style>

.story-script-regular {
  font-family: "Knewave", sans-serif;
  font-weight: 400;
  font-style: normal;
}

.nota{
    font-family: "Knewave", system-ui !important;
    font-weight: 400 !important;
    font-style: normal !important;
}


  .reportcard {
    background-color: #fff;
  }

     .membrete {
      width: 50%;
      position: relative;
      left: 25%;
      margin-bottom: 13px;
    }
   
    .reportcard h1 {
      text-align: center;
      margin-bottom: 0px;
    }
    .reportcard table {
      width: 80%;
      margin-top:0px;
      margin-left: auto;
      margin-right: auto;
      font-size: 11px;
    }
   
  .space  td{
      border: 0;
    }
  

  
  .header3 table {
    font-size: 12px;
    margin-bottom: 5px;
  }
   
  .header3 td {
    border: none;
    text-align: left;
    font-weight: bold;
  }
  .header3 td:first-child{
    width:fit-content;
  }
  
  .header4 {
    background-color: #fff;
      text-align: center;
      width: 80%;
      position: relative;
      left: 10%;
  }
  
  .header4 table {
    font-size: 12px;
    margin-bottom: 15px;
    
  }
  
  .header4 td {
    border: none;
     text-align: center;
  }
   
  
  .footer table {
    margin-top: 7px;
    text-align: center;
  }

  .footer table:first-child {
    margin-top: 20px;
  }
  
  .footer td {
    width: max-content;
  }

  .coments td {
    text-align: center;
    vertical-align: bottom;
    font-size: 12px;
    font-weight: bold;
    border-bottom: #000;
    border-left: hidden;
    border-right: hidden;
  }
  
  .nota {
    table:first-child{
      min-width: 100%;
    }
  }

  @media only screen and (max-width: 768px) {

    .reportcard td:first-child{
      width: 20%;
    }
    .reportcard table {
      width: 90%;
      border-collapse: collapse;
      margin-top:0px;
      border-spacing: 0;
      border: 1px solid #000;
      margin-left: auto;
      margin-right: auto;
      font-size: 7px;
    }
    .reportcard th{
      font-size: 7px !important;
      border: 1px solid #000;
      text-align: center;
    }

    .header3 table {
    font-size: 7px;
    margin-bottom: 5px;
  }

  .header4 table {
    font-size: 7px;
    margin-bottom: 15px;
    
  }

  .no-print {
        display: block;
    }
    /* Clase específica para cuando se está generando el PDF (opcional si usas html2pdf con ignore) */
    @media print {
        .no-print {
            display: none !important;
        }
        .reportcard {
          background-color: #fff;
          width: 100%;      /* Asegura que ocupe el ancho completo del PDF */
          margin: 0 auto;
        }

        .membrete {
            max-width: 100%;
        }

}


</style>

<div class="flex w-full mx-auto px-8 no-print bg-white max-sm:px-0 max-sm:py-4 max-sm:justify-center">
    <button onclick="generatePDF()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center max-sm:w-full max-sm:justify-center max-sm:mx-auto">
        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/></svg>
        <span>Descargar PDF</span>
    </button>
</div>

<div class="reportcard" id="reportcard">
  
      <img class="membrete" src="{{ asset('img/Membrete1-2.avif') }}" alt="">
  
      <h1 class="font-bold text-3xl">Student Report Card</h1>
  
      <div id="header2" class="header2 font-bold text-center text-xl">
          <h2 id="currentDate" class="font-size-12">{{ $date }}</h2>
          <h2 id="currenPeriod" class="font-size-12">TERM <span id="period">{{ $periodo }}</span></h2>
      </div>
  
      <div class="w-3/4 md:w-3/4 mx-auto mb-2">
          <table class="info border border-black text-center font-bold">
              <tr class="p-2">
                  <td class="text-right">Nombre:</td> 
                  <td>{{ $user['nombre'] . ' ' . $user['apellido'] }}</td>
                  <td class="text-right">ID:</td>
                  <td>{{ $user['nuip'] }}</td>
                  <td class="text-right">Grado:</td>
                  <td>{{ $user['grados'].' '.$user['grupos']}}</td>
              </tr>
          </table>  
      </div>
  
      <div class="w-3/4 md:w-3/4 mx-auto text-center mb-4" id="averages">
        <table class="border border-black">
            <tr>
              <td>Term average</td>
              <td class="nota" id="termAverageValue"></td>
              <td>Final average</td>
              <td class="nota" id="finalAverageValue"></td>
            </tr>
        </table>
      </div>

      <div>
          @livewire('pages.estudiantes.boletin', ['estudianteID' => $estudianteID])
      </div>

      <div class="footer">
        <table class="border border-black levels">
            <tr>
                <td class="text-center border border-black">Note 1</td>
                <td class="text-center border border-black">STRENGTHS AND WEAKNESSES</td>
                <td class="text-center border border-black">El nivel de fortaleza que presenta el estudiante en cada asignatura está determinado por las siglas DS: "Desempeño Superior", 
                    DA: "Desempeño Alto".  Y las dificultades están determinadas por las siglas DBs: "Desempeño Básico", DBj: "Desempeño Bajo".
                </td>
            </tr>
        </table>

        <table class="border border-black" id="comentarios" hidden>
            <tr>
                <td class="text-center border border-black">Comentarios</td>
                <td class="text-center border border-black">{{ $comentario->comentario }}</td>
            </tr>
        </table>
  
        <table class="border border-black levels">
                <tr class="border border-black">
                    
                    <td rowspan="2" class="border border-black">LEVELS OF PERFORMANCE</td>
                    <td class="border border-black">LOW</td>
                    <td class="border border-black">BASIC</td>
                    <td class="border border-black">HIGH</td>
                    <td class="border border-black">OUTSTANDING</td>
                </tr>
                <tr class="border border-black">
                    <td class="border border-black">DBj (1,0 - 5,99)</td>
                    <td class="border border-black">DBs (6,0 - 7,99)</td>
                    <td class="border border-black">DA (8,0 - 9,29)</td>
                    <td class="border border-black">DS (9,3 - 10,0)</td>
                </tr>
        </table>
  
        <div class="coments">
            <table class="border border-t-black border-b-black">
                <tr class="items-center">
                    <td class="text-center">
                        <img class="w-32 h-24 mx-auto -mt-4 -mb-6" src="{{ asset('img/firma_teacher.avif') }}">
                    </td>
                    <td id="teacherName" class="border-b-2 border-black"></td>
                </tr>
                <tr>
                    <td class="pb-2">LIZZETH HERNANDEZ DUQUE<br>PRINCIPAL</td>
                    <td class="pb-2">
                        <div class="-mt-2">HOMEROOM TEACHER</div>
                    </td>
                </tr>
            </table>
        </div>
      </div>
  
    </div>

    @livewireScripts
    <script>
      Livewire.on('directorCursoNombre', (nombre) => {
          console.log('Recibido desde Livewire:', nombre);
          document.getElementById('teacherName').textContent = nombre;
      });

      document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('termAverageValue').textContent = termAverage;
      document.getElementById('finalAverageValue').textContent = finalAverage;
      });


      function generatePDF() {
        const element = document.getElementById('reportcard');
        const width = window.innerWidth;
        const format = width > 768 ? 'a2' : 'a3';
        
        const opt = {
            margin:       0.1, 
            filename:     'Boletin_{{ $user['nuip'] }}.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true }, 
            jsPDF:        { unit: 'in', format: format, orientation: 'portrait' } 
        };

  
        html2pdf()
            .set(opt)
            .from(element)
            .save();
    }

    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.nota').forEach(element => {
        let grade = {{ $user['gradoID']}};
        if(grade < 4){
          let averages = document.getElementById('averages');
          let levels = document.querySelectorAll('.levels');
          let comments = document.getElementById('comentarios');
          averages.hidden = true;
          levels.forEach(level => level.hidden = true);
          comments.hidden = false;
          let nota = element.textContent;
          if(nota > 8){
            element.innerHTML = `<table>
            <tr>
            <td>Lo lograste</td>
            <td><img src="{{ asset('img/icons/boletin/happy-face.png') }}" alt="Happy Emoji" style="width: 20px;"></td>
            </tr>
            </table>`;
          }
          else{
            element.innerHTML = `<table>
            <tr>
            <td>En proceso</td>
            <td><img src="{{ asset('img/icons/boletin/sad-face.png') }}" alt="Prejudice Emoji" style="width: 20px;"></td>
            </tr>
            </table>`;
          }
        }
      });
    });

    </script>


</x-app-layout>