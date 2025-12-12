<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Informe Facturación Electrónica') }}
        </h2>
        
        <link href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.min.css" rel="stylesheet">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <table class="table" id="facturacion_table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Nombre estudiante</th>
                            <th>Apellido estudiante</th>
                            <th>Grado</th>
                            <th>NUIP estudiante</th>
                            <th>Nombre del Contacto</th>
                            <th>Apellido del Contacto</th>
                            <th>Telefono del Contacto</th>
                            <th>Correo del Contacto</th>
                            <th>Direccion</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script type="module">
    // Import CSS/JS for DataTables Buttons if using module system or just rely on CDNs if not managed by Vite perfectly yet for these specific plugins
    // Since we are in a blade file and using CDNs for quickness as per user's style in usuarios.blade.php (although they imported deletedResource from local js)
    // I will append the scripts dynamically or assume they are loaded.
    // Actually, I should probably push them or correct the imports.
    // user's usuarios.blade.php had: <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    
</script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>

<script type="module">
    $(document).ready(function() {
        $('#facturacion_table').DataTable({
            processing: true,
            serverSide: true,
            layout: {
                topStart: {
                    buttons: [
                        {
                            text: 'Exportar Todo (Excel)',
                            action: function (e, dt, node, config) {
                                window.location.href = "{{ route('informes.facturacion-electronica.export') }}";
                            },
                            className: 'dt-button buttons-excel buttons-html5'
                        }
                    ]
                }
            },
            ajax: {
                url: "{{ route('informes.facturacion-electronica') }}",
                type: 'GET',
            },
            columns: [
                { data: 'fecha_facturacion', name: 'fecha_facturacion' },
                { data: 'nombre', name: 'nombre' },
                { data: 'apellido', name: 'apellido' },
                { data: 'grado', name: 'grado' },
                { data: 'nuip', name: 'nuip' },
                { data: 'nombre_acudiente', name: 'nombre_acudiente' },
                { data: 'apellido_acudiente', name: 'apellido_acudiente' },
                { data: 'correo', name: 'correo' },
                { data: 'telefono', name: 'telefono' },
                { data: 'direccion', name: 'direccion' },
            ],
        });
    });
</script>


</x-app-layout>
