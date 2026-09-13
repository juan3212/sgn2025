<div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <div class="border-b border-gray-200 pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Informe de Estudiantes con Materias Reprobadas</h2>
        <p class="text-sm text-gray-500 mt-1">Consulte y exporte el reporte a nivel general de toda la institución, por grado completo o por curso específico.</p>
    </div>

    <!-- Filtros Flexibles -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div>
            <label for="grado" class="block text-sm font-semibold text-gray-700 mb-1">
                Grado <span class="text-xs text-gray-400 font-normal">(Opcional)</span>
            </label>
            <select wire:model.live="gradoSelected" id="grado" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todos los grados</option>
                @foreach($grados as $grado)
                    <option value="{{ $grado->id }}">{{ $grado->grado }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="grupo" class="block text-sm font-semibold text-gray-700 mb-1">
                Curso / Grupo <span class="text-xs text-gray-400 font-normal">(Opcional)</span>
            </label>
            <select wire:model="grupoSelected" id="grupo" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todos los cursos/grupos</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}">{{ $grupo->grupo }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="materia" class="block text-sm font-semibold text-gray-700 mb-1">
                Materia <span class="text-xs text-gray-400 font-normal">(Opcional)</span>
            </label>
            <select wire:model="materiaSelected" id="materia" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todas las materias</option>
                @foreach($materias as $materia)
                    <option value="{{ $materia->id }}">{{ $materia->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="periodo" class="block text-sm font-semibold text-gray-700 mb-1">
                Periodo <span class="text-xs text-gray-400 font-normal">(Opcional)</span>
            </label>
            <select wire:model="periodoSelected" id="periodo" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Periodo activo o acumulado</option>
                @foreach($periodos as $periodo)
                    <option value="{{ $periodo->id }}">Periodo {{ $periodo->id }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Barra de acciones: Botones de consulta y exportación -->
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <!-- Botón Generar Informe -->
        <button type="button" wire:click="getInforme" class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 inline-flex items-center justify-center gap-2 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Generar Informe
        </button>

        <!-- Botón Exportar a Excel -->
        <button type="button" wire:click="exportar('xlsx')" class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition duration-150 inline-flex items-center justify-center gap-2 shadow-sm" title="Exportar informe a formato Excel (.xlsx)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Exportar a Excel
        </button>

        <!-- Botón Exportar a CSV -->
        <button type="button" wire:click="exportar('csv')" class="w-full sm:w-auto px-5 py-2.5 bg-slate-700 text-white text-sm font-medium rounded-md hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition duration-150 inline-flex items-center justify-center gap-2 shadow-sm" title="Exportar informe a formato CSV (.csv)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Exportar a CSV
        </button>

        <!-- Indicador de carga -->
        <span wire:loading wire:target="getInforme, exportar" class="text-sm text-gray-500 flex items-center gap-2">
            <svg class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            Procesando...
        </span>
    </div>

    <!-- Resumen cuando hay datos -->
    @if(!empty($informe))
        <div class="mb-4 p-3 bg-blue-50 border-l-4 border-blue-500 text-blue-800 rounded text-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <strong>Total estudiantes con materias reprobadas:</strong> {{ count($informe) }}
                <span class="text-xs text-blue-600 block sm:inline sm:ml-2">
                    (Filtro aplicado: {{ $gradoSelected ? 'Grado seleccionado' : 'Todos los grados' }} &bull; {{ $grupoSelected ? 'Grupo seleccionado' : 'Todos los grupos' }})
                </span>
            </div>
            <div class="text-xs text-blue-600">
                Puede descargar este informe en Excel o CSV con los botones superiores.
            </div>
        </div>
    @endif

    <!-- Tabla de resultados -->
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="informe-table">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estudiante</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Grado</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Grupo</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Materia Reprobada</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nota Definitiva</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if(!empty($informe))
                    @foreach($informe as $estudiante)
                        @foreach($estudiante['materias'] as $materia)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $estudiante['estudiante'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $estudiante['grado'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $estudiante['grupo'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $materia['materia'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
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
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                            {{ $informeMessage }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
