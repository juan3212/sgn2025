<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <div class="flex flex-col items-center mt-4">
        <div class="flex flex-col items-center bg-white rounded-lg shadow-md p-6 w-1/2 mx-auto">
            <h2 class="font-medium text-lg text-gray-900 m-4">Para buscar un boletín, ingrese el nombre del estudiante y haga
                clic en buscar</h2>
            @livewire('components.select-users', [
                'role' => 'estudiante',
                'title' => 'Nombre del estudiante',
                'wire:model' => 'estudiante_id',
                'class' => 'mt-4 border border-gray-300 rounded w-full'
            ])
                <button wire:click="buscarBoletin" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Buscar</button>
            
</div>
</div>
</div>
