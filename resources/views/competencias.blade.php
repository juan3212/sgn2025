<x-app-layout>
    <x-slot name="header">
    <div class="flex flex-wrap justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Competencias') }}
            </h2>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link  href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">

            <x-botones-header :createRoute="'create-competencia'"/>
            
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="container">
                <div class="flex flex-row">        
                </div>
                <table id="competencias-table" class="display">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all" class="form-checkbox h-5 w-5 text-blue-600"></th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Periodo</th>
                            <th>Porcentaje</th>
                            <th>Acciones</th> {{-- Columna opcional para acciones --}}
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

        import { Delete } from '/js/bulk-delete.js';
        import {deleteResource} from '/js/delete-resource.js';

        $(document).ready(function(){
            $('#competencias-table').DataTable({
                processing:true,
                serverSide:true,
                responsive:true,
                scrollX:true,
                ajax: "{{ route('competencias.data') }}",
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