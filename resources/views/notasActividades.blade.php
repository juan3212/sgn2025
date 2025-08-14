<x-app-layout>

    <style>
             .editable-cell {
                display: block;
                width: 100%;
                height: 100%;
                padding: 5px;
                text-align: center;
            }
            td {
                padding: 0px 5px 0px 5px !important;
                border: 1px solid #ccc;
            }
            .outRange {
                background-color: #DB4040;
                font-weight: bold;
                color: white; /* Para asegurar que el texto sea legible */
            }

            .loader {
                border: 6px solid #f3f3f3;
                border-top: 6px solid #3498db;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

    </style>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $materia }}
        </h2>
    </x-slot>

    <div id="loading-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg flex flex-col items-center">
            <div class="loader"></div>
            <p class="mt-3 text-gray-700 font-semibold">Procesando...</p>
        </div>
    </div>

    <div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-full mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg sm:rounded-lg p-6">
            
            <div class="flex flex-col space-y-4">
                <!-- Mensaje de alerta -->
                <p id="messageContent" 
                   class="hidden px-4 py-2 text-red-700 bg-red-100 border border-red-400 rounded">
                </p>

                <!-- Tabla -->
                <div class="overflow-x-auto">
                    <table class="dataTable table display w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-700 uppercase">
                            <tr>
                                <th class="px-4 py-2">Nombre</th>
                                <th class="px-4 py-2">Apellido</th>
                                @foreach ($actividades['actividades_nombre'] as $actividad)
                                    <th class="px-4 py-2">{{ $actividad }}</th>
                                @endforeach
                                <th class="px-4 py-2">Promedio</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($estudiantesNotas as $estudiante)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $estudiante['nombre'] }}</td>
                                    <td class="px-4 py-2">{{ $estudiante['apellido'] }}</td>
                                    @foreach ($estudiante['actividades'] as $actividad)
                                        <td class="px-4 py-2">
                                            <span contenteditable="true" 
                                                  class="editable-cell border rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                                  data-estudiante_id="{{ $estudiante['estudiante_id'] }}"
                                                  data-id="{{ $actividad['actividad_id'] }}">
                                                {{ $actividad['valor'] }}
                                            </span>
                                        </td>
                                    @endforeach
                                    <td class="px-4 py-2 font-semibold">{{ $estudiante['promedio'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Botón -->
                <div class="grid mt-2 p-2">
                    <button id="saveNotas" 
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        Guardar
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>


            <script type="module">
                        import sweetalert2 from 'https://cdn.jsdelivr.net/npm/sweetalert2@11.19.1/+esm';
                        import { deleteResource } from '/js/delete-resource.js';
                        import { Delete } from '/js/bulk-delete.js';


                        const dataTable = new DataTable('.dataTable', {
                            scrollCollapse: true,
                            paging: false,
                            info: false,
                            searching: true,
                            ordering: true,
                        });


                        let notas = [];
                        const messageContent = document.getElementById('messageContent');

                        function debounce(func, delay) {
                            let timeoutId;
                            return function (...args) {
                                clearTimeout(timeoutId);
                                timeoutId = setTimeout(() => func.apply(this, args), delay);
                            };
                        }

                        function parseFloatCell(cell) {
                            const valor = parseFloat(cell.replace(',', '.'));
                            return valor;
                        }

                        function updateNota(actividad_id, estudiante_id, valor) {
                            let notaExistente = notas.find((nota) => nota.actividad_id === actividad_id && nota.estudiante_id === estudiante_id);
                            if (notaExistente) {
                                notaExistente.valor = valor;
                            } else {
                                notas.push({ actividad_id, estudiante_id, valor });
                            }
                        }

                        function save() {
                            let notasCell = document.getElementsByClassName('editable-cell');

                            for (let i = 0; i < notasCell.length; i++) {
                                const actividad_id = notasCell[i].dataset.id;
                                const estudiante_id = notasCell[i].dataset.estudiante_id;
                                const valor = parseFloatCell(notasCell[i].textContent);
                                updateNota(actividad_id, estudiante_id, valor);
                            }
                            console.log(notas);
                        }

                        function handleInput(e) {
                            messageContent.textContent = '';
                            if (e.target.classList.contains('outRange')) {
                                e.target.classList.remove('outRange');
                            }

                            if (e.target.classList.contains('editable-cell')) {
                                const actividad_id = e.target.dataset.id;
                                const estudiante_id = e.target.dataset.estudiante_id;
                                const valor = parseFloatCell(e.target.textContent);


                                if (valor > 10) {
                                    e.target.classList.add('outRange');
                                    messageContent.textContent = 'La nota debe ser un valor entre 0 y 10';
                                    return;
                                }

                                if (!valor) {
                                    e.target.classList.add('outRange');
                                    messageContent.textContent = 'La nota debe ser un valor entre 0 y 10';
                                    return;
                                }

                                updateNota(actividad_id, estudiante_id, valor);

                                console.log(notas);
                            }
                        }

                        const debouncedHandleInput = debounce(handleInput, 500);

                        document.addEventListener('input', debouncedHandleInput);

                        document.addEventListener('paste', save);

                        const saveButton = document.getElementById('saveNotas');
                        saveButton.addEventListener('click', saveNotas);

                        function saveNotas() {
                            save();

                            saveButton.disabled = true;
                            saveButton.textContent = 'Guardando...';
                            const overlay = document.getElementById("loading-overlay");
                            overlay.classList.remove('hidden');

                            fetch('/notas/saveNotasActividades', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    'notas': notas
                                })
                            })
                                .then(response => response.json())
                                .then(data => {

                                    if (data.success) {
                                        notas = [];

                                        Swal.fire({
                                            title: 'Éxito',
                                            text: data.message,
                                            icon: 'success',
                                        })
                                    }
                                    else {
                                        Swal.fire({
                                            title: 'Error',
                                            text: data.message,
                                            icon: 'error',
                                            //footer: data.error_details
                                        })
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire({
                                        title: 'Error',
                                        text: data.message,
                                        icon: 'error',
                                        //footer: data.error_details
                                    })
                                })
                                .finally(() => {
                                    overlay.classList.add('hidden');
                                    saveButton.disabled = false;
                                    saveButton.textContent = 'Guardar';
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                });
                        }
                    </script>
                    <script src="/js/notas.js?1"></script>
</x-app-layout>