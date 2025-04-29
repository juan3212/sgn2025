<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Materia;
use App\Models\Usuario;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'role:administrador'])
    ->name('dashboard');

Route::view('materias', 'materias')
    ->middleware(['auth'])
    ->name('materias');

Route::view('competencias', 'competencias')
    ->middleware(['auth', 'verified', 'role:administrador'])
    ->name('competencias');

Route::get('actividades/{materia}/{periodo}/{competencia}', function ($materia, $periodo, $competencia)  {
    return view('actividades', ['materia' => $materia, 'periodo' => $periodo, 'competencia' => $competencia]);
    }) 
    ->middleware(['auth'])
    ->name('actividades');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


#rutas para mostrar datatables

#usuarios
Route::get('usuarios', [App\Http\Controllers\UsuariosController::class, 'userData'])
    ->middleware(['auth'])
    ->name('user.data');

#materias
Route::get('materias/data', [App\Http\Controllers\MateriasController::class, 'data'])
    ->middleware(['auth'])
    ->name('materias.data');

#competencias
Route::get('competencias/data', [App\Http\Controllers\CompetenciasController::class, 'data'])
    ->middleware(['auth'])
    ->name('competencias.data');

#actividades
Route::get('actividades/data', [App\Http\Controllers\ActividadesController::class, 'data'])
    ->middleware(['auth'])
    ->name('actividades.data');
#notas
Route::get('notas/data/{actividad}', function ($actividad)  {
    return view('notas', ['actividad' => $actividad]);
    })
    ->middleware(['auth'])
    ->name('notas.data');

#guardar notas
Route::post('notas/save', [App\Http\Controllers\NotasController::class, 'save'])
    ->middleware(['auth']);
#edit competencias
Route::get('tablaCompetenciasEdit/{id}', [App\Livewire\Pages\Edit\Competencias::class, 'createTable'])
    ->name('tablaCompetenciasEdit');

#edit materias
Route::get('/edit/materias/{id}', function ($id)  {
    return view('edit.materias', ['id' => $id]);
    })
    ->middleware(['auth', 'role:administrador'])
    ->name('materias.edit');

#edit actividades
Route::get('/edit/actividades/{id}', function ($id)  {
    return view('edit.actividades', ['id' => $id]);
    })
    ->middleware(['auth', 'role:administrador'])
    ->name('actividades.edit');

#vistas tipo edit
Route::get('/edit/competencias/{id}', function ($id)  {
    return view('edit.competencias', ['id' => $id]);
    }) 
    ->middleware(['auth', 'role:administrador'])
    ->name('competencias.edit');


#mostrar formularios dinamicamente
Route::get('create-user', function(){
    return view('form-template', [
        'formComponent'=> 'forms.usuario-form',
        'formTitle' => 'Agregar usuarios',
    ]);
    })->middleware(['auth', 'role:administrador'])
        ->name('create-user');

Route::get('create-materia', function(){
    return view('form-template', [
        'formComponent'=> 'forms.materias-form',
        'formTitle' => 'Agregar materias',
    ]);
    })->middleware(['auth', 'role:administrador'])
        ->name('create-materia');

Route::get('create-competencia', function(){
    return view('form-template', [
        'formComponent'=> 'forms.competencias-form',
        'formTitle' => 'Agregar competencias',
    ]);
    })->middleware(['auth', 'role:administrador'])
        ->name('create-competencia');

Route::get('create-actividad/{materia}/{periodo}/{competencia}', function ($materia, $periodo, $competencia)  {
    return view('form-template', [
        'formComponent'=> 'forms.actividades-form',
        'formTitle' => 'Agregar actividades',
        'params'=> [
            'materia' => $materia,
            'periodo' => $periodo,
            'competencia' => $competencia,
        ],
    ]);
    })
    ->middleware(['auth', 'role:administrador'])
    ->name('create-actividad');

#Rutas tipo DELETE
Route::post('generic-delete', [App\Http\Controllers\DeleteController::class, 'delete'])
    ->middleware(['auth', 'role:administrador'])
    ->name('generic-delete');

Route::post('bulk-delete', [App\Http\Controllers\DeleteController::class, 'bulkDelete'])
    ->middleware(['auth', 'role:administrador'])
    ->name('bulk-delete');

#rutas Prueba
Route::view('prueba', 'pruebas') 
    ->middleware(['auth'])
    ->name('prueba');

Route::get('tabla-prueba/{materia}/{periodo}/{competencia}', [App\Http\Controllers\ActividadesController::class, 'data'])
    ->middleware(['auth'])
    ->name('tabla-prueba');

Route::get('tabla-notas', [App\Http\Controllers\NotasController::class, 'table'])
    ->name('tabla-notas');

require __DIR__.'/auth.php';

Route::get('pruebasql', function () {

        $students = Usuario::leftJoin('usuario_grado', function ($join) {
            $join->on('usuarios.id', '=', 'usuario_grado.usuario_id');
        })
        ->leftJoin('notas', function ($join) {
            $join->on('usuarios.id', '=', 'notas.estudiante_id');
        })
        ->where(function ($query) {
            $query->where('usuario_grado.grado_id', 7);
        })
        ->where(function ($query) {
            $query->where('usuario_grado.grupo_id', 1);
        })
        ->where(function ($query) {
            $query->where('notas.actividad_id', 4)
                  ->orWhereNull('notas.actividad_id'); // Para mantener el LEFT JOIN
        })
        ->select('usuarios.id', 'usuarios.nombre', 'usuarios.apellido', 'usuarios.nuip', 'notas.valor') // Selecciona solo los campos de la tabla usuarios
        ->distinct() // Evita duplicados si hay múltiples coincidencias en las tablas relacionadas
        ->get();
        return $students;
}
)
->middleware(['auth'])
->name('actividades');