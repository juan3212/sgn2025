<div class="max-w-2xl mx-auto p-4 space-y-4">
 
    @foreach($servicios as $servicio)
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            
            {{-- CABECERA --}}
            {{-- Usamos wire:click para llamar a la función del backend --}}
            <button 
                wire:click="toggleServicio({{ $servicio->id }})"
                class="w-full flex justify-between items-center p-4 bg-gray-50 hover:bg-gray-100 transition-colors duration-200 focus:outline-none
                @if(in_array($servicio->id, $serviciosSeleccionados)) focus:ring-blue-500 border-blue-500 border-2 @endif"
            >
                <span class="font-semibold text-gray-800 text-lg">{{ $servicio->nombre }}</span>
                
                {{-- Icono: rotamos si el ID coincide con la variable del backend --}}
                <svg 
                    class="w-5 h-5 text-gray-500 transform transition-transform duration-200 {{ $servicioExpandidoId === $servicio->id ? 'rotate-180' : '' }}" 
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            {{-- CUERPO --}}
            {{-- Solo renderizamos el contenido si el ID coincide --}}
            @if($servicioExpandidoId === $servicio->id)
                <div class="p-0 animate-fade-in-down">  {{-- Clase opcional para animación --}}
                    
                    {{-- Imagen --}}
                    <div class="w-full h-48 bg-gray-200 overflow-hidden">
                        <img src="{{ asset('storage/'.$servicio->imagen) }}" 
                             alt="{{ $servicio->nombre }}" 
                             class="w-full h-full object-cover">
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <h5 class="text-xl font-bold text-gray-800 mb-2">{{ $servicio->nombre }}</h5>
                            <p class="text-gray-600">{{ $servicio->descripcion }}</p>
                        </div>

                        @if($servicio->horario)
                            <div class="text-gray-700">
                                <span class="font-semibold">Horario:</span> {{ $servicio->horario }}
                            </div>
                        @endif

                        @if($servicio->notas && count($servicio->notas) > 0)
                            <div class="bg-yellow-50 p-4 rounded border-l-4 border-yellow-400 text-sm text-gray-700">
                                <span class="font-bold block mb-1">Notas importantes:</span>
                                
                                {{-- Lista con viñetas --}}
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($servicio->notas as $nota)
                                        <li>{{ $nota }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="text-lg text-gray-800">
                            <strong>Precio mensualidad:</strong> 
                            @if(is_numeric($servicio->precio))
                                $ {{ number_format((float)$servicio->precio, 0, ',', '.') }}
                            @else
                                {{ $servicio->precio }}
                            @endif
                        </div>

                        <div class="flex items-center pt-2">
                            <input 
                                wire:model.live="serviciosSeleccionados" 
                                type="checkbox" 
                                value="{{ $servicio->id }}" 
                                id="servicio_{{ $servicio->id }}"
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            >
                            <label class="ml-2 block text-gray-900 font-medium cursor-pointer" for="servicio_{{ $servicio->id }}">
                                Inscribirse
                            </label>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endforeach

    <div class="mt-6 text-right">
        <button wire:click="inscribir" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition">
            Guardar Inscripción
        </button>
    </div>

</div>