<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Estudiantes') }}
        </h2>
    </x-slot>
    
    <x-profesores.notas-estudiantes :materia="1" />
</x-app-layout>