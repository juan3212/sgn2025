<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\getUserDataService;
use App\Models\UsuarioGrado;
use App\Models\Periodo;
use App\Models\Materia;
use App\Models\NotaFinalMateria;
use App\Models\NotaRecuperacion;
use App\Models\Usuario;


class certificadosController extends Controller
{
    //
    public $usuarioId;
    public $user;
    public $groupedMaterias = [];
    
    // Definimos las estructuras como propiedades protegidas
    protected $subjectGroups = [
        ['name' => 'EDUCACIÓN ARTÍSTICA', 'type' => 'arts'],
        ['name' => 'EDUCACIÓN FÍSICA, RECREACIÓN Y DEPORTES', 'type' => 'sport'],
        ['name' => 'HUMANIDADES, LENGUA CASTELLANA E IDIOMAS EXTRANJEROS', 'type' => 'language'],
        ['name' => 'TECNOLOGÍA E INFORMÁTICA', 'type' => 'tech'],
        ['name' => 'MATEMÁTICAS', 'type' => 'math'],
        ['name' => 'CIENCIAS NATURALES Y EDUCACIÓN AMBIENTAL', 'type' => 'science'],
        ['name' => 'CIENCIAS SOCIALES, HISTORIA, GEOGRAFÍA', 'type' => 'social'],
        ['name' => 'CONVIVENCIA ESCOLAR', 'type' => 'environment'],
        ['name' => 'EDUCACIÓN RELIGIOSA', 'type' => 'religion'],
        ['name' => 'EDUCACIÓN ÉTICA Y EN VALORES HUMANOS', 'type' => 'ethics'],
        ['name' => 'Otras', 'type' => 'other'] // Categoría por defecto
    ];
    
    protected $subjectsTree = [
        ['name' => 'ARTISTIC EXPRESSION', 'esName' => 'Expresión artística', 'type' => 'arts'],
        ['name' => 'ARTS', 'esName' => 'Artes', 'type' => 'arts'],
        ['name' => 'DRAMA', 'esName' => 'Drama', 'type' => 'arts'],
        ['name' => 'MUSIC', 'esName' => 'Música', 'type' => 'arts'],
        ['name' => 'DANCE', 'esName' => 'Danzas', 'type' => 'arts'],
        ['name' => 'MUSICAL DRAMA', 'esName' => 'Drama musical', 'type' => 'sport'],
        ['name' => 'PHYSICAL EDUCATION', 'esName' => 'Educación física', 'type' => 'sport'],
        ['name' => 'CALLIGRAPHY', 'esName' => 'Caligrafía', 'type' => 'language'],
        ['name' => 'ENGLISH', 'esName' => 'Inglés', 'type' => 'language'],
        ['name' => 'ENGLISH USAGE', 'esName' => 'Uso del Inglés', 'type' => 'language'],
        ['name' => 'HUMANITIES AND SPANISH LANGUAGE', 'esName' => 'Humanidades y lengua castellana', 'type' => 'language'],
        ['name' => 'SPANISH', 'esName' => 'Español', 'type' => 'language'],
        ['name' => 'LITERACY', 'esName' => 'Literatura', 'type' => 'language'],
        ['name' => 'PHILOSOPHY', 'esName' => 'Filosofía', 'type' => 'language'],
        ['name' => 'FRENCH', 'esName' => 'Francés', 'type' => 'language'],
        ['name' => 'INTERACTIVE ENGLISH', 'esName' => 'Inglés interactivo', 'type' => 'language'],
        ['name' => 'SYSTEMS AND DESIGN', 'esName' => 'Sistemas', 'type' => 'tech'],
        ['name' => 'MATH', 'esName' => 'Matemáticas', 'type' => 'math'],
        ['name' => 'SABER', 'esName' => 'Saber', 'type' => 'math'],
        ['name' => 'MATHEMATICAL LOGIC CONNECTION', 'esName' => 'Matemáticas y conexión lógica', 'type' => 'math'],
        ['name' => 'PHYSICS', 'esName' => 'Física', 'type' => 'science'],
        ['name' => 'CHEMISTRY', 'esName' => 'Química', 'type' => 'science'],
        ['name' => 'SCIENCE', 'esName' => 'Ciencias Naturales', 'type' => 'science'],
        ['name' => 'SOCIAL STUDIES', 'esName' => 'Ciencias Sociales', 'type' => 'social'],
        ['name' => 'ETHICS AND SOCIAL COEXISTENCE', 'esName' => 'Ética y valores humanos', 'type' => 'ethics'],
        ['name' => 'RELIGION', 'esName' => 'Religión', 'type' => 'religion'],
        ['name' => 'SOCIAL AND CULTURAL ENVIRONMENT', 'esName' => 'Ciencias sociales y culturales', 'type' => 'social'],
        ['name' => 'POLITICAL SCIENCES', 'esName' => 'Ciencias politicas', 'type' => 'social'],
        ['name' => 'SCHOOL BEHAVIOR', 'esName' => 'Convivencia Escolar', 'type' => 'environment'],
        ['name' => "PARENT'S COMMITMENT", 'esName' => 'Compromiso de los padres', 'type' => 'environment'],
    ];


    public function start($userid){
        $this->getStudentData($userid);
        $this->Periodos();
        $this->setNotas();
    }

     public function getStudentData($userId)
    {
        $user = new getUserDataService;
        $user = $user->getUserDataFromID($userId);
        $this->user = $user;
    }

    public function getDirectorCurso()
    {
        $director = Usuario::find($this->directorCurso);
        if($director){
            $nombreDirector = $director->nombre . ' ' . $director->apellido;
            $this->dispatch('directorCursoNombre', $nombreDirector);
        }else{
            $this->dispatch('directorCursoNombre', 'N/A');
        }
    }

    public function Materias()
    {
        $materias = Materia::select('materias.id', 'base_materia.nombre_materia', 'materias.intensidad_horaria', 'materias.profesor_id')
        ->join('base_materia', 'base_materia.id', '=', 'materias.materia_id')
        ->where('grado_id', $this->user['gradoID'])
        ->where('grupo_id', $this->user['grupoID'])
        ->get();
        return $materias;
    }

    public function NotasMateria($materiaId)
    {
        $notas = NotaFinalMateria::select('nota_final', 'periodo_id')
        ->where('materia_id', $materiaId)
        ->where('estudiante_id', $this->user['id'])
        ->where('periodo_id', '<=', $this->periodoId)
        ->orderBy('periodo_id', 'asc')
        ->get();
        $notas->each(function ($nota) {
            $nota->nota_final = number_format($nota->nota_final, 2);
        });
        return $notas;
    }

    public function Periodos()
    {
        $periodo = Periodo::where('fecha_fin', '>', now())
        ->first();
        $this->periodoId = $periodo->id - 1;
        if(date("j-n") >= "19-11"){
            $this->periodoId = $periodo->id;
        }
    }

    public function NotasCompetencias($materiaId)
    {
        $notasCompetencias = NotaFinalCompetencia::select('competencias.nombre', 'competencias.descripcion', 'competencias.porcentaje', 'notas_finales_competencias.nota_final')
        ->where('estudiante_id', $this->user['id'])
        ->where('materia_id', $materiaId)
        ->join('competencias', 'competencias.id', '=', 'notas_finales_competencias.competencia_id')
        ->where('competencias.periodo_id', $this->periodoId)
        ->orderBy('competencias.nombre', 'asc')
        ->get();

        $notasCompetencias->each(function ($nota) {
            $nota->nota_final = number_format($nota->nota_final / ($nota->porcentaje / 100), 2);
        });
        return $notasCompetencias; 
    }
    public function NotasRecuperacion($materiaId)
    {
        $notasRecuperacion = NotaRecuperacion::select('nota_final', 'periodo_id')
        ->where('materia_id', $materiaId)
        ->where('estudiante_id', $this->user['id'])
        ->orderBy('periodo_id', 'asc')
        ->get();
        $notasRecuperacion->each(function ($nota) {
            $nota->nota_final = number_format($nota->nota_final, 2);
        });
        return $notasRecuperacion;
    }
    public function promedioFinal($materiaId, $notasMateria, $notasRecuperacion)
    {
        $promedioFinal = 0;
        $promedioPeriodo = 0;
        foreach($notasMateria as $nota){
            $notaRecuperacion = $notasRecuperacion->firstWhere('periodo_id', $nota->periodo_id);
            if($notaRecuperacion){
                $promedioFinal += max($nota->nota_final, $notaRecuperacion->nota_final);
                if($nota->periodo_id == $this->periodoId){
                    $promedioPeriodo = max($nota->nota_final, $notaRecuperacion->nota_final);
                }
            }else{
                $promedioFinal += $nota->nota_final;
                if($nota->periodo_id == $this->periodoId){
                    $promedioPeriodo = $nota->nota_final;
                }
            }
        }
        if($promedioFinal > 0){
            $promedioFinal = round($promedioFinal / count($notasMateria), 2);
        }
        return [$promedioFinal, $promedioPeriodo];
    }
    public function setNotas()
    {
        $materias = $this->Materias();
        $materiasNotas = [];
              $grouped = [];
        foreach ($this->subjectGroups as $group) {
            $grouped[$group['type']] = [
                'name' => $group['name'],
                'materias' => []
            ];
        }

        foreach($materias as $materia){
            if($materia->nombre_materia == 'SCHOOL BEHAVIOR'){
                $this->directorCurso = $materia->profesor_id;
            }
            $notas = $this->NotasMateria($materia->id);
            $recuperacion = $this->NotasRecuperacion($materia->id);
            $promedioFinal = $this->promedioFinal($materia->id, $notas, $recuperacion);


        

        $detalles = $this->getSubjectDetails($materia->nombre_materia);

            // Construir el objeto de la materia
            $datosMateria = [
                'nombre_original' => $materia->nombre_materia,
                'nombre_es' => $detalles['esName'],
                'ih' => $materia->intensidad_horaria,
                'promedio' => $promedioFinal[0],
                // Puedes agregar aquí más detalles como notas por periodo si la vista lo requiere
            ];

            // Asignar al grupo correspondiente
            $type = $detalles['type'];
            
            if (isset($grouped[$type])) {
                $grouped[$type]['materias'][] = $datosMateria;
            } else {
                // Si no encuentra grupo, crea uno genérico o lo mete en 'other'
                if (!isset($grouped['other'])) {
                    $grouped['other'] = ['name' => 'Otras Asignaturas', 'materias' => []];
                }
                $grouped['other']['materias'][] = $datosMateria;
            }

        }
        // Filtrar grupos que quedaron vacíos (opcional)
        $this->groupedMaterias = array_filter($grouped, function($grupo) {
            return count($grupo['materias']) > 0;
        });

        return $this->groupedMaterias;

        $this->materiasNotas = $materiasNotas;
        $this->getDirectorCurso();
    }

     private function getSubjectDetails($nombreMateria) {
        $nombreUpper = trim(strtoupper($nombreMateria));
        
        foreach ($this->subjectsTree as $subject) {
            if ($subject['name'] === $nombreUpper) {
                return [
                    'esName' => $subject['esName'],
                    'type' => strtolower($subject['type'])
                ];
            }
        }

        return [
            'esName' => $nombreMateria, // Si no encuentra traducción, usa el original
            'type' => 'other'
        ];
    }

    public function estudiante($usuarioId)
    {
        $estudiante = Usuario::with('grados', 'grupos')->find($usuarioId);
        return $estudiante;
    }

    public function date()
    {
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        return [
            'dia' => date('d'),
            'mes' => $meses[date('n')], // Mes en español
            'anio' => date('Y')
        ];
    }

    public function render($usuarioId){
        $fechaActual = $this->date();
        $this->start($usuarioId);
        $estudiante = $this->estudiante($usuarioId);

        return view('pages.administrador.certificados', [
            'fechaActual' => $fechaActual,
            'groupedMaterias' => $this->groupedMaterias,
            'estudiante' => $estudiante
        ]);
        
    }

    /*
    public function estudiante($usuarioId)
    {
        $estudiante = Usuario::with('grados', 'grupos')->find($usuarioId);
        return $estudiante;
    }

    public function date()
    {
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        return [
            'dia' => date('d'),
            'mes' => $meses[date('n')], // Mes en español
            'anio' => date('Y')
        ];
    }

    public function generarCertificado($usuarioId)
    {
        $this->usuarioId = $usuarioId;

        // 1. Obtener Estudiante
        $estudiante = UsuarioGrado::find($this->usuarioId);
        
        if (!$estudiante) {
            return []; // O manejar error
        }

        // 2. Obtener el Periodo
        $periodo = Periodo::where('fecha_fin', '>', now())->first();
        if (!$periodo) {
            return []; 
        }

        $periodoId = $periodo->id - 1;
        // Ajuste manual de fechas según tu lógica
        if(date("j-n") >= "19-11"){
            $periodoId = $periodo->id;
        }
        $periodoId =4;

        // 3. Obtener Materias del estudiante
        $materias = Materia::select('materias.id', 'base_materia.nombre_materia', 'materias.intensidad_horaria')
            ->join('base_materia', 'base_materia.id', '=', 'materias.materia_id')
            ->where('materias.grado_id', $estudiante->grado_id)
            ->where('materias.grupo_id', $estudiante->grupo_id)
            ->get();

        $materiaIds = $materias->pluck('id')->toArray();
        
        // 4. Obtener Notas Finales
        $notasFinales = NotaFinalMateria::where('estudiante_id', $estudiante->usuario_id)
            ->where('periodo_id', '<=', $periodoId)
            ->whereIn('materia_id', $materiaIds)
            ->get()
            ->groupBy('materia_id');


        // 5. Obtener Notas Recuperación
        $notasRecuperacion = NotaRecuperacion::where('estudiante_id', $estudiante->id)
            ->whereIn('materia_id', $materiaIds)
            ->get()
            ->groupBy('materia_id');


        // 6. Estructura Base de Agrupación
        // Inicializamos el array con los grupos vacíos para mantener el orden
        $grouped = [];
        foreach ($this->subjectGroups as $group) {
            $grouped[$group['type']] = [
                'name' => $group['name'],
                'materias' => []
            ];
        }

        // 7. Procesar cada materia y asignarla a su grupo
        foreach ($materias as $materia) {
            
            // Calcular Promedio
            $notas = $notasFinales->get($materia->id, collect());
            $recuperaciones = $notasRecuperacion->get($materia->id, collect());

            $sumaPromedio = 0;
            $countNotas = 0;

            foreach ($notas as $nota) {
                $notaRecuperacion = $recuperaciones->where('periodo_id', $nota->periodo_id)->first();
                
                if ($notaRecuperacion) {
                    $sumaPromedio += max($nota->nota_final, $notaRecuperacion->nota_final);
                } else {
                    $sumaPromedio += $nota->nota_final;
                }
                $countNotas++;
            }

            $promedioFinal = $countNotas > 0 ? round($sumaPromedio / $countNotas, 2) : 0;
            

            // Buscar datos en subjectsTree (Nombre ES y Tipo)
            $detalles = $this->getSubjectDetails($materia->nombre_materia);

            // Construir el objeto de la materia
            $datosMateria = [
                'nombre_original' => $materia->nombre_materia,
                'nombre_es' => $detalles['esName'],
                'ih' => $materia->intensidad_horaria,
                'promedio' => $promedioFinal,
                // Puedes agregar aquí más detalles como notas por periodo si la vista lo requiere
            ];

            // Asignar al grupo correspondiente
            $type = $detalles['type'];
            
            if (isset($grouped[$type])) {
                $grouped[$type]['materias'][] = $datosMateria;
            } else {
                // Si no encuentra grupo, crea uno genérico o lo mete en 'other'
                if (!isset($grouped['other'])) {
                    $grouped['other'] = ['name' => 'Otras Asignaturas', 'materias' => []];
                }
                $grouped['other']['materias'][] = $datosMateria;
            }
        }

        // Filtrar grupos que quedaron vacíos (opcional)
        $this->groupedMaterias = array_filter($grouped, function($grupo) {
            return count($grupo['materias']) > 0;
        });

        return $this->groupedMaterias;
    }

    /**
     * Función auxiliar para buscar en el array de configuración
     */
    /*
    private function getSubjectDetails($nombreMateria) {
        $nombreUpper = trim(strtoupper($nombreMateria));
        
        foreach ($this->subjectsTree as $subject) {
            if ($subject['name'] === $nombreUpper) {
                return [
                    'esName' => $subject['esName'],
                    'type' => strtolower($subject['type'])
                ];
            }
        }

        return [
            'esName' => $nombreMateria, // Si no encuentra traducción, usa el original
            'type' => 'other'
        ];
    }

    public function render($usuarioId){
        $fechaActual = $this->date();
        $groupedMaterias = $this->generarCertificado($usuarioId);
        $estudiante = $this->estudiante($usuarioId);

        return view('pages.administrador.certificados', [
            'fechaActual' => $fechaActual,
            'groupedMaterias' => $groupedMaterias,
            'estudiante' => $estudiante
        ]);
        
    }
    */
}
