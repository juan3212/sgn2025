<x-app-layout>
    <x-slot name="header">
    <div class="flex flex-wrap justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Competencias') }}
            </h2>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
           

            <x-botones-header :createRoute="'create-competencia'"/>
            
        </div>
    </x-slot>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">

            <!-- Filtros -->
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 mb-6">
                <div>
                    <label for="grado" class="block text-gray-700 font-medium mb-1">Grado:</label>
                    <select name="grado" id="grado"
                        class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach ($grados as $grado)
                            <option value="{{ $grado->id }}">{{ $grado->grado }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="grupo" class="block text-gray-700 font-medium mb-1">Grupo:</label>
                    <select name="grupo" id="grupo"
                        class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach ($grupos as $grupo)
                            <option value="{{ $grupo->id }}">{{ $grupo->grupo }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="materia" class="block text-gray-700 font-medium mb-1">Materia:</label>
                    <select name="materia" id="materia"
                        class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach ($materias as $materia)
                            <option value="{{ $materia->id }}">{{ $materia->nombre_materia }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="periodo" class="block text-gray-700 font-medium mb-1">Periodo:</label>
                    <select name="periodo" id="periodo"
                        class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach ($periodos as $periodo)
                            <option value="{{ $periodo->id }}">{{ $periodo->periodo }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2 items-end">
                    <button type="button" id="buscar"
                        class="w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                        Buscar
                    </button>
                    <button type="button" id="limpiar"
                        class="w-full sm:w-auto bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                        Limpiar
                    </button>
                </div>
            </div>

            <!-- Tabla -->
            <div class="overflow-x-auto">
                <table id="competencias-table" class="display w-full">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="select-all" class="form-checkbox h-5 w-5 text-blue-600">
                            </th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Periodo</th>
                            <th>Porcentaje</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Periodo</th>
                            <th>Porcentaje</th>
                            <th>Acciones</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</div>


    
    <script type="module">

        import { Delete } from '/js/bulk-delete.js';
        import {deleteResource} from '/js/delete-resource.js';

        $(document).ready(function(){
            $('#competencias-table').DataTable({
                processing:true,
                serverSide:true,
                responsive:true,
                scrollX:true,
                ajax:{
                    url: "{{ route('competencias.data') }}",
                    data: function (d) {
                        d.grado = $('#grado').val();
                        d.grupo = $('#grupo').val();
                        d.materia = $('#materia').val();
                        d.periodo = $('#periodo').val();
                    }
                },
                columns:[
                    {data:'checkbox', name: 'checkbox', orderable:false, searchable:false},
                    {data:'id', name:'id'},
                    {data:'nombre', name:'nombre'},
                    {data:'descripcion', name:'descripcion'},
                    {data:'periodo_id', name:'periodo_id'},
                    {data: 'porcentaje', name:'porcentaje'},
                    {data:'actions', name:'actions', orderable:false, searchable:false}, 
                ]
            })
        })

        $('#buscar').click(function(){
            $('#competencias-table').DataTable().ajax.reload();
        })

        $('#limpiar').click(function(){
            $('#grado').val('');
            $('#grupo').val('');
            $('#materia').val('');
            $('#periodo').val('');
            $('#competencias-table').DataTable().ajax.reload();
        })

        const bulk = new Delete('competencias-table', 'Competencias');
        $('#competencias-table').on('click', '.delete', function(){
            const competenciaId = $(this).data('id');
            deleteResource({
                controllerName: 'Competencias',
                resourceId: competenciaId,
                onSuccessCallback:() => {
                    $('#competencias-table').DataTable().ajax.reload();
                }
            });
        })
    </script>

</x-app-layout>