<?php

namespace App\Livewire\Pages\Administrador;

use Livewire\Component;

class Certificados extends Component
{
    public $usuarioId;
    public $groupedMaterias = [];

    // Definimos las estructuras como propiedades protegidas
    protected $subjectGroups = [
        ["name" => "Educación artística", "type" => "arts"],
        [
            "name" => "Educación física, recreación y deportes",
            "type" => "sport",
        ],
        [
            "name" => "Humanidades, lengua castellana e idiomas extranjeros",
            "type" => "language",
        ],
        ["name" => "Tecnología e informática", "type" => "tech"],
        ["name" => "Matemáticas", "type" => "math"],
        [
            "name" => "Ciencias naturales y educación ambiental",
            "type" => "science",
        ],
        [
            "name" => "Ciencias sociales, historia, geografía",
            "type" => "social",
        ],
        ["name" => "Convivencia Escolar", "type" => "environment"],
        ["name" => "Educación religiosa", "type" => "religion"],
        ["name" => "Educación ética y en valores humanos", "type" => "ethics"],
        ["name" => "Otras", "type" => "other"], // Categoría por defecto
    ];

    protected $subjectsTree = [
        [
            "name" => "ARTISTIC EXPRESSION",
            "esName" => "Expresión artística",
            "type" => "arts",
        ],
        ["name" => "ARTS", "esName" => "Artes", "type" => "arts"],
        ["name" => "DRAMA", "esName" => "Drama", "type" => "arts"],
        ["name" => "MUSIC", "esName" => "Música", "type" => "arts"],
        ["name" => "DANCING", "esName" => "Danzas", "type" => "arts"],
        [
            "name" => "MUSICAL DRAMA",
            "esName" => "Drama musical",
            "type" => "sport",
        ],
        [
            "name" => "PHYSICAL EDUCATION",
            "esName" => "Educación física",
            "type" => "sport",
        ],
        [
            "name" => "CALLIGRAPHY",
            "esName" => "Caligrafía",
            "type" => "language",
        ],
        ["name" => "ENGLISH", "esName" => "Inglés", "type" => "language"],
        [
            "name" => "ENGLISH USAGE",
            "esName" => "Uso del Inglés",
            "type" => "language",
        ],
        [
            "name" => "HUMANITIES AND SPANISH LANGUAGE",
            "esName" => "Humanidades y lengua castellana",
            "type" => "language",
        ],
        ["name" => "SPANISH", "esName" => "Español", "type" => "language"],
        ["name" => "LITERACY", "esName" => "Literatura", "type" => "language"],
        ["name" => "PHILOSOPHY", "esName" => "Filosofía", "type" => "language"],
        ["name" => "FRENCH", "esName" => "Francés", "type" => "language"],
        [
            "name" => "INTERACTIVE ENGLISH",
            "esName" => "Inglés interactivo",
            "type" => "language",
        ],
        [
            "name" => "SYSTEMS AND DESIGN",
            "esName" => "Sistemas",
            "type" => "tech",
        ],
        ["name" => "MATH", "esName" => "Matemáticas", "type" => "math"],
        ["name" => "SABER", "esName" => "Saber", "type" => "math"],
        [
            "name" => "MATHEMATICAL LOGIC CONNECTION",
            "esName" => "Matemáticas y conexión lógica",
            "type" => "math",
        ],
        ["name" => "PHYSICS", "esName" => "Física", "type" => "science"],
        ["name" => "CHEMISTRY", "esName" => "Química", "type" => "science"],
        [
            "name" => "SCIENCE",
            "esName" => "Ciencias Naturales",
            "type" => "science",
        ],
        [
            "name" => "SOCIAL STUDIES",
            "esName" => "Ciencias Sociales",
            "type" => "social",
        ],
        [
            "name" => "ETHICS AND SOCIAL COEXISTENCE",
            "esName" => "Ética y valores humanos",
            "type" => "ethics",
        ],
        ["name" => "RELIGION", "esName" => "Religión", "type" => "religion"],
        [
            "name" => "SOCIAL AND CULTURAL ENVIRONMENT",
            "esName" => "Ciencias sociales y culturales",
            "type" => "social",
        ],
        [
            "name" => "POLITICAL SCIENCES",
            "esName" => "Ciencias politicas",
            "type" => "social",
        ],
        [
            "name" => "SCHOOL BEHAVIOR",
            "esName" => "Convivencia Escolar",
            "type" => "environment",
        ],
        [
            "name" => "PARENT'S COMMITMENT",
            "esName" => "Compromiso de los padres",
            "type" => "environment",
        ],
    ];

    public function mount($usuarioId)
    {
        $this->usuarioId = $usuarioId;
        $this->generarInforme();
    }

    public function generarInforme()
    {
        // 1. Obtener Estudiante
        $estudiante = Usuario::with("grado", "grupo")->find($this->usuarioId);

        if (!$estudiante) {
            return []; // O manejar error
        }

        // 2. Obtener el Periodo
        /*$periodo = Periodo::where('fecha_fin', '>', now())->first();
        if (!$periodo) {
            return [];
        }
        $periodoId = $periodo->id - 1;
        // Ajuste manual de fechas según tu lógica
        if(date("j-n") >= "19-11"){
            $periodoId = $periodo->id;
        }*/

        $periodoId = 1;

        // 3. Obtener Materias del estudiante
        $materias = Materia::select(
            "materias.id",
            "base_materia.nombre_materia",
            "materias.intensidad_horaria",
        )
            ->join(
                "base_materia",
                "base_materia.id",
                "=",
                "materias.materia_id",
            )
            ->where("materias.grado_id", $estudiante->grado_id)
            ->where("materias.grupo_id", $estudiante->grupo_id)
            ->get();

        $materiaIds = $materias->pluck("id")->toArray();

        // 4. Obtener Notas Finales
        $notasFinales = NotaFinalMateria::where(
            "estudiante_id",
            $estudiante->id,
        )
            ->where("periodo_id", "<=", $periodoId)
            ->whereIn("materia_id", $materiaIds)
            ->get()
            ->groupBy("materia_id");

        dump($notasFinales);

        // 5. Obtener Notas Recuperación
        $notasRecuperacion = NotaRecuperacion::where(
            "estudiante_id",
            $estudiante->id,
        )
            ->whereIn("materia_id", $materiaIds)
            ->get()
            ->groupBy("materia_id");

        // 6. Estructura Base de Agrupación
        // Inicializamos el array con los grupos vacíos para mantener el orden
        $grouped = [];
        foreach ($this->subjectGroups as $group) {
            $grouped[$group["type"]] = [
                "name" => $group["name"],
                "materias" => [],
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
                $notaRecuperacion = $recuperaciones
                    ->where("periodo_id", $nota->periodo_id)
                    ->first();

                if ($notaRecuperacion) {
                    $sumaPromedio += max(
                        $nota->nota_final,
                        $notaRecuperacion->nota_final,
                    );
                } else {
                    $sumaPromedio += $nota->nota_final;
                }
                $countNotas++;
            }

            $promedioFinal =
                $countNotas > 0 ? round($sumaPromedio / $countNotas, 2) : 0;

            // Buscar datos en subjectsTree (Nombre ES y Tipo)
            $detalles = $this->getSubjectDetails($materia->nombre_materia);

            // Construir el objeto de la materia
            $datosMateria = [
                "nombre_original" => $materia->nombre_materia,
                "nombre_es" => $detalles["esName"],
                "ih" => $materia->intensidad_horaria,
                "promedio" => $promedioFinal,
                // Puedes agregar aquí más detalles como notas por periodo si la vista lo requiere
            ];

            // Asignar al grupo correspondiente
            $type = $detalles["type"];

            if (isset($grouped[$type])) {
                $grouped[$type]["materias"][] = $datosMateria;
            } else {
                // Si no encuentra grupo, crea uno genérico o lo mete en 'other'
                if (!isset($grouped["other"])) {
                    $grouped["other"] = [
                        "name" => "Otras Asignaturas",
                        "materias" => [],
                    ];
                }
                $grouped["other"]["materias"][] = $datosMateria;
            }
        }

        // Filtrar grupos que quedaron vacíos (opcional)
        $this->groupedMaterias = array_filter($grouped, function ($grupo) {
            return count($grupo["materias"]) > 0;
        });

        dd($this->groupedMaterias);
        return $this->groupedMaterias;
    }

    /**
     * Función auxiliar para buscar en el array de configuración
     */
    private function getSubjectDetails($nombreMateria)
    {
        $nombreUpper = trim(strtoupper($nombreMateria));

        foreach ($this->subjectsTree as $subject) {
            if ($subject["name"] === $nombreUpper) {
                return [
                    "esName" => $subject["esName"],
                    "type" => strtolower($subject["type"]),
                ];
            }
        }

        return [
            "esName" => $nombreMateria, // Si no encuentra traducción, usa el original
            "type" => "other",
        ];
    }
    public function render()
    {
        return view("livewire.pages.administrador.certificados");
    }
}
