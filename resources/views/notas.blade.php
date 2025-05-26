<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notas') }}
            </h2>
            
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link  href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">
            <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
            <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
        </div>

        <style>
            .editable-cell {
                display: block;
                width: 100%;
                height: 100%;
                padding: 5px;
                box-sizing: border-box;
                border: 1px solid #ccc;
            }

            table.dataTable td {
                min-height: 40px;
                vertical-align: middle;
            }

            .outRange {
                border: 2px solid red;
                color: red;
                font-weight: bold;
            }
        </style>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="container  overflow-x-scroll">
                    <div class="m-2 p-2 grid">
                        <p class="text-red-700 bg-red-100 border border-red-400 rounded" id="messageContent"></p>
                    </div>
                    
                    <table id="notas-table" class="display">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>NUIP</th>
                                <th>Notas</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div class="grid m-2 p-2">
                        <button class="btn btn-primary" id="guardarButton">Guardar</button>
                    </div>
            </div>
            </div>
        </div>
    </div>

    <script type="module">
        import sweetalert2 from 'https://cdn.jsdelivr.net/npm/sweetalert2@11.19.1/+esm';
        import {deleteResource} from '/js/delete-resource.js';
        import {Delete} from '/js/bulk-delete.js';

//si scrollX esta activado, no funcionan moveToCell
       
        const table = $('#notas-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('tabla-notas') }}",
                data: function(d) {
                    d.actividad_id = {{ $actividad_id }};
                }
            },
            columns: [
                { data: 'nombre', name: 'nombre' },
                { data: 'apellido', name: 'apellido'},
                { data: 'nuip', name: 'nuip' },
                { data: 'rates', name:'notas', orderable: false, searchable: false},
            ]
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

        function handleInput(e) {
            messageContent.textContent = '';
            if (e.target.classList.contains('outRange')) {
                e.target.classList.remove('outRange');
            }

            if (e.target.classList.contains('editable-cell')) {
                const id = e.target.dataset.id;
                const valor = parseFloat(e.target.textContent);


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

                const notaExistente = notas.find((nota) => nota.id === id);

                if (notaExistente) {
                    notaExistente.valor = valor;
                } else {
                    notas.push({ id, valor });
                }

                console.log(notas);
            }
        }

        const debouncedHandleInput = debounce(handleInput, 500);

        document.addEventListener('input', debouncedHandleInput);

        function updateCellsWithStoredValues() {

            table.rows({ page: 'current' }).every(function () {
                const row = this.node(); 
                const cells = row.querySelectorAll('.editable-cell'); 

                cells.forEach((cell) => {
                    const id = cell.dataset.id; 
                    const notaExistente = notas.find((nota) => nota.id === id); 

                    if (notaExistente) {
                        cell.textContent = notaExistente.valor;
                    }
                });
            });
        }
        //escuchar eventos de actualizacion de la tabla
        table.on('draw.dt order.dt search.dt page.dt xhr.dt', function () {
            updateCellsWithStoredValues();
        });

        function saveNotas() {

            fetch('/notas/save', { 
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        'actividad_id': {{ $actividad_id }},
                        'notas': notas
                    })
                })
                .then(response => response.json())
                .then(data => {

                    if (data.success) {
                        notas = [];
                        updateCellsWithStoredValues();

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
                });
        }
        

        document.getElementById('guardarButton').addEventListener('click', saveNotas);
    </script>
 
    <script src="/js/notas.js"></script>

</x-app-layout>