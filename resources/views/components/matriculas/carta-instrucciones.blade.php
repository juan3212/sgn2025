@props([
    'fatherName' => '',
    'fatherCc' => '',
    'fatherAddress' => '',
    'fatherPhone' => '',
    'fatherEmail' => '',
    'motherName' => '',
    'motherCc' => '',
    'motherAddress' => '',
    'motherPhone' => '',
    'motherEmail' => '',
    'promissoryNoteNumber' => '_____________', // Espacio para el número de pagaré
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
            src: url('/fonts/CedarvilleCursive-Regular.ttf');
            font-weight: 400;
        }

        @font-face {
            font-family: 'Charlotte';
            src: url('/fonts/charlotte.ttf');
            font-weight: 400;
        }

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

    <div id="cuatro" class="bg-white w-full min-h-screen pb-10 pt-4">

        {{-- Encabezado Imagen --}}
        <img src="{{ asset('img/encabezado.png') }}" alt="Encabezado" class="relative w-[70%] h-[120px] block mx-auto object-contain my-4">

        {{-- Título Principal --}}
        <h1 class="text-black font-black text-2xl text-center p-2.5 rounded-lg mb-6 uppercase font-[Arial]">
            CARTA DE INSTRUCCIONES
        </h1>

        {{-- Contenedor del Texto (80% ancho centrado) --}}
        <div class="w-[80%] mx-auto text-black font-sans">
            
            {{-- Sección Señores --}}
            <div class="mb-4 text-justify">
                <p class="mb-2">Señores:</p>
                <h2 class="font-black text-lg font-[Arial] uppercase">
                    JUAN GONZÁLEZ TALERO Y/O COLEGIO BILINGÜE CEDAM.
                </h2>
            </div>

            {{-- Párrafo Nosotros --}}
            <p class="mb-6 text-justify leading-relaxed">
                Nosotros: <span class="font-bold underline">{{ $fatherName }}</span> y <span class="font-bold underline">{{ $motherName }}</span> <br>
                Identificados como aparece al pie de nuestras firmas, y obrando en nuestro propio nombre, por medio del presente documento, y haciendo uso de las facultades conferidas por el Artículo 622 del Código de Comercio, autorizamos a JUAN GONZÁLEZ TALERO Y/O COLEGIO BILINGÜE CEDAM para que llene los espacios que se han dejado en blanco en el pagaré N° <span class="font-bold underline">{{ $promissoryNoteNumber }}</span> Adjunto. Para la cual deben ceñirse a las siguientes instrucciones:
            </p>

            {{-- Instrucciones Numeradas --}}
            <div class="space-y-4 mb-6">
                <p class="text-justify leading-relaxed">
                    1. - El monto será igual al valor de todas las obligaciones exigibles que en nuestro cargo y a favor de JUAN GONZALEZ TALERO Y/O COLEGIO BILINGÜE CEDAM existan el momento de ser llenados los espacios en blanco.
                </p>

                <p class="text-justify leading-relaxed">
                    2. - Los espacios en blanco se llenarán cuando exista de nuestra parte incumplimiento de las obligaciones contenidas en el contrato de prestación de servicios educativos suscritos con JUAN GONZÁLEZ TALERO Y/O COLEGIO BILINGÜE CEDAM, o cualquier otra obligación contraída por nosotros con JUAN GONZÁLEZ TALERO Y/O COLEGIO BILINGÜE CEDAM.
                </p>

                <p class="text-justify leading-relaxed">
                    3. - La fecha del pagaré será aquella que se llene en los espacios dejados en blanco. <br>
                    Para constancia se firma en Bogotá, D.C. hoy <span class="font-bold underline">{{ $day }}</span> de <span class="font-bold underline uppercase">{{ $month }}</span> de <span class="font-bold underline">{{ $year }}</span>
                </p>
            </div>

            {{-- Título Deudores --}}
            <h2 class="font-black text-lg font-[Arial] uppercase mb-4">
                DEUDORES.
            </h2>

            {{-- Sección Firmas --}}
            <div class="grid grid-cols-2 md:grid-cols-2 gap-8 mt-4">
                
                {{-- Bloque Padre --}}
                <div class="border-t-2 border-transparent md:border-none pt-4 md:pt-0">
                    <div class="mb-2">
                         {{-- Espacio para firma visual simulada con la fuente Charlotte --}}
                        <span class="font-charlotte text-md font-bold">{{ $fatherName }}</span>
                    </div>
                    <p class="text-justify leading-relaxed">
                        Nombre y firma del Padre de Familia: <span class="font-bold">{{ $fatherName }}</span> <br>
                        C.C.No. <span class="font-bold underline">{{ $fatherCc }}</span> <br> 
                        Dirección: <span class="font-bold underline">{{ $fatherAddress }}</span> <br>
                        Teléfono: <span class="font-bold underline">{{ $fatherPhone }}</span> <br> 
                        Correo: <span class="font-bold underline">{{ $fatherEmail }}</span>
                    </p>
                </div>

                {{-- Bloque Madre --}}
                <div class="border-t-2 border-transparent md:border-none pt-4 md:pt-0">
                    <div class="mb-2">
                        <span class="font-charlotte text-md font-bold">{{ $motherName }}</span>
                    </div>
                    <p class="text-justify leading-relaxed">
                        Nombre y firma de la Madre de Familia: <span class="font-bold">{{ $motherName }}</span> <br>
                        C.C.No. <span class="font-bold underline">{{ $motherCc }}</span> <br> 
                        Dirección: <span class="font-bold underline">{{ $motherAddress }}</span> <br>
                        Teléfono: <span class="font-bold underline">{{ $motherPhone }}</span> <br>
                        Correo: <span class="font-bold underline">{{ $motherEmail }}</span>
                    </p>
                </div>

            </div>

        </div>
    </div>
    <script>
        function generatePDF() {
            const element = document.getElementById('cuatro');
            const width = window.innerWidth;
            const format = width > 768 ? 'a4' : 'a4';
            
            const opt = {
                margin:       0.1, 
                filename:     'Carta_instrucciones.pdf',
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