<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded shadow-md">
        {{-- Do your work, then step back. --}}
        <form wire:submit.prevent="submit" class="space-y-4">
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
            <div>
                <label for="subjectSelected" class="block text-sm font-medium text-gray-700">Nombre</label>
                <select name="subjectSelected" id="subjectSelected" wire:model="subjectSelected" required class="w-full px-3 py-2 mt-1 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    <option value="">Seleccione una materia</option>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}" @selected($subjectSelected && $subject->id == $subjectSelected)>
                        {{ $subject->nombre_materia }}
                    </option>
                @endforeach
                </select>
            </div>
            <div>
                <label for="ih" class="block text-sm font-medium text-gray-700">Intensidad Horaria</label>
                <input type="text" id="ih" wire:model="ih" required class="w-full px-3 py-2 mt-1 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div>
                <label for="teacher" class="block text-sm font-medium text-gray-700">Docente</label>
                <input type="hidden" name="teacher_id" id="teacher_id" wire:model="teacher_id"> 
                <input type="text" id="teacher" wire:model.live.debounce.250ms="teacherSelected" required class="w-full px-3 py-2 mt-1 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                @if (!empty($teachers))
                    <ul class="autocomplete-lista mt-2 border border-gray-300 rounded shadow-sm">
                        @foreach ($teachers as $usuario)
                        <li class="autocomplete-item px-3 py-2 cursor-pointer hover:bg-gray-200" x-on:click="$wire.teacherSelected = '{{ $usuario->nombre }} {{ $usuario->apellido }}'; $wire.teacher_id = '{{ $usuario->id }}'; $wire.teachers = null">
                                {{ $usuario->nombre }} {{ $usuario->apellido }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div>
                <label for="gradeSelected" class="block text-sm font-medium text-gray-700">Grado</label>
                <select name="gradeSelected" id="gradeSelected" wire:model="gradeSelected" required class="w-full px-3 py-2 mt-1 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    <option value="">Seleccione un grado</option>
                    @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}" @selected($gradeSelected && $grade->id == $gradeSelected)>
                            {{ $grade->grado }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="class" class="block text-sm font-medium text-gray-700">Clase</label>
                <select name="class" id="class" wire:model="classSelected" required class="w-full px-3 py-2 mt-1 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    <option value="">Seleccione una clase</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected($classSelected && $class->id == $classSelected)>
                            {{ $class->grupo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline">Guardar</button>
        </form>
    </div>
</div>
