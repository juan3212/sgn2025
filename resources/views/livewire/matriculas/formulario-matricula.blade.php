<div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow-xl">

   <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Formulario de Registro</h1>

   @php
      $pasos = [
         'estudiante' => '1. Estudiante',
         'acudiente' => '2. Acudientes',
         'servicios' => '3. Servicios',
         'contratos' => '4. Contratos',
      ];
      $indiceActivo = array_search($seccionActiva, $secciones, true);
   @endphp

   <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-6">
      @foreach($pasos as $clave => $etiqueta)
         @php $indicePaso = array_search($clave, $secciones, true); @endphp
         <div class="text-center py-2 px-3 rounded-lg text-sm font-semibold border
            {{ $seccionActiva === $clave
               ? 'bg-indigo-600 text-white border-indigo-600'
               : ($indicePaso < $indiceActivo
                  ? 'bg-green-50 text-green-700 border-green-300'
                  : 'bg-gray-50 text-gray-500 border-gray-200') }}">
            {{ $etiqueta }}
         </div>
      @endforeach
   </div>

   @if(!empty($erroresSeccion))
      <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
         <p class="font-semibold text-red-800 mb-2">No es posible avanzar. Verifique los siguientes requisitos:</p>
         <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach($erroresSeccion as $error)
               <li>{{ $error }}</li>
            @endforeach
         </ul>
      </div>
   @endif

   <form wire:submit.prevent=""
   enctype="multipart/form-data"
   class="space-y-8">
      <div @if($seccionActiva != "estudiante") hidden @endif wire:key="estudiante"
      class="transition-all duration-500 ease-in-out">
         <livewire:matriculas.formulario-estudiante wire:key="estudiante" :$estudianteId />
         <hr class="border-t-2 border-dashed border-gray-400 my-8">

      </div>

      <div @if($seccionActiva != "acudiente") hidden @endif wire:key="acudiente"
      class="transition-all duration-500 ease-in-out">
         <h2 class="text-2xl font-bold text-gray-800 pt-4">Información de Acudiente</h2>
         <livewire:matriculas.formulario-acudiente wire:key="acudiente" :$estudianteId />
      </div>

      <div @if($seccionActiva != "servicios") hidden @endif wire:key="servicios"
      class="transition-all duration-500 ease-in-out">
         <livewire:matriculas.components.lista-servicios wire:key="servicios" :usuarioId="$estudianteId" />
      </div>

      <div @if($seccionActiva != "contratos") hidden @endif wire:key="contratos" class="transition-all duration-500 ease-in-out">

         <h2 class="text-2xl font-bold text-gray-800 mb-4">Legalización de Documentos</h2>
         <p class="text-gray-600 mb-6">Para finalizar la matrícula, debes leer y aceptar los siguientes documentos legales.</p>

         <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg shadow-md mb-6">
            <p class="font-semibold text-blue-800 mb-2">Selecciona el acudiente que recibirá la facturación electrónica <span class="text-red-500">*</span></p>
            <select wire:model.live="acudienteFacturacion" class="block w-full p-2 border border-blue-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
               <option value="" disabled>Seleccione un acudiente</option>
               @foreach ($padres as $padre)
                  <option value="{{ $padre['parent_id'] }}">
                     {{ str_replace('_', ' ', $padre['parentesco']) }} - {{ $padre['nombre'] }} {{ $padre['apellido'] }}
                  </option>
               @endforeach
            </select>
         </div>
         @if($acudienteFacturacion)
         <div class="flex flex-col gap-4">

            {{-- Item 1: Contrato Educativo --}}
            <div class="flex items-center justify-between p-4 border rounded-lg transition-colors duration-300 {{ $checks['contrato'] ? 'bg-green-50 border-green-500' : 'bg-white border-gray-200' }}">
               <div class="flex items-center gap-3">
                  <input type="checkbox" wire:model.live="checks.contrato" id="check_contrato"
                     class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 cursor-pointer">
                  <label for="check_contrato" class="font-semibold text-gray-700 cursor-pointer select-none">
                     Acepto el Contrato de Servicios Educativos
                  </label>
               </div>
               <a href="{{ route('documentos.ver', ['tipo' => 'contrato', 'estudianteId' => $estudianteId]) }}"
                  target="_blank"
                  class="text-indigo-600 hover:text-indigo-800 font-medium underline flex items-center gap-1">
                  <i class="fa-solid fa-file-pdf"></i> Leer
               </a>
            </div>

            {{-- Item 2: Carta de Instrucciones --}}
            <div class="flex items-center justify-between p-4 border rounded-lg transition-colors duration-300 {{ $checks['carta'] ? 'bg-green-50 border-green-500' : 'bg-white border-gray-200' }}">
               <div class="flex items-center gap-3">
                  <input type="checkbox" wire:model.live="checks.carta" id="check_carta"
                     class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 cursor-pointer">
                  <label for="check_carta" class="font-semibold text-gray-700 cursor-pointer select-none">
                     Acepto la Carta de Instrucciones
                  </label>
               </div>
               <a href="{{ route('documentos.ver', ['tipo' => 'carta', 'estudianteId' => $estudianteId]) }}"
                  target="_blank"
                  class="text-indigo-600 hover:text-indigo-800 font-medium underline flex items-center gap-1">
                  <i class="fa-solid fa-file-pdf"></i> Leer
               </a>
            </div>

            {{-- Item 3: Pagaré --}}
            <div class="flex items-center justify-between p-4 border rounded-lg transition-colors duration-300 {{ $checks['pagare'] ? 'bg-green-50 border-green-500' : 'bg-white border-gray-200' }}">
               <div class="flex items-center gap-3">
                  <input type="checkbox" wire:model.live="checks.pagare" id="check_pagare"
                     class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 cursor-pointer">
                  <label for="check_pagare" class="font-semibold text-gray-700 cursor-pointer select-none">
                     Acepto el Pagaré
                  </label>
               </div>
               <a href="{{ route('documentos.ver', ['tipo' => 'pagare', 'estudianteId' => $estudianteId]) }}"
                  target="_blank"
                  class="text-indigo-600 hover:text-indigo-800 font-medium underline flex items-center gap-1">
                  <i class="fa-solid fa-file-pdf"></i> Leer
               </a>
            </div>

            {{-- ACTA DE RESPONSABILIDAD CIVIL CONTRACTUAL --}}
            <div class="flex items-center justify-between p-4 border rounded-lg transition-colors duration-300 {{ $checks['acta'] ? 'bg-green-50 border-green-500' : 'bg-white border-gray-200' }}">
               <div class="flex items-center gap-3">
                  <input type="checkbox" wire:model.live="checks.acta" id="check_acta"
                     class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 cursor-pointer">
                  <label for="check_acta" class="font-semibold text-gray-700 cursor-pointer select-none">
                     Acepto la Acta de Responsabilidad Civil Contractual
                  </label>
               </div>
               <a href="{{ asset('storage/documentos/matriculas/ACTA_DE_RESPONSABILIDAD_CIVIL_CONTRACTUAL.pdf') }}"
                  target="_blank"
                  class="text-indigo-600 hover:text-indigo-800 font-medium underline flex items-center gap-1">
                  <i class="fa-solid fa-file-pdf"></i> Leer
               </a>
            </div>

            {{-- AUTORIZACION DE FACTURA ELECTRONICA --}}
            <div class="flex items-center justify-between p-4 border rounded-lg transition-colors duration-300 {{ $checks['autorizacion-factura-electronica'] ? 'bg-green-50 border-green-500' : 'bg-white border-gray-200' }}">
               <div class="flex items-center gap-3">
                  <input type="checkbox" wire:model.live="checks.autorizacion-factura-electronica" id="check_autorizacion-factura-electronica"
                     class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 cursor-pointer">
                  <label for="check_autorizacion-factura-electronica" class="font-semibold text-gray-700 cursor-pointer select-none">
                     Acepto la Autorización de Factura Electrónica
                  </label>
               </div>
               <a href="{{ route('documentos.ver', ['tipo' => 'autorizacion', 'estudianteId' => $estudianteId]) }}"
                  target="_blank"
                  class="text-indigo-600 hover:text-indigo-800 font-medium underline flex items-center gap-1">
                  <i class="fa-solid fa-file-pdf"></i> Leer
               </a>
            </div>

         </div>
         @endif
      </div>

      <div class="sticky bottom-0 left-0 right-0 p-4 mt-4 flex justify-between items-center bg-white/90 backdrop-blur-sm border-t border-gray-100">
         <button
            type="button"
            wire:click="anterior"
            @if($seccionActiva === 'estudiante') disabled @endif
            class="p-4 px-8 text-lg font-bold rounded-full shadow-xl focus:outline-none focus:ring-4 transition-all duration-300 select-none
            {{ $seccionActiva === 'estudiante'
               ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
               : 'bg-indigo-600 text-white hover:bg-indigo-700 cursor-pointer focus:ring-indigo-300' }}">
            Anterior
         </button>

         <button type="button"
               wire:click="siguiente"
               wire:loading.attr="disabled"
               @if($seccionActiva == 'contratos' && !$this->contratosListos) disabled @endif
               class="p-4 px-8 text-lg font-bold rounded-full shadow-xl focus:outline-none focus:ring-4 transition-all duration-300 select-none
               {{ ($seccionActiva == 'contratos' && !$this->contratosListos)
                  ? 'bg-gray-400 text-gray-200 cursor-not-allowed'
                  : 'bg-indigo-600 text-white hover:bg-indigo-700 cursor-pointer focus:ring-indigo-300'
               }}">
               @if($seccionActiva == 'contratos') Finalizar @else Siguiente @endif
         </button>
      </div>
   </form>

   <script type="module">
      Livewire.on("scrollToTop", () => {
         window.scrollTo(0, 0);
      });
      Livewire.on("showAlert", function(data) {
         const payload = Array.isArray(data) ? data[0] : data;
         const titulos = {
            error: "Atención",
            success: "Éxito",
            info: "Información",
            warning: "Advertencia",
         };

         Swal.fire({
            icon: payload.type,
            title: titulos[payload.type] || payload.type,
            text: payload.message,
         });
      });
   </script>
</div>
