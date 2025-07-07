<div>
    <!-- Well begun is half done. - Aristotle -->
</div>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Report Card</title>

    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
   <style>
  
       @page {
      margin-top: 3cm;
      margin-bottom: 2cm;
    } 
          body {
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
  
    }

    #btnCrearPdf {
      background-color: #d7d7d7;
      border-radius: 10px;
      height:7%;
      width: 5%;
      position: fixed;
      right: 2%;
      top: 2%;
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
      width: 90%;
      border-collapse: collapse;
      margin-top:0px;
      border-spacing: 0;
      border: 1px solid #000;
      margin-left: auto;
      margin-right: auto;
      font-size: 11px;
    }
    .reportcard th{
      font-size: 11px !important;
      border: 1px solid #000;
      text-align: center;
    }
    .reportcard td {
      border: 1px solid #000;
      text-align: start;
    }
  
    .reportcard td:first-child{
      width: 200px;
    }
    .reportcard .subjectrow {
      background-color: #D8D8D8;
    }
    .reportcard .subjectrow td {
      text-align: center;
      border-bottom: 1px solid #000;
    }

    .reportcard .subjectrow:not(:first-child) td {
      border-top: 1px solid #ccc;
    }
  .skillrow td {
      text-align: start;
        border: 0;
        padding: 1;
        margin-top: 0px;
        margin-bottom: 0px;   
    }
    .skillrow td{
       text-align: left; 
    }
    .skillrow tr {
      padding: 0;
    }

    .skills{
      text-align: left;
      width: 500px;
    }
  
   
  .space  td{
      border: 0;
    }
  
  .header2 {
        display: block;
        margin-bottom: 10px;
    }
  
    .header2 h2 {
      text-align: center;
      margin-top: 0px;
      margin-bottom: 0px;
    }
  
    .header3 {
      background-color: #fff;
      text-align: center;
      width: 80%;
      position: relative;
      left: 10%;
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
  
  .coments img {
    position: static;
    margin-bottom: -20px;
    height: 100;
  }

  .observation :last-child {
    text-align: start;
  }

  @media only screen and (max-width: 768px) {
    #btnCrearPdf {
      background-color: #d7d7d7;
      border-radius: 10px;
      height:6vh;
      width: max-content;
      position: fixed;
      right: 3%;
      top: 2%;
    }
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

}
   
      </style>
  
  </head>
  <body>
    <button id="btnCrearPdf"><img src="../images/icons/bx-download.svg" alt="" srcset=""></button>
      <!-- <a href="./pdf.php"><button>PDF</button></a> -->
    <div class="reportcard" id="reportcard">
  
      <img class="membrete" src="../images/Membrete1-2.avif" alt="">
  
      <h1>Student Report Card</h1>
  
      <div id="header2" class="header2">
          <h2 id="currentDate">19th JULY <span id="currentYear"></span></h2>
          <h2 id="currenPeriod">TERM <span id="period"></span></h2>
      </div>
  
      <div class="header3">
          <table class="info">
              <tr>
                  <td>Nombre:</td> {{-- Ahora los datos vienen del controlador --}}
                  <td>{{ $student['name'] }}</td>
                  <td>ID:</td>
                  <td>{{ $student['nuip'] }}</td>
                  <td>Grado:</td>
                  <td>{{ $student['grade'] }}</td>
              </tr>
          </table>  
      </div>
  
      <div class="header4">
        <table>
            <tr>
              <td>Term average</td>
              <td>{{ $student['termAverage'] }}</td>
              <td>Final average</td>
              <td>{{ $student['finalAverage'] }}</td>
            </tr>
        </table>
      </div>

      <div id="tabla"> {{-- El contenedor para las tablas de materias --}}
        @foreach ($subjects as $subject)
            <x-report-card-module 
                :materia="$subject['materia']"
                :c1="$subject['c1']"
                :c2="$subject['c2']" 
                :c3="$subject['c3']" 
                :c4="$subject['c4']" 
                :c5="$subject['c5']" 
                :c6="$subject['c6']" 
                :e="$subject['e']" 
                :n1="$subject['n1']" 
                :n2="$subject['n2']" 
                :n3="$subject['n3']" 
                :n4="$subject['n4']" 
                :n5="$subject['n5']" 
                :n6="$subject['n6']" 
                :ne="$subject['ne']" 
                :F1="$subject['F1']" 
                :R1="$subject['R1']" 
                :F2="$subject['F2']" 
                :R2="$subject['R2']" 
                :F3="$subject['F3']" 
                :R3="$subject['R3']" 
                :F4="$subject['F4']" 
                :R4="$subject['R4']" 
                :Ft="$subject['Ft']" 
                :ih="$subject['ih']"
            />
        @endforeach
      </div>

      <div class="footer">
        <table>
            <tr>
                <td style="text-align: center;">Note 1</td>
                <td style="text-align: center;">STRENGTHS AND WEAKNESSES</td>
                <td>El nivel de fortaleza que presenta el estudiante en cada asignatura está determinado por las siglas DS: "Desempeño Superior", 
                    DA: "Desempeño Alto".  Y las dificultades están determinadas por las siglas DBs: "Desempeño Básico", DBj: "Desempeño Bajo".
                </td>
            </tr>
        </table>
  
        <table>
                <tr>
                    
                    <td rowspan="2">LEVELS OF PERFORMANCE</td>
                    <td>LOW</td>
                    <td>BASIC</td>
                    <td>HIGH</td>
                    <td>OUTSTANDING</td>
                </tr>
                <tr>
                    <td>DBj (1,0 - 5,99)</td>
                    <td>DBs (6,0 - 7,99)</td>
                    <td>DA (8,0 - 9,29)</td>
                    <td>DS (9,3 - 10,0)</td>
                </tr>
        </table>
  
        <div class="coments">
            <table>
              <tr class="observation" id="observation">
                <td>Observación:</td>
                <td class="observationText" id="observationText">{{ $student['observationText'] }}</td>
              </tr>
                <tr>
                  <td ><img class="firma"  src="../images/firma_teacher.avif"></td>
                  <td id="teacherName" style="border-bottom: 2px #000;"></td>
                </tr>
                <tr>
                  <td>LIZZETH HERNANDEZ DUQUE<br>PRINCIPAL</td>
                  <td style="vertical-align: top;">HOMEROOM TEACHER</td>
                </tr>
            </table>
        </div>
      </div>
  
    </div>

    {{-- <script type="module" src="../scripts/boletin_copy.js"></script> --}} {{-- Ya no necesitas este script para rellenar datos --}}
  </body>
</html>
