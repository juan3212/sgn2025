<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link  href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">

    <style>
        .editable-cell {
            display: block;
            width: 100%;
            height: 100%;
            padding: 5px;
            box-sizing: border-box;
        }

        table.dataTable td {
            min-height: 40px;
            vertical-align: middle;
        }
    </style>

</head>
<body>


<table id="notas-table">
        <thead>
            <tr>
                <th>NUIP</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Notas</th>
            </tr>
            <tbody></tbody>
        </thead>
    </table>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link  href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    <script type="module">

        import {deleteResource} from '/js/delete-resource.js';
        import {Delete} from '/js/bulk-delete.js';


        $('#notas-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('tabla-notas') }}",
            columns: [
                { data: 'nuip', name: 'nuip' },
                { data: 'nombre', name: 'nombre' },
                { data: 'apellido', name: 'apellido'},
                { data: 'rates', name:'notas'},
            ]
        });
    </script>
 
    <script src="/js/notas.js"></script>

    
</body>
</html>