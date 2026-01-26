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
                <div class="p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg mx-6 my-4" role="alert">
                    <strong class="font-bold">Recuerda:</strong>
                    <span class="block sm:inline">Antes de comenzar el proceso de matricula, primero debes realizar el pago.</span>
                </div>
            </div>
        </div>
    </div>

    @if (session('error'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <div>
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif
    <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mt-8">
        <a href="{{ route('boletin', $estudianteId) }}"  class=" hidden block w-11/12 sm:w-64 p-4 text-center text-xl font-bold bg-blue-500 text-white rounded-lg shadow-lg hover:bg-indigo-700 transition duration-300 ease-in-out">
            Boletin
        </a>
        <a href="{{ route('matricula', $estudianteId) }}" class="block w-11/12 sm:w-64 p-4 text-center text-xl font-bold bg-green-500 text-white rounded-lg shadow-lg hover:bg-green-700 transition duration-300 ease-in-out">
            Matricula
        </a>
    </div>

        <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold mb-4">Videos Instructivos: Cómo Pagar</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col items-center">
                            <h3 class="text-xl font-semibold mb-2">Pago con Davivienda</h3>
                            <div class="aspect-w-16 aspect-h-9 w-full">
                                <video src="{{ asset('storage/videos/matriculas/pago_davivienda.mp4') }}" controls></video>
                            </div>
                        </div>
                        <div class="flex flex-col items-center">
                            <h3 class="text-xl font-semibold mb-2">Pago con Daviplata</h3>
                            <div class="aspect-w-16 aspect-h-9 w-full">
                                <video src="{{ asset('storage/videos/matriculas/pago_daviplata.mp4') }}" controls></video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>