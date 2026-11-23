<?php

namespace App\Livewire\Matriculas;

use App\Models\MatriculaCompletadaInfo;
use App\Models\UsuarioFacturacion;
use App\Services\MatriculaValidationService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class FormularioMatricula extends Component
{
    public $estudianteId;
    public $nombre;
    public $apellido;
    public $email;
    public $secciones = ['estudiante', 'acudiente', 'servicios', 'contratos'];
    public $seccionActiva = 'estudiante';
    public $basicData;
    public $checks = [
        'contrato' => false,
        'carta' => false,
        'pagare' => false,
        'autorizacion-factura-electronica' => false,
        'acta' => false,
    ];
    public $canContinue = false;
    public $acudienteFacturacion = '';
    public $padres = [];
    public array $estadoValidacionEstudiante = [];
    public array $erroresSeccion = [];

    public function mount($estudianteId)
    {
        $this->estudianteId = $estudianteId;

        $this->cargarPadres();

        $registroExistente = DB::table('usuario_facturacion')
            ->select('acudiente_id')
            ->where('estudiante_id', $this->estudianteId)
            ->first();

        $this->acudienteFacturacion = $registroExistente ? (string) $registroExistente->acudiente_id : '';

        $matriculaCompletadaInfo = DB::table('matricula_completada_info')
            ->select('*')
            ->where('estudiante_id', $this->estudianteId)
            ->first();

        if ($matriculaCompletadaInfo) {
            $this->checks = [
                'contrato' => true,
                'carta' => true,
                'pagare' => true,
                'autorizacion-factura-electronica' => true,
                'acta' => true,
            ];

            $this->seccionActiva = 'contratos';
        }
    }

    #[On('acudientes-actualizados')]
    public function cargarPadres(): void
    {
        if (!$this->estudianteId) {
            $this->padres = [];

            return;
        }

        $this->padres = DB::table('usuario_has_child')
            ->select('usuario_has_child.parent_id', 'usuario_has_child.parentesco', 'usuarios.nombre', 'usuarios.apellido')
            ->join('usuarios', 'usuarios.id', '=', 'usuario_has_child.parent_id')
            ->where('child_id', $this->estudianteId)
            ->whereIn('parentesco', MatriculaValidationService::PARENTESCOS_FINANCIEROS)
            ->get()
            ->map(fn ($p) => (array) $p)
            ->toArray();

        $idsValidos = array_map(fn ($p) => (string) $p['parent_id'], $this->padres);
        if ($this->acudienteFacturacion !== '' && !in_array((string) $this->acudienteFacturacion, $idsValidos, true)) {
            $this->acudienteFacturacion = '';
        }
    }

    #[On('basicUserData')]
    public function updateUserData(array $data)
    {
        $this->basicData = $data;
    }

    public function getContratosListosProperty(): bool
    {
        return !empty($this->acudienteFacturacion) && !in_array(false, $this->checks, true);
    }

    public function siguiente()
    {
        $this->erroresSeccion = [];
        $this->canContinue = false;

        switch ($this->seccionActiva) {
            case 'estudiante':
                $this->guardarEstudiante();
                // El avance ocurre únicamente en estudiantePasoValidado() cuando todos los
                // subcomponentes validan y guardan correctamente y se verifica en BD.
                return;

            case 'acudiente':
                $this->guardarAcudiente();
                break;

            case 'servicios':
                $this->validarAntesDeContratos();
                break;

            case 'contratos':
                $this->guardarContratos();
                $this->dispatch('scrollToTop');

                return;
        }

        $siguienteSeccion = array_search($this->seccionActiva, $this->secciones, true) + 1;
        if ($siguienteSeccion < count($this->secciones) && $this->canContinue) {
            $this->seccionActiva = $this->secciones[$siguienteSeccion];
            $this->canContinue = false;
        }
    }

    public function anterior()
    {
        $this->erroresSeccion = [];
        $this->estadoValidacionEstudiante = [];

        $anteriorSeccion = array_search($this->seccionActiva, $this->secciones, true) - 1;
        if ($anteriorSeccion >= 0) {
            $this->seccionActiva = $this->secciones[$anteriorSeccion];
            $this->dispatch('scrollToTop');
        }
    }

    public function guardarEstudiante()
    {
        $this->erroresSeccion = [];
        $this->estadoValidacionEstudiante = [
            'info_basica' => null,
            'info_contacto' => null,
            'info_medica' => null,
            'documento_identidad' => null,
        ];

        $this->dispatch('saveData');
    }

    #[On('estudiante-paso-validado')]
    public function estudiantePasoValidado($data)
    {
        if ($this->seccionActiva !== 'estudiante') {
            return;
        }

        $componente = $data['componente'] ?? null;
        if (!$componente || !array_key_exists($componente, $this->estadoValidacionEstudiante)) {
            return;
        }

        $this->estadoValidacionEstudiante[$componente] = (bool) ($data['valido'] ?? false);

        if (!empty($data['errores']) && is_array($data['errores'])) {
            $this->erroresSeccion = array_values(array_unique(array_merge($this->erroresSeccion, $data['errores'])));
        }

        // Esperar a que respondan los 4 subcomponentes del estudiante
        if (in_array(null, $this->estadoValidacionEstudiante, true)) {
            return;
        }

        $todosValidos = !in_array(false, $this->estadoValidacionEstudiante, true);
        $this->estadoValidacionEstudiante = [];

        if (!$todosValidos) {
            $mensaje = !empty($this->erroresSeccion)
                ? 'No puede continuar. Faltan datos obligatorios o documentos requeridos: ' . implode(' ', $this->erroresSeccion)
                : 'Por favor complete todos los campos obligatorios y suba los documentos requeridos del estudiante.';

            $this->dispatch('showAlert', ['message' => $mensaje, 'type' => 'error']);
            $this->dispatch('scrollToTop');

            return;
        }

        // Verificación final directa contra BD
        $validator = app(MatriculaValidationService::class);
        $verificacion = $validator->validarEstudiante($this->estudianteId);

        if (!$verificacion['valido']) {
            $this->erroresSeccion = $verificacion['errores'];
            $this->dispatch('showAlert', [
                'message' => 'No puede continuar. Faltan datos obligatorios o documentos requeridos: ' . implode(' ', $verificacion['errores']),
                'type' => 'error',
            ]);
            $this->dispatch('scrollToTop');

            return;
        }

        $this->erroresSeccion = [];
        $this->seccionActiva = 'acudiente';
        $this->dispatch('scrollToTop');
    }

    public function guardarAcudiente()
    {
        $validator = app(MatriculaValidationService::class);

        $verificacionEstudiante = $validator->validarEstudiante($this->estudianteId);
        if (!$verificacionEstudiante['valido']) {
            $this->erroresSeccion = $verificacionEstudiante['errores'];
            $this->seccionActiva = 'estudiante';
            $this->dispatch('showAlert', [
                'message' => 'Complete primero la información obligatoria y documentos del estudiante.',
                'type' => 'error',
            ]);
            $this->dispatch('scrollToTop');

            return;
        }

        $verificacion = $validator->validarAcudientes($this->estudianteId);

        if ($verificacion['valido']) {
            $this->erroresSeccion = [];
            $this->cargarPadres();
            $this->canContinue = true;
            $this->dispatch('scrollToTop');
        } else {
            $this->erroresSeccion = $verificacion['errores'];
            $this->dispatch('showAlert', [
                'message' => 'No puede continuar. ' . implode(' ', $verificacion['errores']),
                'type' => 'error',
            ]);
            $this->dispatch('scrollToTop');
        }
    }

    protected function validarAntesDeContratos(): void
    {
        $validator = app(MatriculaValidationService::class);

        $verificacionEstudiante = $validator->validarEstudiante($this->estudianteId);
        if (!$verificacionEstudiante['valido']) {
            $this->erroresSeccion = $verificacionEstudiante['errores'];
            $this->seccionActiva = 'estudiante';
            $this->dispatch('showAlert', [
                'message' => 'Complete primero la información obligatoria y documentos del estudiante.',
                'type' => 'error',
            ]);
            $this->dispatch('scrollToTop');

            return;
        }

        $verificacionAcudientes = $validator->validarAcudientes($this->estudianteId);
        if (!$verificacionAcudientes['valido']) {
            $this->erroresSeccion = $verificacionAcudientes['errores'];
            $this->seccionActiva = 'acudiente';
            $this->dispatch('showAlert', [
                'message' => 'Complete primero la información obligatoria y documentos de los acudientes.',
                'type' => 'error',
            ]);
            $this->dispatch('scrollToTop');

            return;
        }

        $this->inscribirServicios();
        $this->cargarPadres();
        $this->canContinue = true;
    }

    public function updatedAcudienteFacturacion()
    {
        $this->cargarPadres();
        $idsValidos = array_map(fn ($p) => (string) $p['parent_id'], $this->padres);

        if ($this->acudienteFacturacion === '' || !in_array((string) $this->acudienteFacturacion, $idsValidos, true)) {
            $this->acudienteFacturacion = '';

            return;
        }

        UsuarioFacturacion::updateOrInsert(
            [
                'estudiante_id' => $this->estudianteId,
            ],
            [
                'acudiente_id' => $this->acudienteFacturacion,
            ]
        );
    }

    public function inscribirServicios()
    {
        $this->dispatch('inscribir_servicios');
        $this->dispatch('scrollToTop');
    }

    public function guardarContratos()
    {
        $validator = app(MatriculaValidationService::class);

        $verificacionEstudiante = $validator->validarEstudiante($this->estudianteId);
        if (!$verificacionEstudiante['valido']) {
            $this->erroresSeccion = $verificacionEstudiante['errores'];
            $this->dispatch('showAlert', [
                'message' => 'No se puede finalizar: faltan datos o documentos obligatorios del estudiante.',
                'type' => 'error',
            ]);

            return;
        }

        $verificacionAcudientes = $validator->validarAcudientes($this->estudianteId);
        if (!$verificacionAcudientes['valido']) {
            $this->erroresSeccion = $verificacionAcudientes['errores'];
            $this->dispatch('showAlert', [
                'message' => 'No se puede finalizar: faltan datos o documentos obligatorios de los acudientes.',
                'type' => 'error',
            ]);

            return;
        }

        $this->cargarPadres();
        $idsValidos = array_map(fn ($p) => (string) $p['parent_id'], $this->padres);

        if (empty($this->acudienteFacturacion) || !in_array((string) $this->acudienteFacturacion, $idsValidos, true)) {
            $this->erroresSeccion = ['Debe seleccionar un acudiente válido para la facturación electrónica.'];
            $this->dispatch('showAlert', [
                'message' => 'Debe seleccionar un acudiente válido para la facturación electrónica.',
                'type' => 'error',
            ]);

            return;
        }

        if (!$this->contratosListos) {
            $this->erroresSeccion = ['Debe aceptar todos los documentos legales antes de finalizar la matrícula.'];
            $this->dispatch('showAlert', [
                'message' => 'Debe aceptar todos los documentos legales antes de finalizar la matrícula.',
                'type' => 'error',
            ]);

            return;
        }

        UsuarioFacturacion::updateOrInsert(
            [
                'estudiante_id' => $this->estudianteId,
            ],
            [
                'acudiente_id' => $this->acudienteFacturacion,
            ]
        );

        MatriculaCompletadaInfo::updateOrCreate([
            'estudiante_id' => $this->estudianteId,
        ], [
            'ip' => request()->ip(),
        ]);

        $estudianteGrado = DB::table('usuario_grado')
            ->select('*')
            ->join('grados', 'usuario_grado.grado_id', 'grados.id')
            ->where('usuario_grado.usuario_id', $this->estudianteId)
            ->first();

        $promovido = DB::table('usuarios_promovidos')
            ->select('*')
            ->where('usuarios_promovidos.usuario_id', $this->estudianteId)
            ->first();

        $gradoActual = null;
        if ($promovido) {
            $gradoActual = $promovido->grado_destino;
        } elseif ($estudianteGrado) {
            $gradoActual = $estudianteGrado->grado_id + 1;
        }

        $grado = $gradoActual
            ? DB::table('grados')->select('*')->where('grados.id', $gradoActual)->first()
            : null;

        $mensaje = $grado
            ? 'Matrícula completada, el estudiante se inscribió en el grado: ' . $grado->grado
            : 'Matrícula completada correctamente.';

        $this->erroresSeccion = [];
        $this->dispatch('showAlert', ['message' => $mensaje, 'type' => 'success']);
    }

    public function render()
    {
        return view('livewire.matriculas.formulario-matricula');
    }
}
