<div class="max-w-md mx-auto mt-8 p-6 bg-white rounded-lg shadow-lg">
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    @csrf
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
    <form wire:submit.prevent="submit" class="space-y-4">
        <div class="flex flex-col">
            <label for="nombre" class="text-gray-700 font-medium mb-1">Nombre:</label>
            <input type="text" wire:model="nombre" class="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex flex-col">
            <label for="descripcion" class="text-gray-700 font-medium mb-1">Descripción:</label>
            <textarea wire:model="descripcion" class="border rounded-md px-3 py-2 h-32 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
        <div class="flex flex-col">
            <label for="tipo" class="text-gray-700 font-medium mb-1">Tipo de nota</label>
            <select wire:model="tipoSelected" class="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Seleccione un tipo</option>
                @foreach ($tipo as $tipo)
                    <option value="{{ $tipo->id }}" @selected($tipoSelected && $tipo->id == $tipoSelected)>
                        {{ $tipo->tipo }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-200">
                Guardar
            </button>
        </div>
    </form>
</div>
