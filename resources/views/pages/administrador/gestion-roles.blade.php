<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <nav class="flex items-center space-x-6 border-b border-gray-200">
                <a href="{{ route('gestion-roles') }}" class="px-3 py-2 text-gray-800 border-b border-gray-300 hover:text-gray-600 hover:border-b hover:border-gray-300">Roles</a>
                <a href="{{ route('gestion-permisos') }}" class="px-3 py-2 text-gray-500 hover:text-gray-800 hover:border-b hover:border-gray-300">Permisos</a>
            </nav>
            <x-botones-header :createRoute="route('create-role')"></x-botones-header>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8 ">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <table class="display" id="rolesTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Permisos</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>    
            </div>
        </div>
    </div>
    <script type="module">

        import {deleteResource} from "../js/delete-resource.js";


        
        $(document).ready(function() {
            $('#rolesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/gestion-roles/data',
                    type: 'GET'
                },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'permissions', name: 'permissions' },
                    { data: 'action', name: 'action' },
                    { data: 'delete', name: 'delete' }
                ]    
            });
        });

        $('#rolesTable').on('click', '.delete', function() {
            var id = $(this).data('id');
            deleteResource({
                controllerName: 'RolesyPermisos',
                functionName: 'deleteRole',
                resourceId: id,
                confirmMessage: '¿Estás seguro de que quieres eliminar este rol?',
                successMessage: 'Rol eliminado exitosamente',
                reloadTable: true,
                tableId: 'rolesTable',
                isService: true
            });
        });
    </script>
</x-app-layout>
