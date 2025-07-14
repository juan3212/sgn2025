<x-app-layout>
    <x-slot name="header">
    <div class="flex flex-wrap justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Materias') }}
                @can('ver notas')
                <span class="text-gray-600">- PERIODO {{ $periodo }}</span>
                @endcan
            </h2>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
           
            <link rel="stylesheet" href="/js/Datatables/datatables.css">
            

            @can('administrar materias')
            <x-botones-header :createRoute="'create-materia'"/>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="container">
                <div class="flex flex-row">
                        
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
                            @can('administrar materias')
                            <th><input type="checkbox" id="select-all" class="form-checkbox h-5 w-5 text-blue-600"></th>
                            <th>ID</th>
                            @endcan
                            <th>Materia</th>
                            <th>Grado</th>
                            <th>Curso</th>
                            @can('administrar materias')
                            <th>Profesor</th>
                            @endcan
                            @can('ver notas')
                            <th>Notas</th>
                            @endcan
                            @can('administrar materias')
                            <th>Intensidad horaria</th>
                            <th>Acciones</th> {{-- Columna opcional para acciones --}}
                            @endcan
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            @can('administrar materias')
                            <th></th>
                            <th>ID</th>
                            @endcan
                            <th>Materia</th>
                            <th>Grado</th>
                            <th>Curso</th>
                            @can('administrar materias')
                            <th>Profesor</th>
                            @endcan
                            @can('ver notas')
                            <th>Notas</th>
                            @endcan
                            @can('administrar materias')
                            <th>Intensidad horaria</th>
                            <th>Acciones</th> {{-- Columna opcional para acciones --}}
                            @endcan
                        </tr>
                    </tfoot>
                </table>
            </div>
            </div>
        </div>

        <script src="/js/Datatables/datatables.js"></script>

        
        <script type="module">

            import {deleteResource} from '/js/delete-resource.js';
            import {Delete} from '/js/bulk-delete.js';
            

            $(document).ready(function() {
                var table = $('#materias-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    scrollX: true,
                    ajax: "{{ route('materias.data') }}",
                    columns: [
                        @can('administrar materias')
                        {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                        {data: 'id', name: 'id'},
                        @endcan
                        {data: 'nombre_materia', name: 'materia'},
                        {data: 'grado', name: 'grado'},
                        {data: 'grupo', name: 'curso'},
                        @can('administrar materias')
                        {data: 'nombre', name: 'profesor'},
                        @endcan
                        @can('ver notas')
                        {data: 'notas', name: 'notas'},
                        @endcan
                        @can('administrar materias')
                        {data: 'intensidad_horaria', name: 'intensidad_horaria'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                        @endcan
                    ],
                    drawCallback: function(settings) {
                        // Asegúrate de que el evento de clic se adjunte después de cada redibujo
                        $('#materias-table tbody').off('click', 'tr').on('click', 'tr', function(event) {
                            if ($(event.target).is('input:checkbox') || $(event.target).is('button') || $(event.target).closest('button').length) {
                                return;
                            }
                            var data = table.row(this).data();
                            if (data && data.id) {
                                window.location.href = '/materia/' + data.id;
                            }
                        });
                    }
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