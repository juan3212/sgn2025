<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link  href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">

</head>
<body>
    @livewire('pages.edit.competencias', ['competence'=>$id])
    
    <table id="tablaPrueba">
    <thead>
        <th>checkbox</th>
        <th>Nombre</th>
        <th>Grado</th>
        <th>Grupo</th>
        <th>Actions</th>
    </thead>
    <tfoot>
        <th>checkbox</th>
        <th>Nombre</th>
        <th>Grado</th>
        <th>Grupo</th>
        <th>Actions</th>
    </tfoot>

</table>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script type="module">

$(document).ready(function() {
    $('#tablaPrueba').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{route('tablaPrueba', ['id'=>$id])}}",
        columns: [
            {data: 'checkbox', name: 'checkbox'},
            {data: 'name', name: 'nombre'},
            {data: 'grade', name: 'grado'},
            {data: 'group', name: 'grupo'},
            {data: 'actions', name:'actions'},
        ]
    });
});
</script>

    
</body>
</html>