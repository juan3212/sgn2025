<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link  href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">

            <div>
                <a href="{{ route('create-user') }}"><button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Agregar Usuario</button></a> <!-- Fixed button label -->
                <button id="delete-selected" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed hidden">Eliminar seleccionados</button>
            </div>
            
        </div>
       
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container">
                <h3>Usuarios</h3>
                <table id="users-table" class="display">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all" class="form-checkbox h-5 w-5 text-blue-600"></th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Nuip</th>
                            <th>Correo</th>
                            <th>Acciones</th> {{-- Columna opcional para acciones --}}
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Nuip</th>
                            <th>Correo</th>
                            <th>Acciones</th> {{-- Columna opcional para acciones --}}
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
                ajax: "{{ route('user.data') }}", // URL de la ruta que devuelve los datos JSON
                columns: [ // Define las columnas de la tabla, deben coincidir con las columnas seleccionadas en el controlador
                    {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                    {data: 'id', name: 'id'},
                    {data: 'nombre', name: 'nombre'},
                    {data: 'apellido', name: 'apellido'},
                    {data: 'nuip', name: 'nuip'},
                    {data: 'correo', name: 'correo'},
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

    </script>
</x-app-layout>
