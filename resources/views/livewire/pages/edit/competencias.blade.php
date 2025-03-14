<div class="p-6 max-w-3xl mx-auto bg-white rounded-lg shadow-md">
    {{-- Mensajes de éxito y error --}}
    @if ($successMessage)
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ $successMessage }}
        </div>
    @endif

    <!-- Mensaje de Error -->
    @if ($errorMessage)
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ $errorMessage }}
        </div>
    @endif

    {{-- Formulario --}}
    <form wire:submit.prevent="save" class="space-y-6">
        @csrf

        {{-- Campo Nombre --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" id="name" wire:model="competenceName"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            @error('competenceName')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Campo Descripción --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
            <input type="text" id="description" wire:model="competenceDescription"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            @error('competenceDescription')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Campo Período --}}
        <div>
            <label for="periodo" class="block text-sm font-medium text-gray-700">Período</label>
            <select id="periodo" wire:model="periodoSelected"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @foreach ($periodos as $periodo)
                    <option value="{{ $periodo->id }}" {{ $periodo->id == $periodoSelected ? 'selected' : '' }}>
                        {{ $periodo->periodo }}
                    </option>
                @endforeach
            </select>
            @error('periodoSelected')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Buscador de Materias --}}
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700">Buscar materias:</label>
            <input type="text" id="search" wire:model.live.debounce.500ms="search"
                placeholder="Escribe para buscar..."
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        </div>

        {{-- Lista de Materias --}}
        <div>
            <label for="subjectList" class="block text-sm font-medium text-gray-700 mb-2">Materias</label>
            <div class="border rounded-lg overflow-hidden">
                @if (empty($subjects))
                    <div class="px-4 py-3 text-gray-500">No se encontraron materias.</div>
                @else
                    @foreach ($subjects as $subject)
                        <div class="flex items-center px-4 py-3 border-b last:border-b-0 hover:bg-gray-50">
                            <input type="checkbox" id="subject_{{ $subject->id }}" wire:model="subjectsAdded"
                                value="{{ $subject->id }}"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="subject_{{ $subject->id }}"
                                class="ml-3 block text-sm text-gray-700">{{ $subject->nombre }}</label>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Botón Guardar --}}
        <div>
            <button type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Guardar
            </button>
        </div>
    </form>
</div>
