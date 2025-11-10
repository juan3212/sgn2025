<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 md:col-span-2">
    <div>
        <label for="depto_{{ $tipoUbicacion }}" class="block text-sm font-medium text-gray-700">Departamento {{ ucfirst($tipoUbicacion) }}</label>
        <select id="depto_{{ $tipoUbicacion }}" name="depto_{{ $tipoUbicacion }}" wire:model.live="departamento"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option>Seleccione...</option>
            @foreach($departamentosLista as $departamentos)
                <option 
                    value="{{ $departamentos['id'] }}"
                    @if($departamento && $departamentos['name'] == $departamento)
                        selected
                    @endif
                    >
                    {{ $departamentos['name'] }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="municipio_{{ $tipoUbicacion }}" class="block text-sm font-medium text-gray-700">Municipio {{ ucfirst($tipoUbicacion) }}</label>
        <select id="municipio_{{ $tipoUbicacion }}" name="municipio_{{ $tipoUbicacion }}" wire:model.live="municipio"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option>Seleccione...</option>
          {{ $municipio }}
            @if($municipiosLista)
                @foreach($municipiosLista as $municipios)
                    <option 
                        value="{{$municipios['name']}}"
                        @if($municipio && $municipios['name'] == $municipio)
                            selected
                        @endif
                        >
                        {{ $municipios['name'] }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
</div>