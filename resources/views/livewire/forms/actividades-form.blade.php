<div class="flex flex-col items-center justify-center min-h-[100%] bg-gray-100 p-4">
    <div class="flex justify-start align-end items-end w-full mt-4 max-w-md">
        <a wire:click="previousPage" class="flex justify-start items-end mb-4 underline text-black hover:cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                viewBox="0 0 24 24"><!-- Icon from Material Design Icons by Pictogrammers - https://github.com/Templarian/MaterialDesign/blob/master/LICENSE -->
                <path fill="currentColor"
                    d="M20 13.5a6.5 6.5 0 0 1-6.5 6.5H6v-2h7.5c2.5 0 4.5-2 4.5-4.5S16 9 13.5 9H7.83l3.08 3.09L9.5 13.5L4 8l5.5-5.5l1.42 1.41L7.83 7h5.67a6.5 6.5 0 0 1 6.5 6.5" />
            </svg>
            <span class="ml-2">Volver</span>
        </a>
    </div>
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-md">
        {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
        @csrf
                @if (session()->has('message'))
                    <div class="p-4 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
                        {{ session('message') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="p-4 mb-4 text-red-700 bg-red-100 border border-red-400 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                
        <form wire:submit.prevent="submit" class="space-y-4">

            @if($competencias && $competencias->count() > 0)
                <div class="flex flex-col">
                    <select wire:model="competencia" class="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccione una competencia</option>
                        @foreach ($competencias as $competencia)
                            <option value="{{ $competencia->id }}">
                                {{ $competencia->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex flex-col">
                <label for="nombre" class="text-gray-700 font-medium mb-1">Nombre:</label>
                <input type="text" wire:model="nombre" class="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex flex-col">
                <label for="descripcion" class="text-gray-700 font-medium mb-1">Descripción:</label>
                <textarea wire:model="descripcion" class="border rounded-md px-3 py-2 h-32 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex flex-col">
                <label for="tipo" class="text-gray-700 font-medium mb-1">Tipo de nota</label>
                <select wire:model="tipoSelected" class="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Seleccione un tipo</option>
                    @foreach ($tipo as $tipo)
                        <option value="{{ $tipo->id }}" @selected($tipoSelected && $tipo->id == $tipoSelected)>
                            {{ $tipo->tipo }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if(!$actividadId)
                <div>
                    <input type="checkbox"  wire:model.live="actividadesForAllCheck"  class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-offset-0">
                    <label for="actividadesForAll" class="text-gray-700 font-medium mb-1">Aplicar a todas las materias con la misma competencia</label>
                </div>
            @endif
            
            @if($actividadesForAllCheck)
            <div>
                    @foreach ($materias as $materia)
                        <div>
                            <input type="checkbox" wire:model="materiasSelected" checked value="{{ $materia['id_materia'] }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-offset-0">
                            <label for="materiasSelected" class="text-gray-700 font-medium mb-1">{{ $materia['nombre_materia'] }} {{ $materia['grado'] }} {{ $materia['grupo'] }}</label>
                        </div>
                    @endforeach
            </div>
            @endif
            <div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-200">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
