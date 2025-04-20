<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Actividades') }}
            </h2>

            <x-botones-header :createRoute="route('create-actividad', ['materia'=>$materia, 'periodo'=>$periodo, 'competencia'=>$competencia])"/>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="container">
                    <h3>Actividades</h3>
                    <table id="actividades-table" class="display">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>Nombre</th>
                                <th>Descripcion</th>
                                <th>Tipo de nota</th>
                                <th>Acciones</th>
                                <th>Notas</th>
                            </tr>
                        </thead> 
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Nombre</th>
                                <th>Descripcion</th>
                                <th>Tipo de nota</th>
                                <th>Acciones</th>
                                <th>Notas</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link  href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    <script type="module">

        import {deleteResource} from '/js/delete-resource.js';
        import {Delete} from '/js/bulk-delete.js';


        $('#actividades-table').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            scrollX: true,
            ajax: "{{ route('tabla-prueba', ['materia' => $materia, 'periodo' => $periodo, 'competencia' => $competencia]) }}",
            columns: [
                { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                { data: 'nombre', name: 'nombre' },
                { data: 'descripcion', name: 'descripcion' },
                { data: 'tipo_nota.tipo', name: 'tipo_nota' },
                { data: 'action', name: 'acciones', orderable: false, searchable: false},
                {data: 'rates', name:'notas', orderable: false, searchable: false},
            ]
        });
        const bulk = new Delete('actividades-table', 'Actividad');
        $('#actividades-table').on('click', '.delete', function(){
            const competenciaId = $(this).data('id');
            deleteResource({
                controllerName: 'Actividades',
                resourceId: competenciaId,
                onSuccessCallback:() => {
                    $('#actividades-table').DataTable().ajax.reload();
                }
            });
        })
        
    </script>

</x-app-layout>