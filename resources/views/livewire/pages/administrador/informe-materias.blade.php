<div class="max-w-6xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Informe de Materias</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div>
            <label for="grado" class="block text-sm font-medium text-gray-700 mb-2">Grado</label>
            <select wire:model.live="gradoSelected" id="grado" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">Seleccione un grado</option>
                @foreach($grados as $grado)
                    <option value="{{ $grado->id }}">{{ $grado->grado }}</option>
                @endforeach
            </select>
            <div class="text-red-500">@error('gradoSelected') {{ $message }} @enderror</div>
        </div>
        
        <div>
            <label for="grupo" class="block text-sm font-medium text-gray-700 mb-2">Grupo</label>
            <select wire:model="grupoSelected" id="grupo" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required> 
                <option value="">Seleccione un grupo</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}">{{ $grupo->grupo }}</option>
                @endforeach
            </select>
            <div class="text-red-500">@error('grupoSelected') {{ $message }} @enderror</div>
        </div>
        
        <div>
            <label for="materia" class="block text-sm font-medium text-gray-700 mb-2">Materia</label>
            <select wire:model="materiaSelected" id="materia" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Seleccione una materia</option>
                @foreach($materias as $materia)
                    <option value="{{ $materia->id }}">{{ $materia->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <button type="submit" wire:click="getInforme" class="w-full md:w-auto px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
        Generar Informe
    </button>

    <div class="mt-8 overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="informe-table">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grupo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materia</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nota</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if(!empty($informe))
                @foreach($informe as $estudiante)
                    @foreach($estudiante['materias'] as $materia)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $estudiante['estudiante'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $estudiante['grado'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $estudiante['grupo'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $materia['materia'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($materia['promedio'] >= 4.0) bg-green-100 text-green-800
                                @elseif($materia['promedio'] >= 3.0) bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $materia['promedio'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                @endforeach
                @else
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">{{ $informeMessage }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
