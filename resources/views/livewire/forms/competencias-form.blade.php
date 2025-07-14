<div class="flex items-center justify-center min-h-screen bg-gray-100">
    {{-- Success is as dangerous as failure. --}}
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded shadow-md">
        <form action="" wire:submit.prevent="submit" class="space-y-4">
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
            <div class="space-y-2">
                <label for="competenceName" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" 
                    id="competenceName" 
                    name="competenceName" 
                    wire:model="competenceName" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"> 
            </div>
            <div class="space-y-2">
                <label for="competenceDescription" class="block text-sm font-medium text-gray-700">Descripcion</label>
                <input type="text" 
                    id="competenceDescription" 
                    name="competenceDescription" 
                    wire:model="competenceDescription" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="periodo">Periodo</label>
                <select name="periodo" id="periodo" wire:model="periodoSelected" required class="w-full px-3 py-2 mt-1 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    <option value="" selected>Seleccione</option>
                    @foreach ($periodos as $periodo)
                        <option value="{{ $periodo->id }}">{{ $periodo->periodo }}</option>
                    @endforeach
                </select>
            </div>
            
            @if (!$isTeacher) 
                @livewire('components.select-users',
                [
                    'role'=>'profesor', 
                    'title'=>'Profesor',
                    'usuarioSelected'=>$teacherSelected,
                    'usuario_id'=>$teacher_id,
                    'wire:model'=>'teacher_id'],
                )
            @endif


            <div class="space-y-2">
                <label for="porcentaje" class="block text-sm font-medium text-gray-700">Porcentaje</label>
                <input type="number" 
                    id="porcentaje" 
                    name="porcentaje" 
                    wire:model="porcentaje" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"> 
            </div>
            <div class="space-y-4">
            <div class="space-y-4">
    <!-- Campo de búsqueda -->
    <div class="mb-4">
        <label for="search" class="block text-sm font-medium text-gray-700">Buscar materias:</label>
        <input 
            type="text" 
            id="search" 
            wire:model.live.debounce.500ms="search" 
            placeholder="Escribe para buscar..." 
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        >
    </div>

    <!-- Lista de materias -->
    <label for="subjectList" class="block text-sm font-medium text-gray-700 mb-2">Materias</label>
    <div class="border rounded-lg overflow-hidden">
        @if (empty($subjects))
            <div class="px-4 py-3 text-gray-500">No se encontraron materias.</div>
        @else
            @foreach ($subjects as $subject)
                <div class="flex items-center px-4 py-3 border-b last:border-b-0 hover:bg-gray-50">
                    <input type="checkbox" 
                        id="subject_{{ $subject->id }}" 
                        wire:model="selectedSubjects" 
                        value="{{ $subject->id }}"
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="subject_{{ $subject->id }}" 
                        class="ml-3 block text-sm text-gray-700">
                        {{ $subject->nombre }}
                    </label>
                </div>
            @endforeach
        @endif
    </div>
</div>
            <button type="submit" 
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                Guardar
            </button>
        </form>
    </div>
</div>
