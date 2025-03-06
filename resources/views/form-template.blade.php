<x-app-layout>  
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __( $formTitle ?? 'Formulario') }}
        </h2>
    </x-slot>


    @livewire($formComponent)

    

</x-app-layout>