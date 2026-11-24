<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class MatriculaValidationService
{
    public const PARENTESCOS_FINANCIEROS = [
        'Padre',
        'Madre',
        'Padre/Madre_cabeza_de_familia',
    ];

    /**
     * Valida que toda la información obligatoria y documentos requeridos del estudiante estén completos en BD.
     *
     * @return array{valido: bool, errores: array<int, string>}
     */
    public function validarEstudiante(int|string $estudianteId): array
    {
        $errores = [];

        $usuario = DB::table('usuarios')->where('id', $estudianteId)->first();
        if (!$usuario) {
            return ['valido' => false, 'errores' => ['No se encontró el registro del estudiante.']];
        }

        if ($this->esVacio($usuario->nombre)) {
            $errores[] = 'El nombre del estudiante es obligatorio.';
        }
        if ($this->esVacio($usuario->apellido)) {
            $errores[] = 'El apellido del estudiante es obligatorio.';
        }
        if ($this->esVacio($usuario->nuip)) {
            $errores[] = 'El número de documento del estudiante es obligatorio.';
        }

        $infoDocumento = DB::table('usuario_info_documento')
            ->where('usuario_id', $estudianteId)
            ->first();

        if (!$infoDocumento || $this->esVacio($infoDocumento->tipo_documento)) {
            $errores[] = 'El tipo de documento del estudiante es obligatorio.';
        }
        if (!$infoDocumento || $this->esVacio($infoDocumento->departamento_expedicion)) {
            $errores[] = 'El departamento de expedición del estudiante es obligatorio.';
        }
        if (!$infoDocumento || $this->esVacio($infoDocumento->municipio_expedicion)) {
            $errores[] = 'El municipio de expedición del estudiante es obligatorio.';
        }

        $infoPersonal = DB::table('usuario_informacion')
            ->where('usuario_id', $estudianteId)
            ->first();

        if (!$infoPersonal || $this->esVacio($infoPersonal->fecha_nacimiento)) {
            $errores[] = 'La fecha de nacimiento del estudiante es obligatoria.';
        }
        if (!$infoPersonal || $this->esVacio($infoPersonal->departamento_nacimiento)) {
            $errores[] = 'El departamento de nacimiento del estudiante es obligatorio.';
        }
        if (!$infoPersonal || $this->esVacio($infoPersonal->municipio_nacimiento)) {
            $errores[] = 'El municipio de nacimiento del estudiante es obligatorio.';
        }
        if (!$infoPersonal || !in_array($infoPersonal->sexo, ['Masculino', 'Femenino'], true)) {
            $errores[] = 'El sexo del estudiante es obligatorio.';
        }

        $contacto = DB::table('usuario_contacto')
            ->where('usuario_id', $estudianteId)
            ->first();

        if (!$contacto || $this->esVacio($contacto->direccion)) {
            $errores[] = 'La dirección de residencia del estudiante es obligatoria.';
        }

        $infoMedica = DB::table('usuario_info_medica')
            ->where('usuario_id', $estudianteId)
            ->first();

        if (!$infoMedica || $this->esVacio($infoMedica->rh)) {
            $errores[] = 'El RH del estudiante es obligatorio.';
        }
        if (!$infoMedica || $this->esVacio($infoMedica->eps)) {
            $errores[] = 'La EPS del estudiante es obligatoria.';
        }

        if (!$this->tieneDocumento($estudianteId, 'documento_identidad')) {
            $errores[] = 'Debe subir el documento de identidad del estudiante.';
        }

        return [
            'valido' => empty($errores),
            'errores' => $errores,
        ];
    }

    /**
     * Valida que un acudiente individual tenga todos sus campos obligatorios y documentos requeridos en BD.
     *
     * @return array{valido: bool, errores: array<int, string>}
     */
    public function validarAcudienteIndividual(int|string $acudienteId, string $parentesco): array
    {
        $errores = [];

        $usuario = DB::table('usuarios')->where('id', $acudienteId)->first();
        if (!$usuario) {
            return ['valido' => false, 'errores' => ['No se encontró el acudiente.']];
        }

        $etiqueta = trim(($usuario->nombre ?? '') . ' ' . ($usuario->apellido ?? ''));
        $prefijo = $etiqueta !== '' ? "{$parentesco} ({$etiqueta})" : $parentesco;

        if ($this->esVacio($usuario->nombre)) {
            $errores[] = "{$prefijo}: falta el nombre.";
        }
        if ($this->esVacio($usuario->apellido)) {
            $errores[] = "{$prefijo}: falta el apellido.";
        }
        if ($this->esVacio($usuario->nuip)) {
            $errores[] = "{$prefijo}: falta el número de documento.";
        }

        $infoDocumento = DB::table('usuario_info_documento')
            ->where('usuario_id', $acudienteId)
            ->first();

        if (!$infoDocumento || $this->esVacio($infoDocumento->tipo_documento)) {
            $errores[] = "{$prefijo}: falta el tipo de documento.";
        }
        if (!$infoDocumento || $this->esVacio($infoDocumento->departamento_expedicion)) {
            $errores[] = "{$prefijo}: falta el departamento de expedición.";
        }
        if (!$infoDocumento || $this->esVacio($infoDocumento->municipio_expedicion)) {
            $errores[] = "{$prefijo}: falta el municipio de expedición.";
        }

        $contactos = DB::table('usuario_contacto')
            ->where('usuario_id', $acudienteId)
            ->get();

        $tieneContactoCompleto = $contactos->contains(function ($contacto) {
            return !$this->esVacio($contacto->telefono)
                && !$this->esVacio($contacto->email)
                && filter_var($contacto->email, FILTER_VALIDATE_EMAIL) !== false
                && !$this->esVacio($contacto->direccion);
        });

        if (!$tieneContactoCompleto) {
            $errores[] = "{$prefijo}: debe tener al menos un contacto completo (teléfono, correo válido y dirección).";
        }

        if (!$this->tieneDocumento($acudienteId, 'documento_identidad')) {
            $errores[] = "{$prefijo}: falta subir el documento de identidad.";
        }

        if (in_array($parentesco, self::PARENTESCOS_FINANCIEROS, true)) {
            $estadoLaboral = DB::table('usuario_estado_laboral')
                ->where('usuario_id', $acudienteId)
                ->first();

            if (!$estadoLaboral || $this->esVacio($estadoLaboral->estado_laboral)) {
                $errores[] = "{$prefijo}: falta el estado laboral.";
            } else {
                if ($estadoLaboral->estado_laboral === 'Empleado') {
                    if ($this->esVacio($estadoLaboral->empresa)) {
                        $errores[] = "{$prefijo}: falta la empresa donde trabaja.";
                    }
                    if (!$this->tieneDocumento($acudienteId, 'certificado_laboral')) {
                        $errores[] = "{$prefijo}: falta subir el certificado laboral.";
                    }
                } elseif ($estadoLaboral->estado_laboral === 'Independiente') {
                    if (!$this->tieneDocumento($acudienteId, 'camara_comercio')) {
                        $errores[] = "{$prefijo}: falta subir la cámara de comercio.";
                    }
                    if (!$this->tieneDocumento($acudienteId, 'certificado_ingresos')) {
                        $errores[] = "{$prefijo}: falta subir el certificado de ingresos.";
                    }
                }
            }
        }

        return [
            'valido' => empty($errores),
            'errores' => $errores,
        ];
    }

    /**
     * Valida que el estudiante cuente con los acudientes requeridos y que todos tengan su información y documentos completos.
     *
     * @return array{valido: bool, errores: array<int, string>}
     */
    public function validarAcudientes(int|string $estudianteId): array
    {
        $errores = [];

        $acudientes = DB::table('usuario_has_child')
            ->select('usuario_has_child.parent_id', 'usuario_has_child.parentesco')
            ->join('usuarios', 'usuario_has_child.parent_id', '=', 'usuarios.id')
            ->where('usuario_has_child.child_id', $estudianteId)
            ->get();

        $tienePadre = $acudientes->contains('parentesco', 'Padre');
        $tieneMadre = $acudientes->contains('parentesco', 'Madre');
        $tieneCabezaFamilia = $acudientes->contains('parentesco', 'Padre/Madre_cabeza_de_familia');

        if (!($tienePadre && $tieneMadre) && !$tieneCabezaFamilia) {
            $errores[] = 'El estudiante debe tener registrados tanto al Padre como a la Madre, o bien un Padre/Madre cabeza de familia.';
        }

        foreach ($acudientes as $acudiente) {
            $resultado = $this->validarAcudienteIndividual($acudiente->parent_id, (string) $acudiente->parentesco);
            if (!$resultado['valido']) {
                $errores = array_merge($errores, $resultado['errores']);
            }
        }

        return [
            'valido' => empty($errores),
            'errores' => $errores,
        ];
    }

    /**
     * Verifica si un usuario tiene registrado un documento con ruta no vacía en usuario_archivos.
     */
    public function tieneDocumento(int|string $usuarioId, string $nombreDocumento): bool
    {
        return DB::table('usuario_archivos')
            ->where('usuario_id', $usuarioId)
            ->where('nombre_archivo', $nombreDocumento)
            ->whereNotNull('ruta_archivo')
            ->where('ruta_archivo', '!=', '')
            ->exists();
    }

    private function esVacio(mixed $valor): bool
    {
        return $valor === null || trim((string) $valor) === '';
    }
}
