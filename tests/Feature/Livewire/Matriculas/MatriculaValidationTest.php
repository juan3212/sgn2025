<?php

use App\Livewire\Matriculas\Components\InfoBasicaUsuario;
use App\Livewire\Matriculas\Components\InfoContacto;
use App\Livewire\Matriculas\Components\InfoMedica;
use App\Livewire\Matriculas\Components\UploadDocument;
use App\Livewire\Matriculas\FormularioAcudiente;
use App\Livewire\Matriculas\FormularioMatricula;
use App\Models\Usuario;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('local');
    Http::fake([
        'https://api-colombia.com/api/v1/Department' => Http::response([
            ['id' => 5, 'name' => 'Antioquia'],
        ], 200),
        'https://api-colombia.com/api/v1/Department/*/cities' => Http::response([
            ['id' => 1, 'name' => 'Medellín'],
        ], 200),
    ]);
});

function crearEstudiantePrueba(string $nuip = '10000001'): Usuario
{
    return Usuario::create([
        'nombre' => 'Juan',
        'apellido' => 'Pérez',
        'nuip' => $nuip,
        'correo' => 'juan@example.com',
        'password_hash' => Hash::make('password'),
    ]);
}

function completarDatosEstudianteEnBd(Usuario $estudiante, bool $conDocumento = true): void
{
    DB::table('usuario_info_documento')->updateOrInsert(
        ['usuario_id' => $estudiante->id],
        [
            'tipo_documento' => 'TI',
            'numero_documento' => $estudiante->nuip,
            'departamento_expedicion' => '5',
            'municipio_expedicion' => 'Medellín',
        ]
    );

    DB::table('usuario_informacion')->updateOrInsert(
        ['usuario_id' => $estudiante->id],
        [
            'fecha_nacimiento' => '2012-05-10',
            'departamento_nacimiento' => '5',
            'municipio_nacimiento' => 'Medellín',
            'sexo' => 'Masculino',
        ]
    );

    DB::table('usuario_contacto')->updateOrInsert(
        ['usuario_id' => $estudiante->id],
        [
            'telefono' => '3001234567',
            'email' => 'juan@example.com',
            'direccion' => 'Calle 10 # 20-30',
        ]
    );

    DB::table('usuario_info_medica')->updateOrInsert(
        ['usuario_id' => $estudiante->id],
        [
            'rh' => 'O+',
            'eps' => 'Sura',
            'enfermedades' => null,
            'alergias' => null,
            'medicamentos' => null,
        ]
    );

    if ($conDocumento) {
        DB::table('usuario_archivos')->updateOrInsert(
            [
                'usuario_id' => $estudiante->id,
                'nombre_archivo' => 'documento_identidad',
            ],
            [
                'tipo_archivo' => 'pdf',
                'ruta_archivo' => "documentos/estudiantes/{$estudiante->nuip}/documentos/documento_identidad.pdf",
            ]
        );
    }
}

function crearAcudienteCompletoEnBd(
    Usuario $estudiante,
    string $parentesco,
    string $nuip,
    string $estadoLaboral = 'Empleado',
    array $omitirDocumentos = [],
    bool $conContactoCompleto = true
): Usuario {
    $acudiente = Usuario::create([
        'nombre' => 'Acudiente ' . $parentesco,
        'apellido' => 'Gómez',
        'nuip' => $nuip,
        'correo' => "{$nuip}@example.com",
        'password_hash' => Hash::make($nuip),
    ]);

    DB::table('usuario_info_documento')->insert([
        'usuario_id' => $acudiente->id,
        'tipo_documento' => 'CC',
        'numero_documento' => $nuip,
        'departamento_expedicion' => '5',
        'municipio_expedicion' => 'Medellín',
    ]);

    DB::table('usuario_has_child')->insert([
        'parent_id' => $acudiente->id,
        'child_id' => $estudiante->id,
        'parentesco' => $parentesco,
    ]);

    if ($conContactoCompleto) {
        DB::table('usuario_contacto')->insert([
            'usuario_id' => $acudiente->id,
            'telefono' => '3101112233',
            'email' => "{$nuip}@example.com",
            'direccion' => 'Carrera 45 # 12-34',
        ]);
    } else {
        DB::table('usuario_contacto')->insert([
            'usuario_id' => $acudiente->id,
            'telefono' => '',
            'email' => "{$nuip}@example.com",
            'direccion' => '',
        ]);
    }

    if (in_array($parentesco, ['Padre', 'Madre', 'Padre/Madre_cabeza_de_familia'], true)) {
        DB::table('usuario_estado_laboral')->insert([
            'usuario_id' => $acudiente->id,
            'estado_laboral' => $estadoLaboral,
            'empresa' => $estadoLaboral === 'Empleado' ? 'Empresa SA' : null,
        ]);
    }

    $docsRequeridos = ['documento_identidad'];
    if (in_array($parentesco, ['Padre', 'Madre', 'Padre/Madre_cabeza_de_familia'], true)) {
        if ($estadoLaboral === 'Empleado') {
            $docsRequeridos[] = 'certificado_laboral';
        } elseif ($estadoLaboral === 'Independiente') {
            $docsRequeridos[] = 'camara_comercio';
            $docsRequeridos[] = 'certificado_ingresos';
        }
    }

    foreach ($docsRequeridos as $doc) {
        if (!in_array($doc, $omitirDocumentos, true)) {
            DB::table('usuario_archivos')->insert([
                'usuario_id' => $acudiente->id,
                'nombre_archivo' => $doc,
                'tipo_archivo' => 'pdf',
                'ruta_archivo' => "documentos/estudiantes/{$nuip}/documentos/{$doc}.pdf",
            ]);
        }
    }

    return $acudiente;
}

test('no permite guardar informacion basica del estudiante si faltan campos obligatorios', function () {
    $estudiante = crearEstudiantePrueba();
    $this->actingAs($estudiante);

    Livewire::test(InfoBasicaUsuario::class, ['estudianteId' => $estudiante->id])
        ->set('nombre', '')
        ->set('apellido', '')
        ->set('documentoTipo', '')
        ->set('numeroDocumento', '')
        ->set('fechaNacimiento', '')
        ->set('sexo', '')
        ->set('valueUbicacionExpedicion', ['departamento' => '', 'municipio' => ''])
        ->set('valueUbicacionNacimiento', ['departamento' => '', 'municipio' => ''])
        ->call('saveUsuario')
        ->assertHasErrors([
            'nombre',
            'apellido',
            'documentoTipo',
            'numeroDocumento',
            'fechaNacimiento',
            'sexo',
            'valueUbicacionExpedicion.departamento',
            'valueUbicacionExpedicion.municipio',
            'valueUbicacionNacimiento.departamento',
            'valueUbicacionNacimiento.municipio',
        ])
        ->assertDispatched('estudiante-paso-validado', function ($event, $params) {
            return $params[0]['componente'] === 'info_basica' && $params[0]['valido'] === false;
        });
});

test('info contacto y medica del estudiante exigen direccion, rh y eps pero permiten campos opcionales vacios', function () {
    $estudiante = crearEstudiantePrueba();
    $this->actingAs($estudiante);

    Livewire::test(InfoContacto::class, ['estudianteId' => $estudiante->id, 'tipoUsuario' => 'estudiante'])
        ->set('direccion', '')
        ->set('email', 'correo-invalido')
        ->call('saveContacto')
        ->assertHasErrors(['direccion', 'email'])
        ->assertDispatched('estudiante-paso-validado', function ($event, $params) {
            return $params[0]['componente'] === 'info_contacto' && $params[0]['valido'] === false;
        });

    Livewire::test(InfoContacto::class, ['estudianteId' => $estudiante->id, 'tipoUsuario' => 'estudiante'])
        ->set('direccion', 'Calle 50 # 10-20')
        ->set('telefono', '')
        ->set('email', '')
        ->call('saveContacto')
        ->assertHasNoErrors()
        ->assertDispatched('estudiante-paso-validado', function ($event, $params) {
            return $params[0]['componente'] === 'info_contacto' && $params[0]['valido'] === true;
        });

    Livewire::test(InfoMedica::class, ['estudianteId' => $estudiante->id])
        ->set('rh', '')
        ->set('eps', '')
        ->call('saveInfoMedica')
        ->assertHasErrors(['rh', 'eps'])
        ->assertDispatched('estudiante-paso-validado', function ($event, $params) {
            return $params[0]['componente'] === 'info_medica' && $params[0]['valido'] === false;
        });

    Livewire::test(InfoMedica::class, ['estudianteId' => $estudiante->id])
        ->set('rh', 'A+')
        ->set('eps', 'Sanitas')
        ->call('saveInfoMedica')
        ->assertHasNoErrors()
        ->assertDispatched('estudiante-paso-validado', function ($event, $params) {
            return $params[0]['componente'] === 'info_medica' && $params[0]['valido'] === true;
        });
});

test('upload document del estudiante bloquea si no hay archivo ni documento previo y permite avanzar cuando se sube o ya existe', function () {
    $estudiante = crearEstudiantePrueba('10000002');
    $this->actingAs($estudiante);

    Livewire::test(UploadDocument::class, [
        'estudianteId' => $estudiante->id,
        'usuarioId' => $estudiante->id,
        'tipoDocumento' => 'Documento de identidad',
        'usuario' => 'estudiante',
        'nombreDocumento' => 'documento_identidad',
    ])
        ->call('validarYGuardarEstudiante')
        ->assertHasErrors(['documento'])
        ->assertDispatched('estudiante-paso-validado', function ($event, $params) {
            return $params[0]['componente'] === 'documento_identidad' && $params[0]['valido'] === false;
        });

    $archivo = UploadedFile::fake()->create('id.pdf', 100, 'application/pdf');

    Livewire::test(UploadDocument::class, [
        'estudianteId' => $estudiante->id,
        'usuarioId' => $estudiante->id,
        'tipoDocumento' => 'Documento de identidad',
        'usuario' => 'estudiante',
        'nombreDocumento' => 'documento_identidad',
    ])
        ->set('documento', $archivo)
        ->call('validarYGuardarEstudiante')
        ->assertHasNoErrors()
        ->assertDispatched('estudiante-paso-validado', function ($event, $params) {
            return $params[0]['componente'] === 'documento_identidad' && $params[0]['valido'] === true;
        });

    expect(DB::table('usuario_archivos')
        ->where('usuario_id', $estudiante->id)
        ->where('nombre_archivo', 'documento_identidad')
        ->exists())->toBeTrue();

    // Si ya existe en BD, no obliga a volver a subirlo
    Livewire::test(UploadDocument::class, [
        'estudianteId' => $estudiante->id,
        'usuarioId' => $estudiante->id,
        'tipoDocumento' => 'Documento de identidad',
        'usuario' => 'estudiante',
        'nombreDocumento' => 'documento_identidad',
    ])
        ->call('validarYGuardarEstudiante')
        ->assertHasNoErrors()
        ->assertDispatched('estudiante-paso-validado', function ($event, $params) {
            return $params[0]['componente'] === 'documento_identidad' && $params[0]['valido'] === true;
        });
});

test('formulario matricula no avanza de estudiante a acudiente si faltan datos o documento del estudiante', function () {
    $estudiante = crearEstudiantePrueba('10000003');
    $this->actingAs($estudiante);

    // 1. Al dar siguiente en estudiante, NO avanza inmediatamente; espera validación de subcomponentes
    $componente = Livewire::test(FormularioMatricula::class, ['estudianteId' => $estudiante->id])
        ->assertSet('seccionActiva', 'estudiante')
        ->call('siguiente')
        ->assertDispatched('saveData')
        ->assertSet('seccionActiva', 'estudiante');

    // 2. Si algún subcomponente falla, se mantiene en estudiante y muestra alerta
    $componente
        ->call('estudiantePasoValidado', ['componente' => 'info_basica', 'valido' => true, 'errores' => []])
        ->call('estudiantePasoValidado', ['componente' => 'info_contacto', 'valido' => true, 'errores' => []])
        ->call('estudiantePasoValidado', ['componente' => 'info_medica', 'valido' => false, 'errores' => ['La EPS del estudiante es obligatoria.']])
        ->call('estudiantePasoValidado', ['componente' => 'documento_identidad', 'valido' => false, 'errores' => ['Debe subir el documento requerido: Documento de identidad.']])
        ->assertSet('seccionActiva', 'estudiante')
        ->assertDispatched('showAlert');

    // 3. Incluso si todos reportan true, la verificación contra BD bloquea si falta el documento en BD
    completarDatosEstudianteEnBd($estudiante, conDocumento: false);

    $componente
        ->call('siguiente')
        ->call('estudiantePasoValidado', ['componente' => 'info_basica', 'valido' => true, 'errores' => []])
        ->call('estudiantePasoValidado', ['componente' => 'info_contacto', 'valido' => true, 'errores' => []])
        ->call('estudiantePasoValidado', ['componente' => 'info_medica', 'valido' => true, 'errores' => []])
        ->call('estudiantePasoValidado', ['componente' => 'documento_identidad', 'valido' => true, 'errores' => []])
        ->assertSet('seccionActiva', 'estudiante');

    // 4. Cuando todos los datos y el documento están completos en BD, avanza a acudiente
    completarDatosEstudianteEnBd($estudiante, conDocumento: true);

    $componente
        ->call('siguiente')
        ->call('estudiantePasoValidado', ['componente' => 'info_basica', 'valido' => true, 'errores' => []])
        ->call('estudiantePasoValidado', ['componente' => 'info_contacto', 'valido' => true, 'errores' => []])
        ->call('estudiantePasoValidado', ['componente' => 'info_medica', 'valido' => true, 'errores' => []])
        ->call('estudiantePasoValidado', ['componente' => 'documento_identidad', 'valido' => true, 'errores' => []])
        ->assertSet('seccionActiva', 'acudiente');
});

test('formulario acudiente exige campos obligatorios y documentos requeridos segun estado laboral y guarda archivos para acudiente nuevo', function () {
    $estudiante = crearEstudiantePrueba('10000004');
    $this->actingAs($estudiante);

    // 1. Intento de guardar Padre empleado sin empresa ni documentos -> falla validación
    Livewire::test(FormularioAcudiente::class, ['estudianteId' => $estudiante->id])
        ->set('form.tipo_documento', 'CC')
        ->set('form.numero_documento', '80000001')
        ->set('form.nombre', 'Carlos')
        ->set('form.apellido', 'Pérez')
        ->set('form.valueUbicacion', ['departamento' => '5', 'municipio' => ''])
        ->set('form.parentesco', 'Padre')
        ->set('form.email', 'carlos@example.com')
        ->set('form.telefono', '3009998877')
        ->set('form.direccion', 'Calle 1 # 2-3')
        ->set('form.estado_laboral', 'Empleado')
        ->set('form.empresa', '')
        ->call('guardar')
        ->assertHasErrors([
            'form.valueUbicacion.municipio',
            'form.empresa',
            'form.documento_identidad',
            'form.certificado_laboral',
        ]);

    expect(Usuario::where('nuip', '80000001')->exists())->toBeFalse();

    // 2. Padre empleado con todos los datos y documentos -> guarda usuario y archivos
    Livewire::test(FormularioAcudiente::class, ['estudianteId' => $estudiante->id])
        ->set('form.tipo_documento', 'CC')
        ->set('form.numero_documento', '80000001')
        ->set('form.nombre', 'Carlos')
        ->set('form.apellido', 'Pérez')
        ->set('form.valueUbicacion', ['departamento' => '5', 'municipio' => 'Medellín'])
        ->set('form.parentesco', 'Padre')
        ->set('form.email', 'carlos@example.com')
        ->set('form.telefono', '3009998877')
        ->set('form.direccion', 'Calle 1 # 2-3')
        ->set('form.estado_laboral', 'Empleado')
        ->set('form.empresa', 'Acme S.A.')
        ->set('form.documento_identidad', UploadedFile::fake()->create('cc_padre.pdf', 100, 'application/pdf'))
        ->set('form.certificado_laboral', UploadedFile::fake()->create('cert_lab.pdf', 100, 'application/pdf'))
        ->call('guardar')
        ->assertHasNoErrors();

    $padre = Usuario::where('nuip', '80000001')->first();
    expect($padre)->not->toBeNull();
    expect(DB::table('usuario_archivos')->where('usuario_id', $padre->id)->pluck('nombre_archivo')->all())
        ->toContain('documento_identidad', 'certificado_laboral');

    // 3. Madre independiente exige cámara de comercio y certificado de ingresos
    Livewire::test(FormularioAcudiente::class, ['estudianteId' => $estudiante->id])
        ->set('form.tipo_documento', 'CC')
        ->set('form.numero_documento', '80000002')
        ->set('form.nombre', 'María')
        ->set('form.apellido', 'López')
        ->set('form.valueUbicacion', ['departamento' => '5', 'municipio' => 'Medellín'])
        ->set('form.parentesco', 'Madre')
        ->set('form.email', 'maria@example.com')
        ->set('form.telefono', '3001112233')
        ->set('form.direccion', 'Calle 2 # 3-4')
        ->set('form.estado_laboral', 'Independiente')
        ->set('form.documento_identidad', UploadedFile::fake()->create('cc_madre.pdf', 100, 'application/pdf'))
        ->call('guardar')
        ->assertHasErrors([
            'form.camara_comercio',
            'form.certificado_ingresos',
        ]);
});

test('formulario matricula bloquea avance de acudiente a servicios si faltan padres, datos de contacto o documentos requeridos', function () {
    $estudiante = crearEstudiantePrueba('10000005');
    $this->actingAs($estudiante);
    completarDatosEstudianteEnBd($estudiante, conDocumento: true);

    // Solo Padre (falta Madre) -> bloqueado
    $padre = crearAcudienteCompletoEnBd($estudiante, 'Padre', '90000001', 'Empleado');

    Livewire::test(FormularioMatricula::class, ['estudianteId' => $estudiante->id])
        ->set('seccionActiva', 'acudiente')
        ->call('siguiente')
        ->assertSet('seccionActiva', 'acudiente')
        ->assertDispatched('showAlert');

    // Madre con documento faltante (certificado_ingresos) -> bloqueado
    $madre = crearAcudienteCompletoEnBd(
        $estudiante,
        'Madre',
        '90000002',
        'Independiente',
        omitirDocumentos: ['certificado_ingresos']
    );

    Livewire::test(FormularioMatricula::class, ['estudianteId' => $estudiante->id])
        ->set('seccionActiva', 'acudiente')
        ->call('siguiente')
        ->assertSet('seccionActiva', 'acudiente')
        ->assertDispatched('showAlert');

    // Subir el documento faltante de la madre -> ahora sí avanza a servicios
    DB::table('usuario_archivos')->insert([
        'usuario_id' => $madre->id,
        'nombre_archivo' => 'certificado_ingresos',
        'tipo_archivo' => 'pdf',
        'ruta_archivo' => 'documentos/estudiantes/90000002/documentos/certificado_ingresos.pdf',
    ]);

    Livewire::test(FormularioMatricula::class, ['estudianteId' => $estudiante->id])
        ->set('seccionActiva', 'acudiente')
        ->call('siguiente')
        ->assertSet('seccionActiva', 'servicios');
});

test('formulario matricula bloquea finalizacion de contratos sin acudiente de facturacion o sin aceptar todos los documentos', function () {
    $estudiante = crearEstudiantePrueba('10000006');
    $this->actingAs($estudiante);
    completarDatosEstudianteEnBd($estudiante, conDocumento: true);
    $cabeza = crearAcudienteCompletoEnBd($estudiante, 'Padre/Madre_cabeza_de_familia', '91000001', 'No trabaja');

    // Sin acudienteFacturacion -> bloqueado
    Livewire::test(FormularioMatricula::class, ['estudianteId' => $estudiante->id])
        ->set('seccionActiva', 'contratos')
        ->set('checks', [
            'contrato' => true,
            'carta' => true,
            'pagare' => true,
            'autorizacion-factura-electronica' => true,
            'acta' => true,
        ])
        ->call('siguiente')
        ->assertDispatched('showAlert');

    expect(DB::table('matricula_completada_info')->where('estudiante_id', $estudiante->id)->exists())->toBeFalse();

    // Con acudienteFacturacion pero falta un check -> bloqueado
    Livewire::test(FormularioMatricula::class, ['estudianteId' => $estudiante->id])
        ->set('seccionActiva', 'contratos')
        ->set('acudienteFacturacion', (string) $cabeza->id)
        ->set('checks', [
            'contrato' => true,
            'carta' => true,
            'pagare' => true,
            'autorizacion-factura-electronica' => false,
            'acta' => true,
        ])
        ->call('siguiente')
        ->assertDispatched('showAlert');

    expect(DB::table('matricula_completada_info')->where('estudiante_id', $estudiante->id)->exists())->toBeFalse();

    // Con acudienteFacturacion y todos los checks -> completa matrícula
    Livewire::test(FormularioMatricula::class, ['estudianteId' => $estudiante->id])
        ->set('seccionActiva', 'contratos')
        ->set('acudienteFacturacion', (string) $cabeza->id)
        ->set('checks', [
            'contrato' => true,
            'carta' => true,
            'pagare' => true,
            'autorizacion-factura-electronica' => true,
            'acta' => true,
        ])
        ->call('siguiente')
        ->assertDispatched('showAlert', function ($event, $params) {
            return $params[0]['type'] === 'success';
        });

    expect(DB::table('matricula_completada_info')->where('estudiante_id', $estudiante->id)->exists())->toBeTrue();
});

test('editar un acudiente existente conserva documentos ya cargados y no exige volver a subirlos', function () {
    $estudiante = crearEstudiantePrueba('10000007');
    $this->actingAs($estudiante);

    $padre = crearAcudienteCompletoEnBd($estudiante, 'Padre', '92000001', 'Empleado');

    Livewire::test(FormularioAcudiente::class, ['estudianteId' => $estudiante->id])
        ->call('editarAcudiente', '92000001')
        ->assertSet('form.acudienteId', $padre->id)
        ->assertSet('form.documento_identidad_url', 'documentos/estudiantes/92000001/documentos/documento_identidad.pdf')
        ->assertSet('form.certificado_laboral_url', 'documentos/estudiantes/92000001/documentos/certificado_laboral.pdf')
        ->set('form.empresa', 'Nueva Empresa S.A.S.')
        ->call('guardar')
        ->assertHasNoErrors();

    expect(DB::table('usuario_estado_laboral')->where('usuario_id', $padre->id)->value('empresa'))
        ->toBe('Nueva Empresa S.A.S.');
});

