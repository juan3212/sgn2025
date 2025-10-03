<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Pagos') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="container p-12">
                <table class="display" id="estudiantesTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Documento</th>
                            <th>Estado de Pago</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>    
            </div>
        </div>
    </div>
<script>
    $(document).ready(function() {
        $('#estudiantesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/gestion-pagos/data',
                type: 'GET'
            },
            columns: [
                { data: 'nombre', name: 'nombre' },
                { data: 'apellido', name: 'apellido' },
                { data: 'nuip', name: 'nuip' },
                { data: 'estado_pago', name: 'estado_pago', searchable: false},
                { data: 'action', name: 'action' }
            ]    
        });

        function changeState(id) {
            fetch(`/gestion-pagos/change-state/${id}`, {
                method: 'get',
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        title: '¡Cambiado!',
                        text: 'Estado de pago cambiado correctamente',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                    $('#estudiantesTable').DataTable().ajax.reload();
                }
            });
        }

        $('#estudiantesTable').on('click', '.change-state', function () {
            const id = $(this).data('id');
            changeState(id);
        });

    });
    
</script>

</x-app-layout>

