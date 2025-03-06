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
    
<?php
    use App\Models\Materia;
    $materia = Materia::select('materias.id', 'base_materia.nombre_materia', 'materias.intensidad_horaria', 'usuarios.nombre', 'usuarios.apellido', 'grados.grado', 'grupos.grupo')
    ->join('base_materia', 'materias.materia_id', '=', 'base_materia.id')
    ->join('usuarios', 'materias.profesor_id', '=', 'usuarios.id')
    ->join('grados', 'materias.grado_id', '=', 'grados.id')
    ->join('grupos', 'materias.grupo_id', '=', 'grupos.id')
    ->get();


    $materias = $materia->map(function($materia){
        return [
            'id' => $materia->id,
            'materia' => $materia->nombre_materia,
            'intensidad_horaria' => $materia->intensidad_horaria,
            'profesor' => $materia->nombre.' '.$materia->apellido,
            'grado' => $materia->grado,
            'grupo' => $materia->grupo
        ];
    });

    dd($materias);
?>
    
</body>
</html>