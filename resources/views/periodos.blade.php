<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Periodos') }}
        </h2>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link  href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="container m-2 p-2">
                        <table id="periodos-table" class="display">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Periodo</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        </table>
                </div>
            </div>
        </div>
    </div>

     <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
     <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

     <script type="module">
        $(document).ready(function() {
            $('#periodos-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route('periodos.data')}}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'periodo', name: 'periodo'},
                    {data: 'fecha_inicio', name: 'fecha_inicio'},
                    {data: 'fecha_fin', name: 'fecha_fin'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
     </script>

</x-app-layout>