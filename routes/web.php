<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Materia;

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

require __DIR__.'/auth.php';
