<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\UsuarioContacto;
use App\Models\UsuarioEstadoLaboral;

class AcudienteForm extends Form
{
    public ?Usuario $usuarioModel = null;
    
    // --- INFO BÁSICA ---
    public $acudienteId = null; // Para saber si editamos o creamos

    #[Validate('required')]
    public $nombre = '';
    
    #[Validate('required')]
    public $apellido = '';
    
    #[Validate('required')]
    public $tipo_documento = '';
    
    #[Validate('required|numeric')]
    public $numero_documento = '';
    
    #[Validate('required')]
    public $parentesco = '';

    // --- UBICACIÓN (Usando Modelable en el hijo) ---
    #[Validate('required | array')]
    public $valueUbicacion = [];

    // --- INFO CONTACTO ---
    
    public $email = '';
    
    
    public $telefono = '';
    
    
    public $direccion = '';

    // --- INFO FINANCIERA ---
    public $estado_laboral = '';
    public $empresa = '';

    // --- CONTACTOS ---
    public $contacto = [];


    // --- LÓGICA DE BÚSQUEDA ---
    public function buscarPorDocumento()
    {
        $this->validate(['numero_documento' => 'required|numeric']);

        $usuario = Usuario::where('nuip', $this->numero_documento)->first();

        if ($usuario) {
            $this->usuarioModel = $usuario;
            $this->acudienteId = $usuario->id;
            $this->nombre = $usuario->nombre;
            $this->apellido = $usuario->apellido;
           
            
            // Cargar ubicación de expedición si existe
            $infoDoc = DB::table('usuario_info_documento')->where('usuario_id', $usuario->id)->first();
            if ($infoDoc) {
                $this->valueUbicacion['departamento'] = $infoDoc->departamento_expedicion;
                $this->valueUbicacion['municipio'] = $infoDoc->municipio_expedicion;
                 $this->tipo_documento = $infoDoc->tipo_documento;
            }

            // Cargar contacto
            $contacto = UsuarioContacto::where('usuario_id', $usuario->id)->get();
            if ($contacto) {
                $this->contacto = $contacto;
            }
            
            // Cargar financiera
            $financiera = UsuarioEstadoLaboral::where('usuario_id', $usuario->id)->first();
            if ($financiera) {
                $this->estado_laboral = $financiera->estado_laboral;
                $this->empresa = $financiera->empresa;
            }
            //parentesco
            $parentesco = DB::table('usuario_has_child')->where('parent_id', $usuario->id)->first();
            if ($parentesco) {
                $this->parentesco = $parentesco->parentesco;
            }
            return true; // Encontrado
        }
        
        return false; // No encontrado
    }

    // --- GUARDADO GENERAL ---
    public function store($estudianteId)
    {

        $this->validate();

        DB::transaction(function () use ($estudianteId) {
            // 1. Guardar/Actualizar Usuario
            $usuario = Usuario::updateOrCreate(
                ['nuip' => $this->numero_documento],
                [
                    'nombre' => $this->nombre,
                    'apellido' => $this->apellido,
                    'tipo_documento' => $this->tipo_documento,
                    'password_hash' => $this->usuarioModel ? $this->usuarioModel->password_hash : Hash::make($this->numero_documento)
                ]
            );
            
            $usuario->assignRole('Padre');

            // 2. Info Documento (Ubicación)
            DB::table('usuario_info_documento')->updateOrInsert(
                ['usuario_id' => $usuario->id],
                [
                    'tipo_documento' => $this->tipo_documento,
                    'numero_documento' => $this->numero_documento,
                    'departamento_expedicion' => $this->valueUbicacion['departamento'],
                    'municipio_expedicion' => $this->valueUbicacion['municipio'],
                ]
            );

            // 3. Relación con Estudiante
            DB::table('usuario_has_child')->updateOrInsert(
                ['parent_id' => $usuario->id, 'child_id' => $estudianteId],
                ['parentesco' => $this->parentesco]
            );

           
            // 4. Contacto
            if(count($this->contacto) <= 0){
            $this->validate([
                'email' => 'required|email',
                'telefono' => 'required',
                'direccion' => 'required',
            ]);
            
                UsuarioContacto::updateOrCreate(
                    ['usuario_id' => $usuario->id],
                    [
                        'email' => $this->email,
                        'telefono' => $this->telefono,
                        'direccion' => $this->direccion
                    ]
                );
            }

            // 5. Financiera (Solo si es padre/madre o se llenaron datos)
            if ($this->estado_laboral) {
                UsuarioEstadoLaboral::updateOrCreate(
                    ['usuario_id' => $usuario->id],
                    [
                        'estado_laboral' => $this->estado_laboral,
                        'empresa' => $this->empresa
                    ]
                );
            }
            
        });
       

        $this->reset(); // Limpiar formulario para el siguiente
    }

    public function guardarContacto()
    {
        UsuarioContacto::insert(
           [
                'usuario_id' => $this->acudienteId,
                'email' => $this->email,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion
            ]
        );

        $this->reset('contacto');
        $contacto = UsuarioContacto::where('usuario_id', $this->acudienteId)->get();
        if ($contacto) {
            $this->contacto = $contacto;
        }
    }

    public function quitarContacto($id)
    {
        DB::table('usuario_contacto')
            ->where('id', $id)
            ->delete();
        
        $contacto = UsuarioContacto::where('usuario_id', $this->acudienteId)->get();
        if ($contacto) {
            $this->contacto = $contacto;
        }
    }

    public function getUploadProperties(): array
    {
        return [
            'usuarioId' => $this->acudienteId,
            'estadoLaboral' => $this->estado_laboral,
            'usuario' => 'acudiente',
            'usuarioNuip' => $this->numero_documento,
        ];
    }
}