<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded shadow-md">
        {{-- The whole world belongs to you. --}}
        <form wire:submit.prevent="submit" class="space-y-4">
            @csrf
            <!-- Add your form fields here -->

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
                <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                <input type="text" id="name" name="name" required wire:model="name" class="w-full px-3 py-2 mt-1 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div>
                <label for="lastname" class="block text-sm font-medium text-gray-700">Lastname:</label>
                <input type="text" id="lastname" name="lastname" required wire:model="last_name" class="w-full px-3 py-2 mt-1 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div>
                <label for="nuip" class="block text-sm font-medium text-gray-700">Nuip:</label>
                <input type="text" id="nuip" name="nuip" required wire:model="nuip" class="w-full px-3 py-2 mt-1 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                <input type="email" id="email" name="email" wire:model="email" class="w-full px-3 py-2 mt-1 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                <input type="password" id="password" name="password" required wire:model="password" class="w-full px-3 py-2 mt-1 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Rol:</label>
                <select name="role" id="role" required wire:model.live="role" class="w-full px-3 py-2 mt-1 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    <option value="">Seleccione un rol</option>
                    <option value="administrador">Administrador</option>
                    <option value="profesor">Profesor</option>
                    <option value="estudiante">Estudiante</option>
                </select>
            </div>

            @if ($role == 'estudiante')
                <div>
                    <label for="grade" class="block text-sm font-medium text-gray-700">Grado:</label>
                    <select name="grade" id="grade" required wire:model="selectedGrade" class="w-full px-3 py-2 mt-1 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                        <option value="">Seleccione un Grado</option>
                        @foreach ($grades as $grade)
                            
                            <option value="{{ $grade->id }}">{{ $grade->grado }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="class" class="block text-sm font-medium text-gray-700">Curso:</label>
                    <select name="class" id="class" required wire:model="selectedClass" class="w-full px-3 py-2 mt-1 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                        <option value="">Seleccione un Curso</option>
                        <option value="1">A</option>
                        <option value="2">B</option>
                        <option value="3">C</option>
                        <option value="4">D</option>
                    </select>
                </div>
                

            
            @endif
            <div>
                <button type="button" wire:click="resetForm" class="w-full px-4 py-2 font-bold text-white bg-gray-500 rounded hover:bg-gray-700 focus:outline-none focus:shadow-outline">Reset</button>
            </div>
            <button type="submit" class="w-full px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline">Submit</button>
        </form>
    </div>
</div>
