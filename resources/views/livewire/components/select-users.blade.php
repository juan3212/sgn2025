<div>
    <label for="usuario" class="block text-sm font-medium text-gray-700">{{$title}}</label>
    <input type="hidden" name="usuario_id" id="usuario_id" wire:model="usuario_id">
    <input type="text" id="usuario" wire:model.live.debounce.250ms="usuarioSelected" required
        class="w-full px-3 py-2 mt-1 border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
    @if (!empty($usuarios))
        <ul class="autocomplete-lista mt-2 border border-gray-300 rounded shadow-sm">
            @foreach ($usuarios as $usuario)
                <li class="autocomplete-item px-3 py-2 cursor-pointer hover:bg-gray-200"
                    x-on:click="$wire.usuarioSelected = '{{ $usuario->nombre }} {{ $usuario->apellido }}'; $wire.usuario_id = '{{ $usuario->id }}'; $wire.usuarios = null">
                    {{ $usuario->nombre }} {{ $usuario->apellido }}
                </li>
            @endforeach
        </ul>
    @endif
    
</div>