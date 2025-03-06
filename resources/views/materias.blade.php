<x-app-layout>
    <x-slot name="header">
    <div class="flex flex-wrap justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Materias') }}
            </h2>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link  href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">

            <div>
                <a href="{{ route('create-materia') }}"><button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Agregar Materia</button></a> <!-- Fixed button label -->
                <button id="delete-selected" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed hidden">Eliminar seleccionados</button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container">
                <div class="flex flex-row">
                        <h3>Materias</h3>
                </div>
                @csrf

                @if (session()->has('success'))
                    <div class="p-4 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="p-4 mb-4 text-red-700 bg-red-100 border border-red-400 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <table id="materias-table" class="display">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all" class="form-checkbox h-5 w-5 text-blue-600"></th>
                            <th>ID</th>
                            <th>Materia</th>
                            <th>Grado</th>
                            <th>Curso</th>
                            <th>Profesor</th>
                            <th>Intensidad horaria</th>
                            <th>Acciones</th> {{-- Columna opcional para acciones --}}
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Materia</th>
                            <th>Grado</th>
                            <th>Curso</th>
                            <th>Profesor</th>
                            <th>Intensidad horaria</th>
                            <th>Acciones</th> {{-- Columna opcional para acciones --}}
                        </tr>
                    </tfoot>
                </table>
            </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

        
        <script type="module">

            import {deleteResource} from '/js/delete-resource.js';
            import {Delete} from '/js/bulk-delete.js';

            fetch('/table-materias').then(response => response.json()).then(data => {
                console.log(data);
            });
            

            $(document).ready(function() {
                $('#materias-table').DataTable({
                    processing: true, // Muestra un indicador de "Procesando..." mientras se cargan los datos
                    serverSide: true, // Habilita el procesamiento del lado del servidor
                    ajax: "{{ route('materias.data') }}", // URL de la ruta que devuelve los datos JSON
                    columns: [ // Define las columnas de la tabla, deben coincidir con las columnas seleccionadas en el controlador
                        {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                        {data: 'id', name: 'id'},
                        {data: 'nombre_materia', name: 'materia'},
                        {data: 'grado', name: 'grado'},
                        {data: 'grupo', name: 'curso'},
                        {data: 'nombre', name: 'profesor'},
                        {data: 'intensidad_horaria', name: 'intensidad_horaria'},
                        {data: 'action', name: 'action', orderable: false, searchable: false} // Columna opcional para acciones
                        
                    ]
                });
            });

            const bulk = new Delete('materias-table', 'Materia');

            $('#materias-table').on('click', '.delete', function() {
                const materiaId = $(this).data('id');
               deleteResource({
                    controllerName: 'Materias',
                    resourceId: materiaId,
                    onSuccessCallback:() => {
                        $('#materias-table').DataTable().ajax.reload();
                    }

                });
            });
        </script>
    
</x-app-layout>