<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Usuarios') }}
            </h2>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link  href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">

           <x-botones-header :createRoute="'create-user'"/>

        </div>

    </x-slot>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-4">

            <!-- Contenedor de filtros -->
            <div class="flex flex-wrap gap-4 justify-between items-end pt-2 mb-4">
                <div class="flex flex-wrap gap-4">
                <div class="w-full sm:w-auto">
                    <label for="grado" class="block text-gray-700 font-medium mb-1">Grado:</label>
                    <select name="grado" id="grado" class="border border-gray-300 rounded-md px-3 py-2 w-full sm:w-40 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach ($grados as $grado)
                            <option value="{{ $grado->id }}">{{ $grado->grado }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-auto">
                    <label for="grupo" class="block text-gray-700 font-medium mb-1">Grupo:</label>
                    <select name="grupo" id="grupo" class="border border-gray-300 rounded-md px-3 py-2 w-full sm:w-40 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach ($grupos as $grupo)
                            <option value="{{ $grupo->id }}">{{ $grupo->grupo }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-auto">
                    <label for="role" class="block text-gray-700 font-medium mb-1">Rol:</label>
                    <select name="role" id="role" class="border border-gray-300 rounded-md px-3 py-2 w-full sm:w-40 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                </div>
                <div class="w-full sm:w-auto flex gap-2">
                    <button type="button" id="buscar" class="flex-1 sm:flex-none bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                        Buscar
                    </button>
                    <button type="button" id="limpiar" class="flex-1 sm:flex-none bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                        Limpiar
                    </button>
                </div>
            </div>

            <!-- Tabla original sin cambios -->
            <div class="container">
                <div class="flex flex-wrap"></div>
                <table id="users-table" class="display">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all" class="form-checkbox h-5 w-5 text-blue-600"></th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Nuip</th>
                            <th>Grado</th>
                            <th>Grupo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Nuip</th>
                            <th>Grado</th>
                            <th>Grupo</th>
                            <th>Acciones</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</div>



    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script type="module">

        import {Delete} from '/js/bulk-delete.js';
        import {deleteResource} from '/js/delete-resource.js';

        $(document).ready(function () {
            $('#users-table').DataTable({
                processing: true, // Muestra un indicador de "Procesando..." mientras se cargan los datos
                serverSide: true, // Habilita el procesamiento del lado del servidor
                responsive: true,
                scrollX: true, // Habilita la responsividad de la tabla
                ajax: {
                    url: "{{ route('user.data') }}",
                    data: function (d) {
                        d.grado = $('#grado').val();
                        d.grupo = $('#grupo').val();
                        d.role = $('#role').val();
                    }
                }, // URL de la ruta que devuelve los datos JSON
                columns: [ // Define las columnas de la tabla, deben coincidir con las columnas seleccionadas en el controlador
                    {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                    {data: 'id', name: 'id'},
                    {data: 'nombre', name: 'nombre'},
                    {data: 'apellido', name: 'apellido'},
                    {data: 'nuip', name: 'nuip'},
                    {data: 'grado', name: 'grado', searchable: false},
                    {data: 'grupo', name: 'grupo', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}, // Columna de acciones, no se puede ordenar ni buscar
                ],
            });
        });

        const bulk = new Delete([
             'users-table',
             'Usuario'
        ]);

        $('#users-table').on('click', '.delete', function () {
            const id = $(this).data('id');
            deleteResource({
                controllerName: 'Usuarios',
                resourceId: id,
                onSuccessCallback: () => {
                    $('#users-table').DataTable().ajax.reload();
                },
            });
        });

        $('#buscar').click(function () {
            $('#users-table').DataTable().ajax.reload();
        });

        $('#limpiar').click(function () {
            $('#grado').val('');
            $('#grupo').val('');
            $('#role').val('');
            $('#users-table').DataTable().ajax.reload();
        });
    </script>
</x-app-layout>
