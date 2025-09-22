<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buscar Boletin') }}
        </h2>
    </x-slot>

    <div>
       @livewire('pages.profesores.buscar-boletin')
    </div>
</x-app-layout>
