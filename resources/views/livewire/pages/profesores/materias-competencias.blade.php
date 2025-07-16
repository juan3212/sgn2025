<div>
    {{-- In work, do what you enjoy. --}}

    <div>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">
        <link href="//cdn.datatables.net/responsive/3.0.3/css/responsive.dataTables.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
        <script src="//cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.min.js"></script>

        <style>
            /* Estilos personalizados para mejorar la apariencia */
            .dataTables_wrapper {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            }
           
            /* Mejorar el header de la tabla */
            .dataTable thead th {
                @apply bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold text-sm;
                padding: 12px 8px;
                border: none;
            }
           
            /* Mejorar las filas */
            .dataTable tbody td {
                @apply border-b border-gray-200;
                padding: 12px 8px;
                vertical-align: middle;
            }
           
            .dataTable tbody tr:hover {
                @apply bg-gray-50 cursor-pointer;
            }
           
            /* Mejorar controles de DataTables */
            .dataTables_length select,
            .dataTables_filter input {
                @apply border border-gray-300 rounded-md px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200;
            }
           
            /* Responsive breakpoints personalizados */
            @media (max-width: 768px) {
                .dataTables_wrapper .dataTables_length,
                .dataTables_wrapper .dataTables_filter {
                    float: none;
                    text-align: left;
                    margin-bottom: 10px;
                }
               
                .dataTables_wrapper .dataTables_info,
                .dataTables_wrapper .dataTables_paginate {
                    float: none;
                    text-align: center;
                    margin-top: 10px;
                }
               
                /* Mejorar la descripción truncada en móviles */
                .description-cell {
                    max-width: 70%;
                    white-space: wrap;
                }
            }
           
            /* Estilo para los badges de porcentaje */
            .percentage-badge {
                @apply bg-gradient-to-r from-green-500 to-green-600 text-white px-2 py-1 rounded-full text-xs font-semibold;
            }
           
            /* Responsive details button */
            .dtr-details {
                @apply bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600;
            }
           
            /* Mejorar el aspecto del selector de período */
            .period-selector {
                @apply bg-white border-2 border-gray-200 rounded-lg px-4 py-3 text-sm shadow-sm transition-all duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200;
            }
            
            /* Estilo para la celda de descripción con ancho fijo */
            .description-cell {
                max-width: 70vw; /* 70% del ancho de la pantalla */
                word-wrap: break-word;
                word-break: break-word;
                white-space: normal;
                overflow-wrap: break-word;
                hyphens: auto;
            }
            
            /* Asegurar que la columna descripción tenga ancho fijo */
            .description-column {
                width: 40% !important;
                max-width: 40% !important;
            }
            
            /* Botón Ver Notas para modal responsive */
            .btn-ver-notas {
                @apply bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 mt-3;
            }
            
            /* Mejorar la apariencia del modal responsive */
            .dtr-modal {
                background-color: rgba(0, 0, 0, 0.5);
            }
            
            .dtr-modal-content {
                @apply bg-white rounded-lg shadow-xl max-w-lg mx-auto mt-20;
            }
            
            /* Estilo para el contenido del modal */
            .modal-details-content {
                @apply p-6;
            }
            
            /* Hover solo en desktop */
            @media (min-width: 769px) {
                .dataTable tbody tr:hover {
                    @apply bg-gray-50 cursor-pointer;
                }
            }
            
            /* Remover hover en móviles */
            @media (max-width: 768px) {
                .dataTable tbody tr:hover {
                    @apply bg-transparent cursor-default;
                }
            }
        </style>

        <!-- Header con selector de período -->
        <div class=" mb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <label for="periodo" class="text-sm font-medium text-gray-700 sm:self-center">
                            Período:
                        </label>
                        <select name="periodo" id="periodo" required class="period-selector">
                            <option value="">Seleccione un período</option>
                            @foreach ($periodos as $periodo)
                                <option value="{{ $periodo->id }}">{{ $periodo->periodo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <!-- Tabla responsive -->
                    <div class="overflow-x-auto">
                        <table id="competenciasMaterias-table" class="display responsive nowrap w-full">
                            <thead>
                                <tr>
                                    @can('administrar competencias')
                                    <th class="text-left">
                                        <input type="checkbox" id="select-all" class="form-checkbox h-4 w-4 text-blue-600 rounded focus:ring-blue-500 focus:ring-2">
                                    </th>
                                    <th class="text-left">ID</th>
                                    @endcan
                                    <th class="text-left">Nombre</th>
                                    <th class="text-left description-column">Descripción</th>
                                    <th class="text-left">Período</th>
                                    <th class="text-left">Porcentaje</th>
                                    <th class="text-left">Notas</th>
                                    @can('administrar competencias')
                                    <th class="text-left">Acciones</th>
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
                                    <th>Descripción</th>
                                    <th>Período</th>
                                    <th>Porcentaje</th>
                                    <th>Notas</th>
                                    @can('administrar competencias')
                                    <th>Acciones</th>
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
                // Función para detectar si es dispositivo móvil
                function isMobile() {
                    return window.innerWidth <= 768;
                }
                
                var table = $('#competenciasMaterias-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    scrollX: true,
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                    ajax: {
                        url: "{{ route('competenciasMateria') }}",
                        data: function(d) {
                            d.materia = "{{ $materia->id }}";
                            d.periodo = $("#periodo").val();
                        }
                    },
                    columns: [
                        @can('administrar competencias')
                        {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                        {data: 'id', name: 'id'},
                        @endcan
                        {data: 'nombre', name: 'nombre'},
                        { 
                            data: 'descripcion',
                            name: 'descripcion',
                            render: function(data, type, row) {
                                if (type === 'display' && data) {
                                    return '<div class="description-cell" title="' + data + '">' + data + '</div>';
                                }
                                return data || '';
                            }
                        },
                        {data: 'periodo_id', name: 'periodo_id'},
                        {
                            data: 'porcentaje',
                            name: 'porcentaje',
                            render: function(data, type, row) {
                                return '<span class="percentage-badge">' + data + '%</span>';
                            }
                        },
                        {data: 'notas', name: 'notas'},
                        @can('administrar competencias')
                        {data: 'actions', name: 'actions', orderable: false, searchable: false}
                        @endcan
                    ],
                    // Configuración responsive
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function(row) {
                                    return 'Detalles';
                                }
                            }),
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col, i) {
                                    return col ?
                                        '<tr>' +
                                            '<td class="font-semibold text-gray-700 py-2">' + col.title + ':</td>' +
                                            '<td class="py-2 pl-4">' + col.data + '</td>' +
                                        '</tr>' :
                                        '';
                                }).join('');
                                
                                // Agregar botón "Ver Notas" al final del modal
                                var rowData = api.row(rowIdx).data();
                                var periodo = $('#periodo').val();
                                
                                if (data && rowData && rowData.id && periodo) {
                                    data += '<tr>' +
                                        '<td colspan="2" class="py-4 text-center">' +
                                            '<button class="btn btn-xs btn-primary btn-ver-notas" onclick="window.location.href=\'/actividades/{{ $materia->id }}/' + periodo + '/' + rowData.id + '\'">' +
                                                'Ver Notas' +
                                            '</button>' +
                                        '</td>' +
                                    '</tr>';
                                }
                               
                                return data ? $('<table class="w-full"/>').append(data) : false;
                            }
                        }
                    },
                    // Configuración de columnas responsivas
                    columnDefs: [
                        @can('administrar competencias')
                        {
                            targets: [0, 1], // Checkbox e ID
                            className: 'never' // Nunca ocultar
                        },
                        {
                            targets: [2], // Nombre
                            className: 'all' // Siempre mostrar
                        },
                        {
                            targets: [3], // Descripción
                            className: 'min-tablet-l description-column', // Ocultar en móviles
                            width: '30%'
                        },
                        {
                            targets: [6], // Notas
                            className: 'min-tablet-l' // Ocultar en móviles
                        },
                        {
                            targets: [4], // Período
                            className: 'tablet-l' // Ocultar en móviles y tablets pequeñas
                        },
                        {
                            targets: [7], // Acciones
                            className: 'all' // Siempre mostrar
                        }
                        @else
                        {
                            targets: [0], // Nombre
                            className: 'all' // Siempre mostrar
                        },
                        {
                            targets: [1], // Descripción
                            className: 'min-tablet-l description-column', // Ocultar en móviles
                            width: '30%'
                        },
                        {
                            targets: [4], // Notas
                            className: 'all', // Ocultar en móviles
                            responsivePriority: 1
                        },
                        {
                            targets: [2], // Período
                            className: 'tablet-l' // Ocultar en móviles y tablets pequeñas
                        }
                        @endcan
                    ],
                    // Configuración adicional
                    dom: '<"flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4"<"mb-2 sm:mb-0"l><"mb-2 sm:mb-0"f>>rtip',
                    initComplete: function() {
                        // Personalizar controles después de inicialización
                        $('.dataTables_length select').addClass('ml-2');
                        $('.dataTables_filter input').addClass('ml-2').attr('placeholder', 'Buscar...');
                    }
                });

                // Filtro por período
                $('#periodo').on('change', function(){
                    table.ajax.reload();
                });

                @can('administrar competencias')
                // Funcionalidad de selección de todas las filas
                $('#select-all').on('click', function(){
                    var checkboxes = table.$('input[type="checkbox"]', {"page": "current"});
                    checkboxes.prop('checked', this.checked);
                });
                @endcan

                // Redireccionar al hacer clic en una fila SOLO en desktop
                $('#competenciasMaterias-table tbody').on('click', 'tr', function (e) {
                    // Solo permitir redirección en desktop
                    if (isMobile()) {
                        return; // No hacer nada en móviles
                    }
                    
                    // Evitar la redirección si se hizo clic en un checkbox o en un botón dentro de la fila
                    if ($(e.target).is('input:checkbox') || $(e.target).is('button') || $(e.target).closest('button').length) {
                        return;
                    }
                    
                    var data = table.row(this).data();
                    var periodo = $('#periodo').val();
                    
                    if (data && data.id && periodo) {
                        window.location.href = '/actividades/{{ $materia->id }}/'+ periodo +'/' + data.id;
                    }
                });
                
                // Manejar cambios de tamaño de ventana
                $(window).on('resize', function() {
                    table.columns.adjust().responsive.recalc();
                });
            });
        </script>
    </div>
</div>