<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Actividades') }}
        </h2>
    </x-slot>
    <div>
        @livewire('forms.actividades-form', ['id' => $id])
    </div>
</x-app-layout>