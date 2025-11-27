
<div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow-xl">

   <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Formulario de Registro</h1>

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
            <p class="font-semibold text-blue-800 mb-2">Selecciona el acudiente que recibirá la facturación electrónica</p>
            <select wire:model.live="acudienteFacturacion" class="block w-full p-2 border border-blue-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
               <option value="" disabled>Seleccione un acudiente</option>
               @foreach ($padres as $padre)
                  <option value="{{ $padre->parent_id }}">{{ $padre->parentesco }}</option>
               @endforeach
            </select>
         </div>
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

            {{--AUTORIZACION DE FACTURA ELECTRONICA--}}
              <div class="flex items-center justify-between p-4 border rounded-lg transition-colors duration-300 {{ $checks['autorizacion-factura-electronica'] ? 'bg-green-50 border-green-500' : 'bg-white border-gray-200' }}"
              @if($this->acudienteFacturacion == "") disabled @endif>
               <div class="flex items-center gap-3">
                  <input type="checkbox" wire:model.live="checks.autorizacion-factura-electronica" id="check_autorizacion-factura-electronica" 
                     class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 cursor-pointer">
                  <label for="check_autorizacion-factura-electronica" class="font-semibold text-gray-700 cursor-pointer select-none">
                     Acepto la Autorizacion de Factura Electronica
                  </label>
               </div>
               <a href="{{ route('documentos.ver', ['tipo' => 'autorizacion', 'estudianteId' => $estudianteId]) }}" 
                  target="_blank" 
                  class="text-indigo-600 hover:text-indigo-800 font-medium underline flex items-center gap-1">
                  <i class="fa-solid fa-file-pdf"></i> Leer
               </a>
            </div>

         </div>
      </div>

      <div class="fixed bottom-0 left-0 right-0 p-4  flex justify-between items-center z-10  ">
         <a 
            wire:click="anterior" 
            class="p-4 px-8 bg-indigo-600 text-white text-lg font-bold rounded-full shadow-xl hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 hover:cursor-pointer select-none"
            >Anterior</a>

         <button type="button" 
               wire:click="siguiente" 
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
         
         Swal.fire({
            icon: data[0].type,
            title: data[0].type,
            text: data[0].message,
         });
      });
   </script>
</div>