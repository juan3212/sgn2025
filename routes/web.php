<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Materia;
use App\Models\Usuario;
use App\Http\Controllers\Estudiantes\calcularNotasController;
use App\Services\getUserDataService;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('usuarios', 'usuarios')
    ->middleware(['auth', 'permission:administrar usuarios'])
    ->name('usuarios');

Route::view('competencias', 'competencias')
    ->middleware(['auth', 'verified', 'permission:administrar competencias'])
    ->name('competencias');

Route::get('boletin/{estudianteID}', [App\Http\Controllers\estudiantes\boletinesController::class, 'render'])
    ->middleware(['auth'])
    ->name('boletin');
Route::get('pruebaBoletin/{estudianteID}', function ($estudianteID)  {
    $user = new getUserDataService;
    $user = $user->getUserDataFromID($estudianteID);
    
    return $user;
    })
    ->middleware(['auth'])
    ->name('pruebaBoletin');
    #competencias de materias
Route::get('materia/{materia}', function ($materia)  {
    return view('materias-competencias', ['materia' => $materia]);
    })
    ->middleware(['auth'])
    ->name('materia');

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
Route::get('usuarios/data', [App\Http\Controllers\UsuariosController::class, 'userData'])
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

#competencias de materia
Route::get('competenciasMateria', [App\Http\Controllers\MateriasCompetenciasController::class, 'data'])
    ->middleware(['auth'])
    ->name('competenciasMateria');

#actividades
Route::get('actividades/data', [App\Http\Controllers\ActividadesController::class, 'data'])
    ->middleware(['auth'])
    ->name('actividades.data');
#notas
Route::get('notas/{actividad_id}', [App\Http\Controllers\NotasController::class, 'render'])
    ->middleware(['auth'])
    ->name('notas.data');


#guardar notas
Route::post('notas/save', [App\Http\Controllers\NotasController::class, 'save'])
    ->middleware(['auth']);
#edit competencias
Route::get('tablaCompetenciasEdit/{id}', [App\Livewire\Pages\Edit\Competencias::class, 'createTable'])
    ->middleware(['auth', 'permission:administrar competencias'])
    ->name('tablaCompetenciasEdit');

#edit materias
Route::get('/edit/materias/{id}', function ($id)  {
    return view('edit.materias', ['id' => $id]);
    })
    ->middleware(['auth', 'permission:administrar materias'])
    ->name('materias.edit');

#edit actividades
Route::get('/edit/actividades/{id}', function ($id)  {
    return view('edit.actividades', ['id' => $id]);
    })
    ->middleware(['auth', 'permission:administrar actividades'])
    ->name('actividades.edit');

#vistas tipo edit
Route::get('/edit/competencias/{id}', function ($id)  {
    return view('edit.competencias', ['id' => $id]);
    }) 
    ->middleware(['auth', 'permission:administrar competencias'])
    ->name('competencias.edit');


#mostrar formularios dinamicamente
Route::get('create-user', function(){
    return view('form-template', [
        'formComponent'=> 'forms.usuario-form',
        'formTitle' => 'Agregar usuarios',
    ]);
    })->middleware(['auth', 'permission:administrar usuarios'])
        ->name('create-user');

Route::get('create-materia', function(){
    return view('form-template', [
        'formComponent'=> 'forms.materias-form',
        'formTitle' => 'Agregar materias',
    ]);
    })->middleware(['auth', 'permission:administrar materias'])
        ->name('create-materia');

Route::get('create-competencia', function(){
    return view('form-template', [
        'formComponent'=> 'forms.competencias-form',
        'formTitle' => 'Agregar competencias',
    ]);
    })->middleware(['auth', 'permission:administrar competencias'])
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
    ->middleware(['auth', 'permission:administrar actividades'])
    ->name('create-actividad');

#Rutas tipo DELETE
Route::post('generic-delete', [App\Http\Controllers\DeleteController::class, 'delete'])
    ->middleware(['auth', 'permission:administrar materias'])
    ->name('generic-delete');

Route::post('bulk-delete', [App\Http\Controllers\DeleteController::class, 'bulkDelete'])
    ->middleware(['auth', 'permission:administrar materias'])
    ->name('bulk-delete');

#rutas Prueba
Route::view('prueba', 'pruebas')
    ->middleware(['auth'])
    ->name('prueba');

Route::view('landing', 'landing')
    ->name('landing');

Route::view('fotos', 'fotos')
    ->name('fotos');

Route::get('tabla-prueba/{materia}/{periodo}/{competencia}', [App\Http\Controllers\ActividadesController::class, 'data'])
    ->middleware(['auth'])
    ->name('tabla-prueba');

Route::get('tabla-notas', [App\Http\Controllers\NotasController::class, 'table'])
    ->middleware(['auth'])
    ->name('tabla-notas');


# rutas para importacion de usuarios
Route::get('usuarios/import', [App\Http\Controllers\UsuariosController::class, 'importForm'])->middleware(['auth', 'permission:administrar usuarios'])->name('users.import.form');
Route::post('usuarios/import', [App\Http\Controllers\UsuariosController::class, 'import'])->middleware(['auth', 'permission:administrar usuarios'])->name('users.import');

require __DIR__.'/auth.php';

