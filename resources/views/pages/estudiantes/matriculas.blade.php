@props([
    "estudianteId" => 0,
])
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Matriculas') }}
        </h2>
    </x-slot>
    <livewire:matriculas.formulario-matricula :estudianteId="$estudianteId" />
</x-app-layout>