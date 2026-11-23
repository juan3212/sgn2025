<?php

namespace App\Livewire\Forms;

use App\Models\Usuario;
use App\Models\UsuarioContacto;
use App\Models\UsuarioEstadoLaboral;
use App\Services\MatriculaValidationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Form;
use Spatie\Permission\Models\Role;

class AcudienteForm extends Form
{
    public ?Usuario $usuarioModel = null;

    // --- INFO BÁSICA ---
    public $acudienteId = null;

    public $nombre = '';
    public $apellido = '';
    public $tipo_documento = '';
    public $numero_documento = '';
    public $parentesco = '';

    // --- UBICACIÓN (Usando Modelable en el hijo) ---
    public $valueUbicacion = [];

    // --- INFO CONTACTO ---
    public $email = '';
    public $telefono = '';
    public $direccion = '';

    // --- INFO FINANCIERA ---
    public $estado_laboral = '';
    public $empresa = '';

    // --- CONTACTOS GUARDADOS ---
    public $contacto = [];

    // --- DOCUMENTOS (nuevos archivos temporales) ---
    public $documento_identidad = null;
    public $certificado_laboral = null;
    public $camara_comercio = null;
    public $certificado_ingresos = null;

    // --- URLs DE DOCUMENTOS EXISTENTES EN BD ---
    public $documento_identidad_url = null;
    public $certificado_laboral_url = null;
    public $camara_comercio_url = null;
    public $certificado_ingresos_url = null;

    // --- LÓGICA DE BÚSQUEDA ---
    public function buscarPorDocumento($estudianteId = null)
    {
        $this->validate([
            'numero_documento' => 'required|numeric',
        ], [
            'numero_documento.required' => 'Ingrese el número de documento para buscar.',
            'numero_documento.numeric' => 'El número de documento debe ser numérico.',
        ]);

        $usuario = Usuario::where('nuip', $this->numero_documento)->first();

        if ($usuario) {
            $this->usuarioModel = $usuario;
            $this->acudienteId = $usuario->id;
            $this->nombre = $usuario->nombre ?? '';
            $this->apellido = $usuario->apellido ?? '';

            // Cargar ubicación de expedición si existe
            $infoDoc = DB::table('usuario_info_documento')->where('usuario_id', $usuario->id)->first();
            if ($infoDoc) {
                $this->valueUbicacion = [
                    'departamento' => $infoDoc->departamento_expedicion ?? '',
                    'municipio' => $infoDoc->municipio_expedicion ?? '',
                ];
                $this->tipo_documento = $infoDoc->tipo_documento ?? '';
            } else {
                $this->valueUbicacion = [];
            }

            // Cargar contacto
            $contacto = UsuarioContacto::where('usuario_id', $usuario->id)->get();
            $this->contacto = $contacto ?: [];
            $this->email = '';
            $this->telefono = '';
            $this->direccion = '';

            // Cargar financiera
            $financiera = UsuarioEstadoLaboral::where('usuario_id', $usuario->id)->first();
            if ($financiera) {
                $this->estado_laboral = $financiera->estado_laboral ?? '';
                $this->empresa = $financiera->empresa ?? '';
            } else {
                $this->estado_laboral = '';
                $this->empresa = '';
            }

            // Cargar parentesco (priorizar el vínculo con este estudiante)
            $parentescoQuery = DB::table('usuario_has_child')->where('parent_id', $usuario->id);
            $parentesco = $estudianteId
                ? (clone $parentescoQuery)->where('child_id', $estudianteId)->first()
                : null;
            if (!$parentesco) {
                $parentesco = $parentescoQuery->first();
            }
            $this->parentesco = $parentesco ? ($parentesco->parentesco ?? '') : '';

            // Cargar documentos existentes en BD
            $this->cargarDocumentosExistentes($usuario->id);
            $this->reset(['documento_identidad', 'certificado_laboral', 'camara_comercio', 'certificado_ingresos']);

            return true;
        }

        // Limpiar datos de una búsqueda anterior manteniendo el documento digitado
        $numero = $this->numero_documento;
        $tipo = $this->tipo_documento;
        $this->reset();
        $this->numero_documento = $numero;
        $this->tipo_documento = $tipo;

        return false;
    }

    public function cargarDocumentosExistentes(int|string $usuarioId): void
    {
        $archivos = DB::table('usuario_archivos')
            ->where('usuario_id', $usuarioId)
            ->pluck('ruta_archivo', 'nombre_archivo');

        $this->documento_identidad_url = $archivos->get('documento_identidad');
        $this->certificado_laboral_url = $archivos->get('certificado_laboral');
        $this->camara_comercio_url = $archivos->get('camara_comercio');
        $this->certificado_ingresos_url = $archivos->get('certificado_ingresos');
    }

    // --- GUARDADO GENERAL ---
    public function store($estudianteId)
    {
        // Sincronizar referencias cuando el NUIP cambia o no se usó el botón "Buscar"
        $usuarioExistente = Usuario::where('nuip', trim((string) $this->numero_documento))->first();
        if ($usuarioExistente && $usuarioExistente->id != $this->acudienteId) {
            $this->usuarioModel = $usuarioExistente;
            $this->acudienteId = $usuarioExistente->id;
            $this->contacto = UsuarioContacto::where('usuario_id', $usuarioExistente->id)->get();
            $this->cargarDocumentosExistentes($usuarioExistente->id);
        } elseif (!$usuarioExistente && $this->acudienteId) {
            $this->usuarioModel = null;
            $this->acudienteId = null;
            $this->contacto = [];
            $this->documento_identidad_url = null;
            $this->certificado_laboral_url = null;
            $this->camara_comercio_url = null;
            $this->certificado_ingresos_url = null;
        }

        $rules = [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'tipo_documento' => 'required|in:TI,CC,CE,PPT',
            'numero_documento' => 'required|numeric',
            'parentesco' => 'required|in:Padre,Madre,Padre/Madre_cabeza_de_familia,Hermano(a),Tio(a),Abuelo(a),Otro',
            'valueUbicacion.departamento' => 'required',
            'valueUbicacion.municipio' => 'required',
            'documento_identidad' => empty($this->documento_identidad_url)
                ? 'required|file|max:10240|mimes:pdf,jpg,jpeg,png'
                : 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png',
        ];

        $messages = [
            'nombre.required' => 'El nombre del acudiente es obligatorio.',
            'apellido.required' => 'El apellido del acudiente es obligatorio.',
            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'tipo_documento.in' => 'Seleccione un tipo de documento válido.',
            'numero_documento.required' => 'El número de documento es obligatorio.',
            'numero_documento.numeric' => 'El número de documento debe ser numérico.',
            'parentesco.required' => 'El parentesco es obligatorio.',
            'parentesco.in' => 'Seleccione un parentesco válido.',
            'valueUbicacion.departamento.required' => 'El departamento de expedición es obligatorio.',
            'valueUbicacion.municipio.required' => 'El municipio de expedición es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'direccion.required' => 'La dirección de residencia es obligatoria.',
            'estado_laboral.required' => 'El estado laboral es obligatorio.',
            'estado_laboral.in' => 'Seleccione un estado laboral válido.',
            'empresa.required' => 'El nombre de la empresa es obligatorio para empleados.',
            'documento_identidad.required' => 'Debe subir el documento de identidad del acudiente.',
            'documento_identidad.file' => 'El documento de identidad debe ser un archivo válido.',
            'documento_identidad.max' => 'El documento de identidad no debe superar los 10 MB.',
            'documento_identidad.mimes' => 'El documento de identidad debe ser PDF, JPG, JPEG o PNG.',
            'certificado_laboral.required' => 'Debe subir el certificado laboral.',
            'certificado_laboral.file' => 'El certificado laboral debe ser un archivo válido.',
            'certificado_laboral.max' => 'El certificado laboral no debe superar los 10 MB.',
            'certificado_laboral.mimes' => 'El certificado laboral debe ser PDF, JPG, JPEG o PNG.',
            'camara_comercio.required' => 'Debe subir la cámara de comercio.',
            'camara_comercio.file' => 'La cámara de comercio debe ser un archivo válido.',
            'camara_comercio.max' => 'La cámara de comercio no debe superar los 10 MB.',
            'camara_comercio.mimes' => 'La cámara de comercio debe ser PDF, JPG, JPEG o PNG.',
            'certificado_ingresos.required' => 'Debe subir el certificado de ingresos.',
            'certificado_ingresos.file' => 'El certificado de ingresos debe ser un archivo válido.',
            'certificado_ingresos.max' => 'El certificado de ingresos no debe superar los 10 MB.',
            'certificado_ingresos.mimes' => 'El certificado de ingresos debe ser PDF, JPG, JPEG o PNG.',
        ];

        $tieneContactoCompletoGuardado = collect($this->contacto)->contains(function ($item) {
            return !empty(trim((string) ($item->telefono ?? '')))
                && !empty(trim((string) ($item->email ?? '')))
                && filter_var($item->email, FILTER_VALIDATE_EMAIL) !== false
                && !empty(trim((string) ($item->direccion ?? '')));
        });

        $diligencioContactoNuevo = trim((string) $this->email) !== ''
            || trim((string) $this->telefono) !== ''
            || trim((string) $this->direccion) !== '';

        if (!$tieneContactoCompletoGuardado || $diligencioContactoNuevo) {
            $rules['email'] = 'required|email|max:255';
            $rules['telefono'] = 'required|string|max:50';
            $rules['direccion'] = 'required|string|max:255';
        }

        $requiereInfoFinanciera = in_array(
            $this->parentesco,
            MatriculaValidationService::PARENTESCOS_FINANCIEROS,
            true
        );

        if ($requiereInfoFinanciera) {
            $rules['estado_laboral'] = 'required|in:Empleado,Independiente,No trabaja';

            if ($this->estado_laboral === 'Empleado') {
                $rules['empresa'] = 'required|string|max:255';
                $rules['certificado_laboral'] = empty($this->certificado_laboral_url)
                    ? 'required|file|max:10240|mimes:pdf,jpg,jpeg,png'
                    : 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png';
            } elseif ($this->estado_laboral === 'Independiente') {
                $rules['camara_comercio'] = empty($this->camara_comercio_url)
                    ? 'required|file|max:10240|mimes:pdf,jpg,jpeg,png'
                    : 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png';
                $rules['certificado_ingresos'] = empty($this->certificado_ingresos_url)
                    ? 'required|file|max:10240|mimes:pdf,jpg,jpeg,png'
                    : 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png';
            }
        }

        $this->validate($rules, $messages);

        // Verificar autorización para subida de documentos si hay archivos nuevos
        $archivosParaSubir = array_filter([
            'documento_identidad' => $this->documento_identidad,
            'certificado_laboral' => ($requiereInfoFinanciera && $this->estado_laboral === 'Empleado') ? $this->certificado_laboral : null,
            'camara_comercio' => ($requiereInfoFinanciera && $this->estado_laboral === 'Independiente') ? $this->camara_comercio : null,
            'certificado_ingresos' => ($requiereInfoFinanciera && $this->estado_laboral === 'Independiente') ? $this->certificado_ingresos : null,
        ]);

        if (!empty($archivosParaSubir)) {
            $this->verificarAutorizacionSubida($estudianteId, $this->acudienteId);
        }

        DB::transaction(function () use ($estudianteId, $tieneContactoCompletoGuardado, $diligencioContactoNuevo, $requiereInfoFinanciera, $archivosParaSubir) {
            // 1. Guardar/Actualizar Usuario
            $usuario = Usuario::updateOrCreate(
                ['nuip' => trim((string) $this->numero_documento)],
                [
                    'nombre' => trim((string) $this->nombre),
                    'apellido' => trim((string) $this->apellido),
                    'password_hash' => $this->usuarioModel ? $this->usuarioModel->password_hash : Hash::make($this->numero_documento),
                ]
            );

            if (Role::where('name', 'Padre')->exists() && !$usuario->hasRole('Padre')) {
                $usuario->assignRole('Padre');
            }

            // 2. Info Documento (Ubicación)
            DB::table('usuario_info_documento')->updateOrInsert(
                ['usuario_id' => $usuario->id],
                [
                    'tipo_documento' => $this->tipo_documento,
                    'numero_documento' => trim((string) $this->numero_documento),
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
            if (!$tieneContactoCompletoGuardado || $diligencioContactoNuevo) {
                UsuarioContacto::create([
                    'usuario_id' => $usuario->id,
                    'email' => trim((string) $this->email),
                    'telefono' => trim((string) $this->telefono),
                    'direccion' => trim((string) $this->direccion),
                ]);
            }

            // 5. Financiera (Para Padre, Madre o Padre/Madre cabeza de familia)
            if ($requiereInfoFinanciera && $this->estado_laboral) {
                UsuarioEstadoLaboral::updateOrCreate(
                    ['usuario_id' => $usuario->id],
                    [
                        'estado_laboral' => $this->estado_laboral,
                        'empresa' => $this->estado_laboral === 'Empleado' ? trim((string) $this->empresa) : null,
                    ]
                );
            }

            // 6. Guardar documentos subidos de forma segura
            foreach ($archivosParaSubir as $nombreDocumento => $archivo) {
                $this->guardarArchivoSeguro($usuario, $nombreDocumento, $archivo);
            }
        });

        $this->reset();
    }

    protected function verificarAutorizacionSubida(int|string $estudianteId, int|string|null $acudienteId): void
    {
        $currentUser = Auth::user();
        if (!$currentUser) {
            throw ValidationException::withMessages([
                'form.documento_identidad' => 'Acción no autenticada.',
            ]);
        }

        $isPrivileged = $currentUser->hasRole(['administrador', 'profesor', 'Super-Admin']);
        $isOwner = ($currentUser->id == $acudienteId || $currentUser->id == $estudianteId);
        $isParent = DB::table('usuario_has_child')
            ->where('parent_id', $currentUser->id)
            ->where('child_id', $estudianteId)
            ->exists();

        if (!$isPrivileged && !$isOwner && !$isParent) {
            throw ValidationException::withMessages([
                'form.documento_identidad' => 'No tiene permiso para subir documentos para este estudiante.',
            ]);
        }
    }

    protected function guardarArchivoSeguro(Usuario $usuario, string $nombreDocumento, $archivo): void
    {
        $safeNuip = preg_replace('/[^0-9]/', '', (string) $usuario->nuip);
        if (empty($safeNuip)) {
            throw ValidationException::withMessages([
                "form.{$nombreDocumento}" => 'NUIP de usuario no válido.',
            ]);
        }

        $extension = strtolower((string) $archivo->extension());
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
        if (!in_array($extension, $allowedExtensions, true)) {
            throw ValidationException::withMessages([
                "form.{$nombreDocumento}" => 'Tipo de archivo no permitido. Solo PDF, JPG, JPEG o PNG.',
            ]);
        }

        $safeNombre = Str::slug($nombreDocumento, '_');
        $safeNombre = preg_replace('/[^a-zA-Z0-9_-]/', '', $safeNombre);
        if (empty($safeNombre)) {
            $safeNombre = 'documento_' . time();
        }

        $path = 'documentos/estudiantes/' . $safeNuip . '/documentos';
        $name = $safeNombre . '.' . $extension;

        $archivo->storeAs($path, $name);

        DB::table('usuario_archivos')->updateOrInsert([
            'usuario_id' => $usuario->id,
            'nombre_archivo' => $nombreDocumento,
        ], [
            'ruta_archivo' => $path . '/' . $name,
            'tipo_archivo' => $extension,
        ]);
    }

    public function guardarContacto(): bool
    {
        $this->validate([
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:50',
            'direccion' => 'required|string|max:255',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'direccion.required' => 'La dirección de residencia es obligatoria.',
        ]);

        if (!$this->acudienteId) {
            return false;
        }

        UsuarioContacto::create([
            'usuario_id' => $this->acudienteId,
            'email' => trim((string) $this->email),
            'telefono' => trim((string) $this->telefono),
            'direccion' => trim((string) $this->direccion),
        ]);

        $this->reset(['email', 'telefono', 'direccion']);
        $this->contacto = UsuarioContacto::where('usuario_id', $this->acudienteId)->get();

        return true;
    }

    public function quitarContacto($id)
    {
        if (!$this->acudienteId) {
            return;
        }

        DB::table('usuario_contacto')
            ->where('id', $id)
            ->where('usuario_id', $this->acudienteId)
            ->delete();

        $this->contacto = UsuarioContacto::where('usuario_id', $this->acudienteId)->get();
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
