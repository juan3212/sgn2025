<?php

namespace App\Imports;

use App\Models\Usuario;
use App\Models\Grado;
use App\Models\Grupo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Spatie\Permission\Models\Role;

class UsersImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected $roles;
    protected $grados;
    protected $grupos;

    public function __construct()
    {
        $this->roles = Role::all()->keyBy('name'); 
        $this->grados = Grado::all()->keyBy('grado'); 
        $this->grupos = Grupo::all()->keyBy('grupo');
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (!isset($row['documento_estudiante']) || !isset($row['nombre_estudiante'])) {
                continue;
            }

            DB::transaction(function () use ($row) {

                $estudiante = $this->crearUsuario(
                    $row['documento_estudiante'],
                    $row['nombre_estudiante'],
                    $row['apellido_estudiante'],
                    $row['email_estudiante'],
                    $row['contrasena_estudiante']
                );

                if ($rolEstudiante = $this->roles->get('estudiante')) {
                    $estudiante->assignRole($rolEstudiante);
                }

                $this->guardarInfoAdicional($estudiante->id, $row, 'estudiante');

                
                if (!empty($row['grado']) && !empty($row['grupo'])) {
                    $grado = $this->grados->get($row['grado']);
                    $grupo = $this->grupos->get($row['grupo']);

                    if ($grado && $grupo) {
                        $estudiante->grados()->syncWithoutDetaching([
                            $grado->id => ['grupo_id' => $grupo->id]
                        ]);
                    }
                }

                // --- 2. PROCESAR PADRE (Si existe) ---
                if (!empty($row['documento_padre'])) {
                    $padre = $this->crearUsuario(
                        $row['documento_padre'],
                        $row['nombre_padre'],
                        $row['apellido_padre'],
                        $row['email_padre'],
                        $row['contrasena_padre']
                    );
                    
                    if ($rolPadre = $this->roles->get('padre')) {
                        $padre->assignRole($rolPadre);
                    }

                    $this->guardarInfoAdicional($padre->id, $row, 'padre');
                    $this->relacionarPadreHijo($padre->id, $estudiante->id, 'Padre');
                }

                
                if (!empty($row['documento_madre'])) {
                    $madre = $this->crearUsuario(
                        $row['documento_madre'],
                        $row['nombre_madre'],
                        $row['apellido_madre'],
                        $row['email_madre'],
                        $row['contrasena_madre']
                    );

                    if ($rolPadre = $this->roles->get('padre')) { 
                        $madre->assignRole($rolPadre);
                    }

                    $this->guardarInfoAdicional($madre->id, $row, 'madre');
                    $this->relacionarPadreHijo($madre->id, $estudiante->id, 'Madre');
                }
            });
        }
    }

    private function crearUsuario($documento, $nombre, $apellido, $email, $password)
    {
        return Usuario::firstOrCreate(
            ['nuip' => $documento],
            [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'correo' => $email,
                'password_hash' => Hash::make($password), 
            ]
        );
    }

    private function guardarInfoAdicional($usuarioId, $row, $tipoPersona)
    {
        $telefono = $row['telefono_' . $tipoPersona] ?? null;
        $direccion = $row['direccion_' . $tipoPersona] ?? null;

        if ($telefono || $direccion) {
            DB::table('usuario_contacto')->updateOrInsert(
                ['usuario_id' => $usuarioId],
                [
                    'telefono' => $telefono,
                    'direccion' => $direccion,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        if ($tipoPersona === 'estudiante' && !empty($row['fecha_de_nacimiento_estudiante'])) {
            
            $existeInfo = DB::table('usuario_informacion')
                            ->where('usuario_id', $usuarioId)
                            ->exists();

            if ($existeInfo) {
                DB::table('usuario_informacion')
                    ->where('usuario_id', $usuarioId)
                    ->update([
                        'fecha_nacimiento' => $row['fecha_de_nacimiento_estudiante'],
                        'sexo'=> $row['sexo_estudiante'],
                        'updated_at' => now()
                    ]);
            } else {
                DB::table('usuario_informacion')->insert([
                    'usuario_id' => $usuarioId,
                    'fecha_nacimiento' => $row['fecha_de_nacimiento_estudiante'],
                    'departamento_nacimiento' => ' ',
                    'municipio_nacimiento' => ' ',   
                    'sexo'=> $row['sexo_estudiante'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    private function relacionarPadreHijo($padreId, $hijoId, $parentesco)
    {
        DB::table('usuario_has_child')->updateOrInsert(
            [
                'parent_id' => $padreId,
                'child_id' => $hijoId
            ],
            [
                'parentesco' => $parentesco,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }

    public function chunkSize(): int
    {
        return 500; // Reducimos un poco el chunk porque ahora procesamos más datos por fila
    }

    public function batchSize(): int
    {
        return 500;
    }
}