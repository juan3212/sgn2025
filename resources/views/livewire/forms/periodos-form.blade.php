<div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Nuevo Período</h2>
    
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form wire:submit.prevent="submit" class="space-y-4">
        <div>
            <label for="periodo" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Período</label>
            <input type="text" 
                   id="periodo" 
                   wire:model="periodo" 
                   placeholder="Periodo 1"
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('periodo') border-red-500 @enderror">
            @error('periodo')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio</label>
            <input type="date" 
                   id="fecha_inicio" 
                   wire:model="fecha_inicio" 
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fecha_inicio') border-red-500 @enderror">
            @error('fecha_inicio')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Finalización</label>
            <input type="date" 
                   id="fecha_fin" 
                   wire:model="fecha_fin" 
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fecha_fin') border-red-500 @enderror">
            @error('fecha_fin')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="pt-2">
            <button type="submit" 
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ $id ? 'Actualizar' : 'Guardar' }} Período
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('periodoSaved', message => {
            alert(message);
            // O si prefieres usar un toast o alguna otra librería de notificaciones
            // Ejemplo con SweetAlert2:
            // Swal.fire('¡Éxito!', message, 'success');
        });

        Livewire.on('periodoError', message => {
            alert(message);
            // O con SweetAlert2:
            // Swal.fire('Error', message, 'error');
        });
    });
</script>
@endpush
