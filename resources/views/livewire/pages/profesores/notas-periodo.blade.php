<div>

    <div x-data="{ open: false, competenciaForm: false }">

        <div x-show="open"
            class="fixed h-screen inset-0 z-50 flex items-center justify-center overflow-y-hidden overflow-x-hidden bg-gray-900 bg-opacity-50 backdrop-blur-sm p-4">

            <div x-on:click.away="open = false"
                class="relative w-full max-w-2xl max-h-full rounded-lg bg-white shadow-xl ring-1 ring-gray-900/5">


                <button x-on:click="open = false" type="button"
                    class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>

                <div class="p-4">
                    <livewire:forms.actividades-form wire:key="actividad-form{{ count($competencias).count($actividades) }}" :periodo="$periodo_id" :materia="$materia_id"
                        wire:key="actividad-form" />
                </div>
            </div>
        </div>

        <div x-show="competenciaForm"
            class="fixed h-screen inset-0 z-50 flex items-center justify-center overflow-y-hidden overflow-x-hidden bg-gray-900 bg-opacity-50 backdrop-blur-sm p-4">

            <div x-on:click.away="competenciaForm = false"
                class="relative w-full max-w-2xl max-h-full rounded-lg bg-white shadow-xl ring-1 ring-gray-900/5">


                <button x-on:click="competenciaForm = false" type="button"
                    class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>

                <div class="p-4">
                    <livewire:forms.competencias-form wire:key="competencia-form{{ count($competencias).count($actividades) }}"/>
                </div>
            </div>
        </div>
    </div>

    <div>
        <h2></h2>
    </div>

    <!-- Informacion de competencias -->
    <div class="w-[95%] mx-auto my-4 overflow-x-auto shadow-md sm:rounded-lg bg-white">
        <table class="w-full text-sm text-left text-gray-500 border-collapse">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 w-[15%] font-bold border-b border-gray-200 border-x-0">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3 w-[75%] font-bold border-b border-gray-200 border-x-0">
                        Descripción
                    </th>
                    <th scope="col" class="px-6 py-3 w-[10%] text-center font-bold border-b border-gray-200 border-x-0">
                        Peso
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($competencias as $competencia)
                        <tr class="bg-white hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="px-6 py-4 font-medium text-gray-900 border-b border-gray-100 border-x-0">
                                {{ $competencia->nombre }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 border-b border-gray-100 border-x-0">
                                {{ $competencia->descripcion }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-blue-600 border-b border-gray-100 border-x-0">
                                {{ $competencia->porcentaje }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">No hay competencias</td>
                        </tr>
                    @endforelse
            </tbody>
        </table>
    </div>

    <!-- Informacion de actividades -->
    <div class="flex justify-between items-center mb-4 mx-auto my-8 w-[95%]">
        <h2 class="text-2xl font-bold">{{ $nombre_materia }} {{ $grado_nombre }} {{ $grupo_nombre }} - PERIODO {{ $periodo_id }}</h2>

        <div>
            <button type="button" x-on:click="competenciaForm = true"
                class="bg-sky-300 hover:bg-sky-400 text-blue-600 font-bold border border-blue-600 py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Agregar Competencia
            </button>

            <button type="button" x-on:click="open = true"
                x-bind:disabled="{{ $competencias->isEmpty() }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:opacity-50 disabled:cursor-not-allowed">
                Agregar Actividad
            </button>

            <button id="saveNotas" type="button"
                x-bind:disabled="{{ $actividades->isEmpty() }}"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:opacity-50 disabled:cursor-not-allowed">
                Guardar Notas
            </button>
        </div>
    </div>

    <div id="loading-overlay"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
    </div>

    <div id="messageContent" class="text-red-500 text-sm font-bold h-6 mb-2"></div>


    <div class="w-[95%] mx-auto my-4 overflow-x-auto shadow-md sm:rounded-lg">
        @if($actividades->isEmpty())
        <table>
            <thead>
                <tr>
                    <th>No hay actividades</th>
                </tr>
            </thead>
        </table>
        @else
        <table class="dataTable w-full text-sm text-left text-gray-500 border-collapse" id="notasPeriodo">
            <thead class="text-xs text-gray-900 uppercase bg-gray-100">
                <tr>
                    <th rowspan="2"
                        class="sticky  left-0 z-20 bg-gray-200 px-4 py-3 border-b border-gray-300 shadow-sm min-w-[200px] max-sm:left-0">
                        Nombre
                    </th>
                    <th rowspan="2"
                        class="sticky left-[200px] z-20 bg-gray-200 px-4 py-3 border-b border-gray-300 shadow-sm min-w-[200px] max-sm:left-0">
                        Apellido
                    </th>
                    <th rowspan="2"
                        class="sticky left-[400px] z-20 bg-gray-200 px-4 py-3 border-b border-gray-300 shadow-sm min-w-[100px] max-sm:left-0 max-sm:z-10">
                        Nota Final
                    </th>

                    @foreach ($competencias as $competencia)
                        <th class="text-center px-4 py-2 border-b border-l border-gray-300 bg-gray-200 whitespace-nowrap"
                            colspan="{{ $competencia->actividades->count() }}"
                            title="{{ $competencia->descripcion }}">
                            {{ $competencia->nombre . ' - ' . substr($competencia->descripcion, 0, 30) . '...' }}
                        </th>
                    @endforeach

                </tr>
                <tr>
                    @foreach ($competencias as $competencia)
                        @foreach ($competencia->actividades as $index => $actividad)
                            <th title="{{ $actividad->descripcion }}"
                                class="px-4 py-2 border-b border-l border-gray-300 bg-gray-50 whitespace-nowrap min-w-[100px] text-center">
                                Actividad {{ $index + 1 }}
                            </th>
                        @endforeach
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($estudiantes as $estudiante)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td
                            class="sticky left-0 z-10 bg-white px-4 py-3 font-medium text-gray-900 border-r border-gray-200 shadow-sm whitespace-nowrap max-sm:left-0">
                            {{ $estudiante->nombre }}
                        </td>

                        <td
                            class="sticky left-[200px] z-10 bg-white px-4 py-3 font-medium text-gray-900 border-r border-gray-200 shadow-sm whitespace-nowrap max-sm:left-0">
                            {{ $estudiante->apellido }}
                        </td>
                        <td
                            class="sticky left-[400px] bg-indigo-200 z-10 px-2 py-2 border border-gray-200 text-gray-900 text-center align-middle max-sm:left-0 max-sm:z-0">
                          {{ $estudiante->notasMateria[0]->nota_final ?? '' }}
                        </td>

                        @foreach ($competencias as $competencia)
                            @foreach ($competencia->actividades as $actividad)
                                <td class="px-2 py-2 border border-gray-200 text-gray-900 text-center align-middle">
                                    <span contenteditable="true"
                                        class="editable-cell block w-full rounded border border-transparent hover:border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none px-2 py-1 transition-all cursor-text"
                                        data-estudiante_id="{{ $estudiante->id }}"
                                        data-actividad_id="{{ $actividad->id }}">
                                        {{ $estudiante->notas->firstWhere('actividad_id', $actividad->id)?->valor }}
                                    </span>
                                </td>
                            @endforeach
                        @endforeach

                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif

    <script type="module">
        import sweetalert2 from 'https://cdn.jsdelivr.net/npm/sweetalert2@11.19.1/+esm';

        document.addEventListener('DOMContentLoaded', () => {


            const dataTable = new DataTable('.dataTable', {
                scrollCollapse: true,
                paging: false,
                info: false,
                searching: false,
                ordering: true,
            });

            const messageContent = document.getElementById('messageContent');
            const saveButton = document.getElementById('saveNotas');
            const overlay = document.getElementById("loading-overlay");

            function debounce(func, delay) {
                let timeoutId;
                return function(...args) {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => func.apply(this, args), delay);
                };
            }

            function parseFloatCell(value) {
                if (!value) return NaN;
                return parseFloat(value.toString().replace(',', '.').trim());
            }

            function handleInput(e) {
                const cell = e.target;

                if (!cell.classList.contains('editable-cell')) return;

                const textVal = cell.innerText.trim();

                cell.classList.remove('bg-red-200', 'text-red-800', 'outRange');
                messageContent.textContent = '';

                if (textVal === '') return;

                const valor = parseFloatCell(textVal);

                if (isNaN(valor) || valor < 0.1 || valor > 10) {
                    cell.classList.add('bg-red-200', 'text-red-800', 'outRange');
                    messageContent.textContent = 'La nota debe ser un número entre 0.1 y 10';
                    saveButton.disabled = true;
                    saveButton.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    const errores = document.querySelectorAll('.outRange');
                    if (errores.length === 0) {
                        saveButton.disabled = false;
                        saveButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                }
            }

            function collectData() {
                let notasParaGuardar = [];
                const cells = document.querySelectorAll('.editable-cell');

                cells.forEach(cell => {
                    const valorRaw = cell.innerText.trim();

                    if (valorRaw !== '' && !cell.classList.contains('outRange')) {
                        notasParaGuardar.push({
                            estudiante_id: cell.dataset.estudiante_id,
                            actividad_id: cell.dataset.actividad_id,
                            valor: parseFloatCell(valorRaw)
                        });
                    }
                });
                return notasParaGuardar;
            }

            function saveNotas() {
                if (document.querySelectorAll('.outRange').length > 0) {
                    sweetalert2.fire({
                        title: 'Error',
                        text: 'Corrige las notas marcadas en rojo antes de guardar.',
                        icon: 'warning'
                    });
                    return;
                }

                const notas = collectData();

                if (notas.length === 0) {
                    sweetalert2.fire('Info', 'No hay notas válidas para guardar', 'info');
                    return;
                }

                saveButton.disabled = true;
                saveButton.textContent = 'Guardando...';
                overlay.classList.remove('hidden');

                fetch('/notas/saveNotasActividades', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            notas: notas
                        })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Error en la respuesta del servidor');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            sweetalert2.fire({
                                title: 'Éxito',
                                text: data.message || 'Notas guardadas correctamente',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Error desconocido');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        sweetalert2.fire({
                            title: 'Error',
                            text: error.message || 'Hubo un problema al guardar',
                            icon: 'error'
                        });
                    })
                    .finally(() => {
                        overlay.classList.add('hidden');
                        saveButton.disabled = false;
                        saveButton.textContent = 'Guardar Notas';
                    });
            }


            const debouncedHandleInput = debounce(handleInput, 300);
            document.addEventListener('input', debouncedHandleInput);
            
            document.addEventListener('paste', handlePaste, true);

            if (saveButton) {
                saveButton.addEventListener('click', saveNotas);
            }
        });

        Livewire.on('actividad-competencia-guardada', () => {
            window.location.reload();
        });
       
    </script>
        
        <script src="{{ asset('js/notas.js') }}"></script>
    </div>

    

</div>