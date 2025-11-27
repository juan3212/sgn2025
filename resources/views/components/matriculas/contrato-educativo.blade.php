@props([
    'studentName' => 'Juan Pablo Acevedo Hernandez',
    'studentGrade' => 'Décimo',
    'fatherName' => '',
    'fatherCc' => '',
    'fatherEmail' => '',
    'fatherPhone' => '',
    'motherName' => '',
    'motherCc' => '',
    'motherEmail' => '',
    'motherPhone' => '',
    'address' => 'Bogotá D.C',
    'day' => date('d'),
    'month' => date('m'),
    'year' => date('Y'),
])

<div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- Estilos para fuentes locales --}}
    <style>
        @font-face {
            font-family: 'Cedarville_Cursive';
            src: url('{{ asset('fonts/CedarvilleCursive-Regular.ttf') }}');
            font-weight: 400;
        }

        @font-face {
            font-family: 'Charlotte';
            src: url('{{ asset('fonts/charlotte.ttf') }}');
            font-weight: 400;
        }

        .firmas {
   
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-column-gap: 20px;
    grid-row-gap: 10%;
    position: relative;
    left: 0%;
    margin-top: 5%;
    padding-bottom: 3%;
}

.firma-juan{
    align-items: center;
}
.firma-representante {
    width: 50%;
    position: relative;
    margin-left: -8%;
    margin-bottom: -4%;
}


.firma-padre {
    font-family: 'Charlotte';
    font-size: 14px;
    font-weight: bold;
    position: relative;
}

.esp-firma-juan {
    width: 30%;
}

.esp-firma-padre {
    width: 50%;
    height: 50%;
    margin-left: 20%;
    

        .font-charlotte {
            font-family: 'Charlotte', sans-serif;
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

    {{-- Botón Flotante para Descargar --}}
    <div class="flex w-full mx-auto px-8 no-print bg-white max-sm:px-0 max-sm:py-4 max-sm:justify-center">
        <button onclick="generatePDF()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center max-sm:w-full max-sm:justify-center max-sm:mx-auto">
            <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/></svg>
            <span>Descargar PDF</span>
        </button>
    </div>

    <article id="tres" class="bg-white w-full min-h-screen pb-10">
        <div class="cuerpo w-full bg-white relative">
            
            <section id="cuatro" class="relative pt-4">
                
                {{-- Encabezado --}}
                <img src="{{ asset('img/encabezado.png') }}" alt="Encabezado Colegio" class="relative w-[70%] h-[120px] block mx-auto object-contain my-4">

                {{-- Título --}}
                <h1 class="text-black font-black text-2xl text-center p-2.5 rounded-lg mb-6 uppercase leading-tight font-[Arial]">
                    CONTRATO DE SERVICIOS EDUCATIVOS AÑO LECTIVO 2026
                </h1>

                {{-- Contenedor Principal del Texto --}}
                <div class="w-[80%] mx-auto text-justify space-y-4 text-sm md:text-base leading-relaxed text-black font-sans">
                    
                    {{-- Parte A: Representante Legal --}}
                    <div id="parte-a">
                        <p>Entre los suscritos a saber, <span class="font-bold">JUAN GONZÁLEZ TALERO,</span> mayor de edad y vecino de Bogotá, identificado con la C.C. No 79.863.883 de Bogotá, obrando en calidad de Representante Legal del COLEGIO BILINGÜE CEDAM, con NIT. [cite_start]79863883-7, institución educativa con reconocimiento oficial de estudios de la Secretaria de Educación Distrital No 02530 No. 14-023 No.14032 para EDUCACIÓN PREESCOLAR BILINGÜE (Grados PREKINDER, KÍNDER y TRANSICIÓN), EDUCACIÓN BÁSICA ciclos de PRIMARIA BILINGÜE y SECUNDARIA BILINGÜE, EDUCACIÓN MEDIA BILINGÜE ACADÉMICA CON ESPECIALIDAD ARTÍSTICA, y quien en adelante se denominará EL COLEGIO, y [cite: 5, 6, 7]</p>
                    </div>

                    {{-- Datos Padres --}}
                    <div id="padre">
                        <p>
                            <span class="font-bold">PADRES Y/O ACUDIENTE:</span> 
                            <span class="font-bold underline">{{ $fatherName }}</span>, 
                            <span class="font-bold">CC. No:</span> <span class="font-bold underline">{{ $fatherCc }}</span><br>
                            <span class="font-bold">RESIDENCIADO Y DOMICILIADO EN</span> <span class="font-bold underline">{{ $address }}</span> 
                            <span class="font-bold">CON DIRECCIÓN ELECTRÓNICA</span> <span class="font-bold underline">{{ $fatherEmail }}</span> 
                            [cite_start]<span class="font-bold">Y CELULAR</span> <span class="font-bold underline">{{ $fatherPhone }}</span> [cite: 8, 9, 10, 11, 12]
                        </p>
                    </div>

                    <div id="madre">
                        <p>
                            <span class="font-bold">PADRES Y/O ACUDIENTE:</span> 
                            <span class="font-bold underline">{{ $motherName }}</span>, 
                            <span class="font-bold">CC. No:</span> <span class="font-bold underline">{{ $motherCc }}</span><br>
                            <span class="font-bold">RESIDENCIADO Y DOMICILIADO EN</span> <span class="font-bold underline">{{ $address }}</span> 
                            <span class="font-bold">CON DIRECCIÓN ELECTRÓNICA</span> <span class="font-bold underline">{{ $motherEmail }}</span> 
                            [cite_start]<span class="font-bold">Y CELULAR</span> <span class="font-bold underline">{{ $motherPhone }}</span> [cite: 15, 16, 17, 18, 20, 21]
                        </p>
                    </div>

                    <div id="parte-b">
                        [cite_start]<p>vecinos de esta ciudad e identificados con los números de Cédula de Ciudadanía como aparecen al pie de sus correspondientes firmas, quien en adelante y para los efectos de este contrato se denominarán los PADRES DE FAMILIA Y/O ACUDIENTES, con obligaciones solidarias, han convenido suscribir el presente CONTRATO DE SERVICIOS EDUCATIVOS, como representantes del [cite: 22]</p>
                    </div>

                    {{-- Parte C: Cláusulas 1 a 4 --}}
                    <div id="parte-c">
                        <p>
                            ESTUDIANTE: <span class="font-bold underline uppercase">{{ $studentName }}</span>,
                            el cual se regirá por las siguientes cláusulas: <br><br>
                            
                            <span class="font-bold">PRIMERA - DEFINICIÓN DEL CONTRATO:</span> En concordancia con el Artículo 95 de la Ley 115 de 1994 el presente contrato formaliza la vinculación del EDUCANDO al servicio educativo que ofrece EL COLEGIO y compromete a las partes y al EDUCANDO en las obligaciones legales y pedagógicas tendientes a hacer efectiva la prestación del servicio público educativo en Institución privada, obligaciones que son colectivas y esenciales para la consecución del objeto y de los fines comunes, toda vez que el derecho a la educación se considera Derecho - Deber. Siendo este un Contrato de Cooperación Educativa que obedece a las disposiciones constitucionales en las cuales se establece una responsabilidad compartida de la educación, en donde concurren obligaciones de los educadores, los educandos, los padres y/o acudientes, tendientes a hacer efectiva la prestación del servicio público educativo como función social, por parte de los Colegios Privados, de manera que el incumplimiento de cualquiera de las obligaciones adquiridas por los contratantes hace imposible la consecución del fin común. [cite_start]Por tanto, las obligaciones que se adquieren en el presente contrato son correlativas y esenciales para el logro de los objetivos educacionales y por ende de los fines establecidos en el artículo 5º de la Ley 115 de 1994 y el P.E.I[cite: 24, 25, 26].<br><br>
                            
                            [cite_start]<span class="font-bold">SEGUNDA - OBJETO:</span> El objeto del presente Contrato de Servicios Educativos, es procurar la formación integral del EDUCANDO mediante la reciproca complementación de esfuerzos del mismo, de los PADRES DE FAMILIA Y/O ACUDIENTES y del COLEGIO con la búsqueda del pleno desarrollo de la personalidad del EDUCANDO y de un excelente rendimiento académico en el ejercicio del programa curricular correspondiente al grado para lo cual el COLEGIO por intermedio de los docentes, elementos didácticos, plan de estudios y herramientas diferenciales promoverá el aprendizaje del EDUCANDO[cite: 26, 27].<br><br>
                            
                            <span class="font-bold">TERCERA: DURACIÓN Y RENOVACIÓN:</span> El presente Contrato de Servicios Educativos tendrá una vigencia de Un (1) año lectivo Escolar (10 meses), contado a partir del Primero (1) de febrero de 2026, hasta el (30) de noviembre de 2026 y su ejecución será sucesiva por periodos mensuales. [cite_start]El presente contrato NO tendrá una renovación automática, deberá suscribirse nuevo documento con similares características siempre y cuando el EDUCANDO y los PADRES Y/O ACUDIENTE hayan cumplido satisfactoriamente las obligaciones pactadas en el presente documento, así como del Manual de Convivencia de la institución y demás disposiciones internas de obligatorio cumplimiento[cite: 28, 29].<br><br>
                            
                            <span class="font-bold">CUARTO: PRESENCIALIDAD, ALTERNANCIA Y VIRTUALIDAD:</span> EI COLEGIO BILINGÜE CEDAM prestará el servicio educativo de manera presencial, conforme a las disposiciones de la Secretaria de Educación Distrital, las autoridades sanitarias distritales competentes, en caso de fuerza mayor, orden de autoridad competente o circunstancias que afecten la seguridad, salud o integridad de la comunidad educativa CEDAM, sea necesario adoptar temporalmente modalidades de educación virtual, remota o híbrida, el COLEGIO podrá implementar dichas estrategias sin que ello constituya incumplimiento del presente contrato ni modifique el valor pactado por concepto de matrícula o pensión. [cite_start]El COLEGIO garantizará la calidad del servicio educativo en cualquiera de las modalidades que se adopten, y los PADRES DE FAMILIA Y/O ACUDIENTES se comprometen a apoyar el proceso académico del EDUCANDO, asegurando el cumplimiento de sus deberes escolares y el uso adecuado de las herramientas tecnológicas dispuestas por la institución[cite: 30, 31, 35, 36]. [cite_start]<span class="font-bold">PARÁGRAFO:</span> EI COLEGIO adoptará las medidas de bioseguridad, prevención y autocuidado que determinen las autoridades competentes y promoverá entre sus estudiantes la responsabilidad individual y colectiva frente al bienestar de la comunidad educativa[cite: 37].
                        </p>
                    </div>

                    {{-- Datos Económicos --}}
                    <div id="datos-estudiante">
                        <p>
                            <span class="font-bold">QUINTA. VALOR:</span> EI presente Contrato de Servicios Educativos tiene un costo de:<br>
                            NOMBRE ESTUDIANTE: <span class="font-bold underline uppercase">{{ $studentName }}</span><br>
                            CURSO 2026: <span class="font-bold underline uppercase">{{ $studentGrade }}</span><br>
                            VALOR MATRÍCULA: <span class="font-bold underline">$712.000</span> Pesos Colombianos.<br>
                            [cite_start]VALOR PENSIÓN MENSUAL: <span class="font-bold underline">$665.500</span> Pesos Colombianos. [cite: 38, 39, 40, 41, 42]<br>
                        </p>
                    </div>

                    {{-- Parte D: Resto de Clausulas (Texto Completo) --}}
                    <div id="parte-d">
                        <p>
                            pagaderos por los PADRES DE FAMILIA Y/O ACUDIENTES solidariamente, los primeros siete (7) días calendario de cada mes a partir de febrero de 2026 hasta el final de su vigencia en noviembre de 2026, en caso de no cumplir este plazo, a partir del día siguiente se cobrarán intereses de mora a la tasa máxima legal vigente por cada día de no omisión en un mismo mes. Si el responsable no cancela el valor de la pensión pasados los primeros siete (7) días del mes, deberá cancelar un recargo administrativo de gestión de recaudo, equivalente a veinte mil pesos ($20.000) por cada mes de retraso. Este valor se adicionará a la factura de los costos educativos del mes y permanecerá vigente hasta que se cumpla con la obligación. [cite_start]El retardo en el pago, causará además pérdida de beneficios económicos, como becas, apoyos, auxilios entre otros[cite: 43, 44, 45, 46].<br><br>

                            <span class="font-bold">SEXTA.- SERVICIOS ADICIONALES Y EXTRACURRICULARES:</span> Los servicios complementarios, tales como ruta escolar, onces escolares, almuerzo, jornada adicional y actividades extracurriculares (como curso de inglés, ensamble musical, técnica vocal, ballet, fútbol, ritmos latinos, entre otros), son de carácter opcional y se prestan de manera independiente al servicio educativo principal, Los valores correspondientes a estos servicios son fijos y mensuales, y se mantienen durante todo el mes calendario, sin ajustes por días no asistidos ni por los periodos vacacionales previstos en el calendario académico. [cite_start]En caso de que el Padre de familia, acudiente o responsable de los pagos desee suspender la participación del estudiante en alguno de estos servicios, deberá informarlo al Colegio con una antelación mínima de cinco (5) días antes de finalizar el mes en curso; de lo contrario, se entenderá que el servicio continúa activo y se generará el cobro correspondiente al mes siguiente, los valores de estos servicios no hacen parte de la matrícula ni de la pensión escolar, y su pago corresponde a una obligación independiente, derivada de la prestación efectiva de cada servicio[cite: 47, 48, 49].<br><br>

                            <span class="font-bold">SÉPTIMA. OBLIGACIONES ESENCIALES MUTUAS DEL CONTRATO:</span> Son obligaciones de la esencia del presente contrato para cumplir con el fin común de la educación del EDUCANDO, las siguientes: A) Por parte del EDUCANDO, asistir al COLEGIO y/o a sus clases y cumplir las pautas definidas y establecidas en el Proyecto Educativo Institucional y en el reglamento interno o manual de convivencia en los horarios, con las actividades exigidas, con las reglas de comportamiento del manual de convivencia, y con absoluto respeto a las normas constitucionales. B) portar el uniforme en debida forma y hacer uso de los enseres de la Institución con total cuidado y responsabilidad C) llevar sus útiles, guías y cuadernos de enseñanza virtual o manual en el orden impartido en clase D) Facilitar, promover y realizar todos los deberes en las plataformas tecnológicas, digitales o remotas que sean necesarias para la prestación del servicio educativo, cuando por fuerza mayor, disposición de autoridad competente o circunstancias institucionales se adopten modalidades de educación virtual, remota o híbrida. [cite_start]En tales casos, los estudiantes deberán cumplir cabalmente las obligaciones académicas establecidas en el Manual de Convivencia y ajustadas a la modalidad implementada) los padres deberán hacerse responsables del bajo rendimiento académico del EDUCANDO y tomar las acciones posibles para el aumento de su excelencia G) por parte del COLEGIO, impartir la enseñanza contratada y propiciar la mejor calidad y continuidad del servicio[cite: 50, 51, 52].<br><br>

                            <span class="font-bold">OCTAVA. - DERECHOS Y OBLIGACIONES DE LOS PADRES O ACUDIENTES:</span> En cumplimiento de las normas vigentes para el servicio educativo y en concordancia con el objeto del presente contrato, los PADRES, tienen los siguientes <span class="font-bold">SON DERECHOS:</span> a) Exigir la permanente y correcta prestación del servicio educativo. b) Exigir el cumplimiento del Proyecto Educativo Institucional. c) Participar en el proceso educativo. d) Buscar y recibir orientación sobre la educación de su hijo. e) Exigir que el servicio educativo tenga el nivel académico prescrito por la ley, de acuerdo con los indicadores de excelencia educativa estipulada por las autoridades oficiales correspondientes. De igual forma, los padres tienen la siguientes <span class="font-bold">SON OBLIGACIONES:</span> a) Pagar estricta y cumplidamente los costos del servicio educativo (pensión y servicios adicionales) dentro de los primeros SIETE (7) días calendario de cada mes. b) A propiciar al EDUCANDO el ambiente familiar de acuerdo con su desarrollo integral. c) A velar por el proceso del educando en todos los órdenes. d) A cumplir estrictamente las citas y llamadas que hacen las directivas y docentes del plantel. e) A cumplir el Proyecto Educativo Institucional y el reglamento interno o manual de convivencia del COLEGIO. f) Proveer al EDUCANDO de todos los elementos tecnológicos para su enseñanza, así como del material de protección especial personal de Bioseguridad para evitar el riesgo de Contagio en las instalaciones y espacios externos al Colegio g) Garantizar el aprendizaje y sensibilización del autocuidado del menor con el uso de tapabocas, lavado de manos, no enviar a su hijo al Colegio cuando presente alguna enfermedad contagiosa que afecte, a sus compañeros y comunidad educativa) Controlar el uso de redes sociales del educando y velar por que su hijo no comenta actos de cyberbullyng o acoso por redes a cualquier miembro de la comunidad educativa (1) Prestar la mayor colaboración posible a las directivas y profesores para la obtención del fin propuesto. J) Estar a paz y salvo por concepto en el pago de pensiones y demás costos educativos adquiridos, al solicitar trámites administrativos con la institución Educativa. K) Están prohibidos los grupos por WhatsApp o redes sociales entre padres de familia, estudiantes, docentes o personal perteneciente a la comunidad educativa Cedam, que dañen, destruyan, difamen el buen nombre del COLEGIO BILINGÜE CEDAM. [cite_start]L) LOS PADRES Y/O ACUDIENTES no podrá establecer relaciones o vínculos afectivos cercanos con los docentes, personal administrativo, personal operativo, conductores, monitoras, enfermera, vigilantes y demás personal que le preste un servicio al educando [cite: 53-72].<br><br>

                            <span class="font-bold">NOVENA: DERECHOS Y OBLIGACIONES DEL EDUCANDO:</span> En cumplimiento de las normas vigentes para el servicio educativo y en concordancia con el objeto del presente contrato, el EDUCANDO tiene los siguientes <span class="font-bold">SON DERECHOS:</span> a) Recibir una educación de acuerdo a los principios que inspiran el Proyecto Educativo Institucional del plantel. b) A ser valorados y respetados como personas. c) A participar en el desarrollo del servicio educativo a través de programas y proyectos establecidos por el colegio. d) A recibir de directivos y profesores buen ejemplo, acompañamiento, estímulo y atención, ser escuchados oportunamente e) A participar en las instancias establecidas en el Reglamento Interno o Manual de Convivencia. [cite_start]<span class="font-bold">SON OBLIGACIONES</span> a) A cumplir, respetar y acatar el Reglamento Interno o Manual de Convivencia del COLEGIO y los principios que orientan el Proyecto Educativo Institucional. b) Respetar y valorar a todas las personas que integran la comunidad Estudiantil. c) Enaltecer en sus actividades y expresiones el buen nombre del COLEGIO; y d) Asistir puntual y respetuosamente a las clases y actividades que programe el COLEGIO; f) No cometer actos de cyberbullyng o acoso por redes sociales a cualquier miembro de la comunidad educativa; g) Están prohibidos los grupos por WhatsApp o redes sociales entre padres de familia, estudiantes, docentes o personal perteneciente a la comunidad educativa Cedam, que dañen, destruyan, difamen el buen nombre del COLEGIO BILINGÜE CEDAM [cite: 73-81].<br><br>

                            <span class="font-bold">DECIMA: DERECHOS Y OBLIGACIONES DEL COLEGIO:</span> En cumplimiento de las normas vigentes para el servicio educativo y en concordancia con el objeto del presente contrato, el COLEGIO tiene los siguientes <span class="font-bold">DERECHOS:</span> a) A exigir el cumplimiento del Reglamento Interno o Manual de Convivencia del establecimiento por parte del EDUCANDO y de los deberes académicos que derivan del servicio. b) A exigir a los PADRES y/o ACUDIENTES el cumplimiento de sus obligaciones como responsables del EDUCANDO. c) A recuperar los costos incurridos en el servicio y a exigir y lograr el pago de los derechos correspondientes a matrícula, pensión, transporte y otros costos, por todos los medios lícitos a su alcance. d) A reservarse el derecho de NO RENOVACIÓN DE MATRÍCULA según estipulaciones del Reglamento Interno o Manual de Convivencia y por razones de comportamiento, rendimiento académico. [cite_start]Así mismo el COLEGIO tendrá las siguientes <span class="font-bold">OBLIGACIONES</span> a: a) A impartir una educación integral de acuerdo con los fines de la educación Colombiana y los lineamientos del Ministerio de Educación Nacional y el ideario del Proyecto Educativo Institucional. b) Desarrollar planes y programas establecidos mediante el Proyecto Educativo Institucional. c) Cumplir y exigir el cumplimiento del Reglamento Interno o Manual de Convivencia del COLEGIO, y d) Prestar en forma regular el servicio educativo dentro de las prescripciones legales [cite: 82-88].<br><br>

                            <span class="font-bold">ONCE: AUTORIZACIÓN PARA EL USO DE LA IMAGEN DEL ESTUDIANTE:</span> Los PADRES DE FAMILIA Y/O ACUDIENTES expresamente autorizan al COLEGIO BILINGÜE CEDAM, al uso de material fotográfico y/o audiovisual en el cual se encuentre la imagen de su hijo (a) y que parte del registro de actividades escolares desarrolladas durante el año escolar, con el fin de ser utilizadas en medios publicitarios, por ejemplo, la página web del COLEGIO BILINGÜE CEDAM, redes sociales de la misma institución, Facebook, Instagram, YouTube, entre otros; imágenes o representaciones audiovisuales tales como videos institucionales, pendones, brochures, material publicitario, participación en actividades institucionales tales como talleres, congresos, capacitaciones dentro y fuera del plantel educativo o cualquier evento de tipo educativo y formativo o como evidencia requerida en la implementación de programas que implemente la institución. [cite_start]En consecuencia, el COLEGIO BILINGÜE CEDAM queda exento de cualquier responsabilidad que terceros en los medios publicitarios mencionados puedan hacer uso inadecuado del material autorizado por los PADRES DE FAMILIA Y/O ACUDIENTES, así mismo la institución o quien represente sus derechos queda exenta al pago de cualquier valor por el uso del material mencionado [cite: 89-96].<br><br>

                            [cite_start]<span class="font-bold">DOCE: AUSENCIA DE RESPONSABILIDAD:</span> EL COLEGIO BILINGÜE CEDAM no se hace responsable por daños, lesiones personales o accidentes provocados por los estudiantes a sus compañeros, al personal del plantel educativo o a los bienes del mismo o de terceros durante la prestación del servicio, por los cual LOS PADRES DE FAMILIA Y/O ACUDIENTES asumen el pago de los mismos, como también el pago de indemnizaciones y reclamos a que haya lugar, ya que LOS PADRES DE FAMILIA Y/O ACUDIENTES deben jugar un papel decisivo en la formación del estudiante haciéndole conocer entre otros el manual de convivencia del COLEGIO BILINGÜE CEDAM[cite: 97, 98].<br><br>

                            [cite_start]<span class="font-bold">TRECE: PROYECTO EDUCATIVO Y REGLAMENTO INTERNO:</span> El Proyecto Educativo Institucional y el Reglamento Interno del COLEGIO BILINGÜE CEDAM o Manual de Convivencia se consideran parte integrante del presente contrato[cite: 98].<br><br>

                            [cite_start]<span class="font-bold">CATORCE: CAUSALES DE TERMINACIÓN DEL CONTRATO:</span> El presente contrato terminará por una de las siguientes causas: a) Por expiración del término fijado o sea el año lectivo. b) Por mutuo acuerdo entre las partes. c) Por muerte del educando. d) por estar el EDUCANDO inmerso en alguna conducta conocida por autoridades judiciales o administrativas que involucre violencia sobre las personas o las cosas e) por haberlo determinado de este modo al interior del comité disciplinario, dada la falta gravísima por conducta arbitraria o lesiva del alumno. f) POR EL RETRASO EN EL PAGO DE PENSIONES POR DOS O MÁS MESES. g) Por las causales determinadas en el Reglamento y/o Manual de Convivencia. h) Cuando el alumno o sus padres de familia y/o acudientes suministren información falsa a la Institución, documentación tanto en las entrevistas de ingreso, como también en el desarrollo de la actividad académica o procedan de mala fe contra los intereses del Colegio, sin perjuicio de que se pueda adelantar acción penal por los mismos hechos. i) Por las causales determinadas en el Reglamento Interno o Manual de Convivencia del COLEGIO BILINGÜE CEDAM [cite: 99-105].<br><br>

                            <span class="font-bold">QUINCE: DERECHO DE NO RENOVACIÓN.</span> EI COLEGIO BILINGÜE CEDAM se reserva el derecho a no renovar o realizar nuevo contrato de servicio educativo para el siguiente año, cuando: 1. lo considere necesario para el bienestar de la comunidad educativa, el cuerpo docente, el equipo de alumnos y especialmente para el EDUCANDO, por lo que podrá optar por avisar de la decisión de manera motivada legal, técnica, financiera y operativamente a los padres, en cualquier tiempo por medio físico o digital sin requerir trámites previos de ninguna naturaleza. 2. [cite_start]EL EDUCANDO demuestre no estar conforme con la filosofía y principios educativos del Colegio 3. Cuando a pesar de los esfuerzos del cuerpo docente, el promedio académico, el rendimiento escolar del EDUCANDO, no mejoren significativamente a través de todas la herramientas académicas y psicosociales que el Colegio le preste durante el año lectivo escolar[cite: 106, 107].<br><br>

                            <span class="font-bold">DIECISÉIS: MORA:</span> EI COLEGIO BILINGÜE CEDAM cuenta con los servicios legales de una firma de abogados reconocida, a quienes se enviarán los contratos que tengan dos o más meses de retraso en el pago de los costos educativos establecidos por cualquier concepto. Los gastos y costos de cobranza incluyen los honorarios del profesional del derecho que en la etapa pre jurídico serán del 15% liquidado sobre la totalidad del capital y de los intereses causados. El presente Contrato de Servicios Educativos presta mérito ejecutivo desde el momento de su firma, al tenor de lo estipulado por el Artículo 422 y siguientes del C.G.P. y el Artículo 14 parágrafo 6 del Decreto 2542 de 199. <span class="font-bold">PARÁGRAFO TERCERO.</span> - Para garantizar el pago de las sumas de dinero pactadas en el presente Contrato de Servicios Educativos, los PADRES DEL FAMILIA Y/O ACUDIENTES se obligan a suscribir título valor Pagaré con su respectiva carta de instrucción por la cantidad determinada en la Cláusula Cuarta de este documento. <span class="font-bold">PARÁGRAFO CUARTO. - [cite_start]CLAUSULA ACELERATORIA:</span> EL COLEGIO podrá declarar insubsistentes los plazos de esta obligación o de las cuotas pendientes de pago, esté o no vencidas y exigir el pago total e inmediato, Judicial o Extrajudicialmente cuando se presente mora en el pago de cualquiera de las cuotas pactadas [cite: 108-113].<br><br>

                            [cite_start]<span class="font-bold">DIECISIETE: RETIRO TEMPORAL DEL SERVICIO:</span> La ausencia del EDUCANDO en forma temporal o total dentro del mes por enfermedad, por intercambios, caso fortuito o fuerza mayor, no dará el derecho a los Padres de Familia y/o Acudientes a descontar suma alguna de lo obligado a pagar o a que El Colegio haga devoluciones o abonos posteriores[cite: 114].<br><br>

                            <span class="font-bold">DIECIOCHO: REINTEGRO DEL VALOR DE LA MATRÍCULA:</span> Cuando se matricula a un estudiante y se decide su retiro, el responsable jurídico debe informar por escrito al COLEGIO BILINGÜE CEDAM antes que se inicien las labores académicas escolares al veintiséis del mes de Enero de 2026 y en este caso el interesado tiene derecho a que se le devuelva el 50% del valor de la matricula, de lo contrario no se hará reintegro. DEVOLUCIÓN DE COSTOS EDUCATIVOS: Si el estudiante es retirado por los padres de familia antes de que inicie el mes escolar, este deberá informar por escrito al área de coordinación y al área financiera y el padre de familia no deberá pagar el mes siguiente del mes en que fue retirado. [cite_start]Pero si avisa dentro del mes en curso deberá pagar la totalidad del valor del mes [cite: 115-118].<br><br>

                            [cite_start]<span class="font-bold">DIECINUEVE: AUTORIZACIÓN DE CONSULTA Y REPORTE:</span> LOS PADRES DE FAMILIA Y/O ACUDIENTES, autorizan al COLEGIO BILINGÜE CEDAM o a quien se determine, a consultar su situación crediticia y financiera, a reportar el estado de sus obligaciones para con el plantel a las Centrales de Riesgo existentes y a aplicar sus pagos en primer lugar a las sanciones establecidas contractualmente y por último a las pensiones (Artículo 1.653 del C.C.) [cite: 119, 124]<br><br>

                            <span class="font-bold">VEINTE: PROTECCIÓN DE DATOS:</span> Los Padres de Familia y/o Acudientes del estudiante, de manera expresa autorizamos al COLEGIO BILINGÜE CEDAM, el tratamiento (recolección, almacenamiento, uso y supresión) de los datos personales indispensables, opcionales y sensibles del estudiante, así como de los Padres y/o Acudientes que se requieran o que estén relacionados con la prestación del servicio educativo contratado. Así mismo, autorizamos la transferencia de datos a las entidades públicas o administrativas en ejercicio de las competencias legales o por orden judicial; autorizamos la transferencia de datos a terceros en los cuales El Colegio haya celebrado contrato de prestación de servicios, tales como los de transporte y alimentación si fuese el caso, o de otras tareas relacionadas o derivadas del servicio educativo. Manifiesto que en calidad de padre y/o acudiente, he sido informado de manera clara y comprensible sobre mis derechos respecto a los datos proporcionados de acuerdo con la Ley 1581 de 2012 y su decreto reglamentario 1377 de 2013 y que puedo conocer y tener acceso a la política de protección y tratamiento de datos personales. Declaro de manera libre, expresa, inequívoca e informada, que AUTORIZO al COLEGIO BILINGÜE CEDAM, para que realice la recolección, almacenamiento, uso, circulación, supresión y en general, tratamiento de mis datos personales con el fin de lograr las finalidades relativas al objeto social del Colegio. [cite_start]Declaro que se me ha informado de manera clara y comprensible que tengo derecho a conocer, actualizar y rectificar los datos personales proporcionados, a solicitar prueba de esta autorización, a solicitar información sobre el uso que se les ha dado a mis datos personales, a presentar quejas por el uso indebido de mis datos personales, a revocar esta autorización o solicitar la supresión de los datos personales suministrados y a acceder de forma gratuita a los mismos [cite: 124-129].<br><br>

                            [cite_start]<span class="font-bold">VEINTIUNO: VALIDEZ Y ACEPTACIÓN DEL CONTRATO:</span> EI PADRE DE FAMILIA, ACUDIENTE RESPONSABLE DE PAGO reconoce que, al aceptar el contrato a través de los medios digitales o virtuales dispuestos por el COLEGIO BILINGÜE CEDAM, como plataformas institucionales, formularios en línea u otros sistemas electrónicos de registro, dicha aceptación constituye un acto válido y vinculante del contrato de prestación de servicios educativos, con plena validez y los mismos efectos legales que una firma presencial, conforme a lo establecido en la Ley 527 de 1999 y demás normas aplicables sobre mensajes de datos y firmas electrónicas[cite: 130, 131].<br><br>

                            <span class="font-bold">VEINTIDÓS: AUTORIZACIÓN NOTIFICACIÓN ELECTRÓNICA:</span> Se autoriza al COLEGIO BILINGÜE CEDAM a remitirnos comunicaciones, facturas y/o citaciones relacionadas con el desempeño académico, de normalización y/o convivencia del EDUCANDO mediante el correo electrónico que se suministra y que es responsabilidad de su titular mantener la dirección actualizada. [cite_start]Asimismo, declaramos que, en caso de modificación de la cuenta de correo, se dará aviso oportuno al Colegio con el fin de actualizar la información[cite: 132, 133].
                        </p>
                    </div>

                    {{-- Fechas --}}
                    <div id="parte-fecha">
                        Para constancia se firma en dos ejemplares del mismo tenor, en Bogotá D.C a los <br>
                        [cite_start]<span class="font-bold underline">{{ $day }}</span> días del mes de <span class="font-bold underline">{{ $month }}</span> de <span class="font-bold underline">{{ $year }}</span> [cite: 134-136]
                    </div>

                                    <div id="firmas" class="texto firmas">
                    <div class="firma_juan">
                        <img src="{{ asset('img/Firma_Digitalizada.jpg') }}" alt="firma_representante" class="firma-representante">
                        <p>JUAN GONZÁLEZ TALERO</p>
                        <p>REPRESENTANTE LEGAL</p>
                        <p>C.C: 79.863.883</p>
                    </div>

                    <div class="firma_padre">
                        <p><span id="firma-padre" class="firma-padre">{{ $fatherName }}</span></p>
                        <p>PADRES Y/O ACUDIENTES DEL(A) ESTUDIANTE(A)</p>
                        <p>Nombre: <span id="nombre-padre" class="nombre-padre">{{ $fatherName }}</span></p>
                        <p>C.C: <span id="cc-padre" class="cc-padre">{{ $fatherCc }}</span></p>
                    </div>

                    <div class="firma_madre">
                        <p><span id="firma-madre" class="firma-padre">{{ $motherName }}</span></p>
                        <p>PADRES Y/O ACUDIENTES DEL(A) ESTUDIANTE(A)</p>
                        <p>Nombre: <span id="nombre-madre" class="nombre-madre">{{ $motherName }}</span></p>
                        <p>C.C: <span id="cc-madre" class="cc-madre">{{ $motherCc }}</span></p>
                    </div>

                </div>

            </section>
        </div>
    </article>

    <script>
              function generatePDF() {
        const element = document.getElementById('tres');
        const width = window.innerWidth;
        const format = width > 768 ? 'a2' : 'a2';
        
        const opt = {
            margin:       0, 
            filename:     'Boletin_{{ $studentName }}.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true }, 
            jsPDF:        { unit: 'in', format: format, orientation: 'portrait' } 
        };

  
        html2pdf()
            .set(opt)
            .from(element)
            .save();
    }
    </script>
</div>