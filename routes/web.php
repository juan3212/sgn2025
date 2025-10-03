<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Services\getUserDataService;
use App\Models\NotaFinalMateria;


Route::get('/', [App\Http\Controllers\MateriasController::class, 'render'])
->middleware(['auth', 'verified', 'paymentStatus'])
->name('home');

Route::get('dashboard', [App\Http\Controllers\MateriasController::class, 'render'])
    ->middleware(['auth', 'verified', 'paymentStatus'])
    ->name('dashboard');

Route::get('usuarios', [App\Http\Controllers\UsuariosController::class, 'render'])
    ->middleware(['auth', 'permission:administrar usuarios'])
    ->name('usuarios');

Route::get('competencias', [App\Http\Controllers\CompetenciasController::class, 'render'])
    ->middleware(['auth', 'verified', 'permission:administrar competencias'])
    ->name('competencias');

# boletines
Route::get('boletin/{estudianteID}', [App\Http\Controllers\estudiantes\boletinesController::class, 'render'])
    ->middleware(['auth'])
    ->name('boletin');

Route::view('buscar-boletin', 'pages.profesores.buscar-boletin')
    ->middleware(['auth', 'permission:administrar materias'])
    ->name('buscar-boletin');

    #competencias de materias
Route::get('materia/{materia}', [App\Http\Controllers\MateriasCompetenciasController::class, 'render'])
    ->middleware(['auth'])
    ->name('materia');

Route::get('actividades/{materia}/{periodo}/{competencia}', [App\Http\Controllers\ActividadesController::class, 'render']) 
    ->middleware(['auth'])
    ->name('actividades');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

    #periodos
Route::view('periodos', 'periodos')
    ->middleware(['auth', 'permission:administrar periodos'])
    ->name('periodos');

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

#notas por materia y periodo para profesores
Route::get('notas-estudiantes', [App\Http\Controllers\profesores\NotasEstudiantes::class, 'dataOfEstudiante'])
    ->middleware(['auth'])
    ->name('notas-estudiantes');

#actividades
Route::get('actividades/data', [App\Http\Controllers\ActividadesController::class, 'data'])
    ->middleware(['auth'])
    ->name('actividades.data');
#notas
Route::get('notas/{actividad_id}', [App\Http\Controllers\NotasController::class, 'render'])
    ->middleware(['auth'])
    ->name('notas.data');

#notas actividades
Route::get('notas-actividades/{materia_id}/{competencia_id}', [App\Http\Controllers\notasActividadesController::class, 'render'])
    ->middleware(['auth'])
    ->name('notas-actividades');

#periodos
Route::get('periodos/data', [App\Http\Controllers\PeriodosController::class, 'getPeriodos'])
    ->middleware(['auth', 'permission:administrar usuarios'])
    ->name('periodos.data');


#guardar notas
Route::post('notas/save', [App\Http\Controllers\NotasController::class, 'save'])
    ->middleware(['auth', 'permission:administrar notas']);

#guardar notas de varias actividades
Route::post('notas/saveNotasActividades', [App\Http\Controllers\notasActividadesController::class, 'saveNotasActividades'])    
    ->middleware(['auth', 'permission:administrar notas']);

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

Route::get('create-periodo/{id}', function ($id){
    return view('form-template', [
        'formComponent'=> 'forms.periodos-form',
        'formTitle' => 'Agregar periodos',
        'params'=> [
            'id' => $id,
        ],
    ]);
    })->middleware(['auth', 'permission:administrar periodos'])
    ->name('create-periodo');

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
Route::view('prueba', 'pages.administrador.gestion-pagos')
    ->name('prueba');


#rutas Gestion Roles y Permisos
Route::get('gestion-roles/data', [App\Http\Controllers\administrador\RolesyPermisosController::class, 'rolesTable'])
    ->middleware(['auth'])
    ->name('gestion-roles.data');

Route::get('gestion-permisos/data', [App\Http\Controllers\administrador\RolesyPermisosController::class, 'permissionTable'])
    ->middleware(['auth'])
    ->name('gestion-permisos.data');

Route::view('gestion-roles', 'pages.administrador.gestion-roles')
    ->middleware(['auth', 'permission:administrar roles'])
    ->name('gestion-roles');

Route::view('gestion-permisos', 'pages.administrador.gestion-permisos')
    ->middleware(['auth', 'permission:administrar roles'])
    ->name('gestion-permisos');

Route::get('create-role/{role?}', function($role = null){
    return view('form-template', [
        'formComponent'=> 'forms.administrador.roles-form',
        'formTitle' => 'Agregar roles',
        'params'=> [
            'role' => $role,
        ],
    ]);
    })->middleware(['auth', 'permission:administrar roles'])
        ->name('create-role');
    
#rutas Gestion Pagos
Route::view('gestion-pagos', 'pages.administrador.gestion-pagos')
    ->middleware(['auth', 'permission:administrar pagos'])
    ->name('gestion-pagos');
Route::get('gestion-pagos/data', [App\Http\Controllers\administrador\GestionPagosController::class, 'data'])
    ->middleware(['auth'])
    ->name('gestion-pagos.data');

Route::get('gestion-pagos/change-state/{id}', [App\Http\Controllers\administrador\GestionPagosController::class, 'cambiarEstadoPago'])
    ->middleware(['auth', 'permission:administrar pagos'])
    ->name('gestion-pagos.change-state');

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

