<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar competencias') }}
        </h2>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link  href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">
    </x-slot>
    
    <div class="p-6 max-w-7xl mx-auto">
        <!-- Contenedor de Dos Columnas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Columna izquierda: Componente Livewire -->
            <div class="bg-white rounded-lg shadow-md p-6">
                @livewire('pages.edit.competencias', ['competence' => $id])
            </div>
            <!-- Columna derecha: Tabla de Materias Asociadas -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden p-4">
                <h2 class="text-lg font-semibold mb-4">Materias Asociadas</h2>
                <div class="max-h-[500px] overflow-y-auto border border-gray-200 rounded-lg">
                    <table id="editCompetences-table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grupo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Checkbox</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grupo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </tfoot>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Los datos se cargarán dinámicamente mediante DataTables -->
                        </tbody>
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

    $(document).ready(function() {
        $('#editCompetences-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('tablaCompetenciasEdit', ['id'=>$id])}}",
            columns: [
                {data: 'checkbox', name: 'checkbox', sortable: false, searchable: false},
                {data: 'name', name: 'nombre'},
                {data: 'grade', name: 'grado'},
                {data: 'group', name: 'grupo'},
                {data: 'actions', name:'actions', sortable: false, searchable: false},
            ]
        });
    });

    $('#editCompetences-table').on('click', '.delete', function(){
        const competenciaId = "{{$id}}";
        const subjectId = $(this).data('id');
        deleteResource({
            controllerName: 'CompetenciasService',
            functionName: 'detachMateria',
            resourceId: 
            {
                'competencia_id': competenciaId,
                'materia_id': subjectId
            },
            reloadTable: true,
            tableId: 'editCompetences-table'

        });
    });

    const bulk = new Delete('editCompetences-table', 'CompetenciasService');
    </script>


</x-app-layout>