<div>
    {{-- In work, do what you enjoy. --}}

    <link ef="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link  href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
   
        <select name="periodo" id="periodo" required>
            <option value="">Seleccione un periodo</option>
            @foreach ($periodos as $periodo)
                <option value="{{ $periodo->id }}">{{ $periodo->periodo }}</option>
            @endforeach
        </select>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="container">
                <div class="flex flex-row">
                </div>
                <table id="competenciasMaterias-table" class="display">
                    <thead>
                        <tr>
                            @can('administrar competencias')
                            <th><input type="checkbox" id="select-all" class="form-checkbox h-5 w-5 text-blue-600"></th>
                            <th>ID</th>
                            @endcan
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Periodo</th>
                            <th>Porcentaje</th>
                            <th>notas</th>
                            @can('administrar competencias')
                            <th>Acciones</th> {{-- Columna opcional para acciones --}}
                            @endcan
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            @can('administrar competencias')
                            <th></th>
                            <th>ID</th>
                            @endcan
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Periodo</th>
                            <th>Porcentaje</th>
                            <th>notas</th>
                            @can('administrar competencias')
                            <th>Acciones</th> {{-- Columna opcional para acciones --}}
                            @endcan
                        </tr>
                    </tfoot>
                </table>
            </div>
            </div>
        </div>
    </div>

    <script type="module">
        $(document).ready(function(){
            $('#competenciasMaterias-table').DataTable({
                processing:true,
                serverSide:true,
                responsive:true,
                scrollX:true,
                ajax: {
                    url: "{{ route('competenciasMateria') }}",
                    data: function(d) {
                        d.materia = "{{ $materia->id }}";
                        d.periodo = $("#periodo").val();
                    }
                },
                columns:[
                    @can('administrar competencias')
                    {data:'checkbox', name: 'checkbox', orderable:false, searchable:false},
                    {data:'id', name:'id'},
                    @endcan
                    {data:'nombre', name:'nombre'},
                    {data:'descripcion', name:'descripcion'},
                    {data:'periodo_id', name:'periodo_id'},
                    {data:'porcentaje', name:'porcentaje', render(data, type, row){
                        return `${data}%`;
                    }},
                    {data:'notas', name:'notas'},
                    @can('administrar competencias')
                    {data:'actions', name:'actions', orderable:false, searchable:false}, 
                    @endcan
                ]
            })

            $('#periodo').on('change', function(){
                $('#competenciasMaterias-table').DataTable().ajax.reload();
            })

            // Redireccionar al hacer clic en una fila
            $('#competenciasMaterias-table').on('click', 'tr', function () {
                // Evitar la redirección si se hizo clic en un checkbox o en un botón dentro de la fila
                if ($(event.target).is('input:checkbox') || $(event.target).is('button') || $(event.target).closest('button').length) {
                    return;
                }
                var data = $('#competenciasMaterias-table').DataTable().row(this).data();
                var periodo = $('#periodo').val();
                if (data && data.id) {
                    window.location.href = '/actividades/{{ $materia->id }}/'+ periodo +'/' + data.id;
                }
            });
        })


    </script>
</div>
