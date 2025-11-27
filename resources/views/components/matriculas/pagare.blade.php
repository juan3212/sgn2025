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
    'promissoryNoteNumber' => '_________________',
    'valueText' => '___________________',
    'valueNumber' => '______________',
    'dueDate' => '_______________________',
    'paymentAddress' => '________________________________',
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

        {{-- Contenedor Principal (80% ancho centrado) --}}
        <div class="w-[80%] mx-auto text-black font-sans space-y-4">
            
            {{-- Datos del Pagaré (Cabecera) --}}
            <div class="space-y-2 mb-6 font-medium">
                <p class="block"><span class="font-bold">PAGARÉ NÚMERO:</span> <span class="uppercase">{{ $promissoryNoteNumber }}</span></p>
                <p class="block">VALOR: <span class="uppercase">{{ $valueText }}</span></p>
                <p class="block">VENCIMIENTO: <span class="uppercase">{{ $dueDate }}</span></p>
                <p class="block">DIRECCIÓN PARA EL PAGO: <span class="uppercase">{{ $paymentAddress }}</span></p>
                <p class="block">A LA ORDEN DE: JUAN GONZÁLEZ TALERO Y/O COLEGIO BILINGÜE CEDAM</p>
            </div>

            {{-- Cuerpo del Texto Legal --}}
            <div class="text-justify leading-relaxed mb-6">
                <p>
                    Nosotros; <span class="font-bold underline uppercase">{{ $fatherName }}</span> y <span class="font-bold underline uppercase">{{ $motherName }}</span> Mayores de edad identificados como aparece al pie de nuestras firmas, domiciliados en Bogotá, D.C. obrando en nuestro propio nombre, manifestamos: 
                    
                    <span class="font-bold">PRIMERO-OBJETO.</span> Que nos comprometemos a pagar incondicionalmente a la orden de JUAN GONZÁLEZ TALERO Y/O COLEGIO BILINGÜE CEDAM en la ciudad y dirección indicadas, la suma de <span class="uppercase">{{ $valueText }}</span> ($<span class="uppercase">{{ $valueNumber }}</span>) M/cte. el día <span class="underline">____</span> de <span class="underline">________</span> 
                    
                    <span class="font-bold">SEGUNDO. - INTERESES.-</span> Que, en caso de mora en el pago, reconoceremos intereses liquidados a la tasa máxima legal vigente al momento del pago. 
                    
                    <span class="font-bold">TERCERO. - CESIÓN.-</span> Que aceptamos desde ahora la cesión que de este pagaré haga COLEGIO BILINGÜE CEDAM a cualquier persona natural o jurídica. 
                    
                    <span class="font-bold">CUARTO.-</span> Que los gastos originados por concepto de impuestos de timbre serán de mi cargo, al igual que los gastos por el cobro judicial o extrajudicial si a ello hubiere lugar.
                </p>
            </div>

            {{-- Fecha de Firma --}}
            <div class="mb-8">
                <p>Para constancia se firma en la ciudad de Bogotá, hoy <span class="font-bold underline">{{ $day }}</span> de <span class="font-bold underline uppercase">{{ $month }}</span> de <span class="font-bold underline">{{ $year }}</span></p>
            </div>

            {{-- Título Deudores --}}
            <h2 class="font-black text-lg font-[Arial] uppercase mb-4">
                DEUDORES.
            </h2>

            {{-- Sección Firmas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-4">
                
                {{-- Bloque Padre --}}
                <div class="border-t-2 border-transparent md:border-none pt-4 md:pt-0">
                    <div class="mb-2">
                         {{-- Firma visual simulada --}}
                        <span class="font-charlotte text-xl font-bold block h-8">{{ $fatherName }}</span>
                    </div>
                    <p class="text-justify leading-relaxed">
                        Nombre y firma del Padre de Familia: <span class="font-bold uppercase">{{ $fatherName }}</span> <br>
                        C.C.No. <span class="font-bold underline">{{ $fatherCc }}</span> <br> 
                        Dirección: <span class="font-bold underline">{{ $fatherAddress }}</span> <br>
                        Teléfono: <span class="font-bold underline">{{ $fatherPhone }}</span> <br> 
                        Correo: <span class="font-bold underline">{{ $fatherEmail }}</span>
                    </p>
                </div>

                {{-- Bloque Madre --}}
                <div class="border-t-2 border-transparent md:border-none pt-4 md:pt-0">
                    <div class="mb-2">
                        <span class="font-charlotte text-xl font-bold block h-8">{{ $motherName }}</span>
                    </div>
                    <p class="text-justify leading-relaxed">
                        Nombre y firma de la Madre de Familia: <span class="font-bold uppercase">{{ $motherName }}</span> <br>
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
        const format = width > 768 ? 'a3' : 'a3';
        
        const opt = {
            margin:       0, 
            filename:     'pagare.pdf',
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