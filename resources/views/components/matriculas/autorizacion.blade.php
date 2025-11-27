@props([
    'parentName' => '',
    'parentId' => '',
    'parentIdCity' => 'Bogotá',
    'studentName' => '',
    'studentGrade' => '',
    'billedName' => '', // Nombre de la persona a quien se facturará
    'billedId' => '',
    'billedEmail' => '',
    'billedAddress' => '',
    'billedPhone' => '',
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
        <h1 class="text-black font-black text-xl md:text-2xl text-center p-2.5 rounded-lg mb-8 uppercase font-[Arial] w-[80%] mx-auto leading-tight">
            FORMATO AUTORIZACIÓN ELABORACIÓN FACTURA ELECTRÓNICA SERVICIOS EDUCATIVOS
        </h1>

        {{-- Contenedor del Texto (80% ancho centrado) --}}
        <div class="w-[80%] mx-auto text-black font-sans text-justify leading-loose text-base md:text-lg space-y-6">
            
            <p>
                Yo, <span class="font-bold underline uppercase">{{ $parentName }}</span>, 
                identificado(a) con número de cédula N° <span class="font-bold underline">{{ $parentId }}</span> 
                de <span class="font-bold underline">{{ $parentIdCity }}</span>, 
                como responsable de mi hijo <span class="font-bold underline uppercase">{{ $studentName }}</span> 
                quien cursa el grado <span class="font-bold underline uppercase">{{ $studentGrade }}</span>.
            </p>

            <p>
                Autorizo a que la factura por los servicios educativos se haga a nombre de 
                <span class="font-bold underline uppercase">{{ $billedName }}</span> 
                quien se identifica con número de cédula N° <span class="font-bold underline">{{ $billedId }}</span>, 
                y que se envíe al correo electrónico <span class="font-bold underline">{{ $billedEmail }}</span>, 
                dirección residencial <span class="font-bold underline uppercase">{{ $billedAddress }}</span> 
                y teléfono <span class="font-bold underline">{{ $billedPhone }}</span>.
            </p>

            <p class="mt-8">
                Cordialmente,
            </p>

            {{-- Sección Firma --}}
            <div class="mt-12 w-full md:w-1/2">
                <div class="mb-2 border-b border-black w-full pb-2">
                     {{-- Firma visual simulada --}}
                    <span class="font-charlotte text-md font-bold block h-10">{{ $parentName }}</span>
                </div>
                <div class="text-left leading-relaxed text-sm md:text-base">
                    <p class="font-bold">Firma del Responsable</p>
                    <p>Nombre: <span class="font-normal uppercase">{{ $parentName }}</span></p>
                    <p>C.C.: <span class="font-normal">{{ $parentId }}</span></p>
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
                filename:     'Autorizacion.pdf',
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