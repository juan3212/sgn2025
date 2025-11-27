@props([
    'estudianteId' => request()->user()->id,
    'name' => request()->user()->nombre,
    'apellido' => request()->user()->apellido,
])
<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Bienvenido {{ $name }} {{ $apellido }}
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mt-8">
        <a href="{{ route('boletin', $estudianteId) }}" class="block w-11/12 sm:w-64 p-4 text-center text-xl font-bold bg-blue-500 text-white rounded-lg shadow-lg hover:bg-indigo-700 transition duration-300 ease-in-out">
            Boletin
        </a>
        <a href="{{ route('matricula', $estudianteId) }}" class="block w-11/12 sm:w-64 p-4 text-center text-xl font-bold bg-green-500 text-white rounded-lg shadow-lg hover:bg-green-700 transition duration-300 ease-in-out">
            Matricula
        </a>
    </div>
</x-app-layout>