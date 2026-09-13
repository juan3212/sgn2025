<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class PlanillaNotasPeriodoExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle
{
    protected $materia_id;
    protected string $nombre_materia;
    protected string $grado_nombre;
    protected string $grupo_nombre;
    protected $periodo_id;
    protected $competencias;
    protected $estudiantes;

    protected array $rows = [];
    protected array $mergeRanges = [];
    protected int $totalColumns = 2;
    protected int $totalRows = 2;
    protected int $lastActivityColIndex = 2;

    public function __construct(
        $materia_id,
        string $nombre_materia,
        string $grado_nombre,
        string $grupo_nombre,
        $periodo_id,
        $competencias,
        $estudiantes
    ) {
        $this->materia_id = $materia_id;
        $this->nombre_materia = $nombre_materia;
        $this->grado_nombre = $grado_nombre;
        $this->grupo_nombre = $grupo_nombre;
        $this->periodo_id = $periodo_id;
        $this->competencias = $competencias;
        $this->estudiantes = $estudiantes;

        $this->buildExportData();
    }

    protected function buildExportData(): void
    {
        $isBehavior = trim(strtoupper($this->nombre_materia)) === 'SCHOOL BEHAVIOR';

        // 1. Fila 1 de encabezados (Competencias)
        $headerRow1 = ['Nombre', 'Apellido'];
        // 2. Fila 2 de encabezados (Actividades)
        $headerRow2 = ['Nombre', 'Apellido'];

        $currentColIndex = 3; // Las actividades empiezan en la columna C (índice 3)

        // Asegurar que las competencias sean únicas por ID
        $competenciasUnicas = collect($this->competencias)->unique('id');

        // Mapear cada competencia con sus actividades estrictamente únicas de esta materia y periodo
        $competenciasConActividades = [];
        $seenActividadIds = [];

        foreach ($competenciasUnicas as $competencia) {
            $actividades = collect($competencia->actividades ?? [])
                ->filter(function ($act) {
                    return (int) $act->materia_id === (int) $this->materia_id
                        && (int) $act->periodo_id === (int) $this->periodo_id;
                })
                ->unique('id')
                ->filter(function ($act) use (&$seenActividadIds) {
                    if (in_array($act->id, $seenActividadIds)) {
                        return false;
                    }
                    $seenActividadIds[] = $act->id;
                    return true;
                })
                ->values();

            $count = $actividades->count();
            if ($count > 0) {
                $competenciasConActividades[] = [
                    'competencia' => $competencia,
                    'actividades' => $actividades,
                ];

                $compTitle = trim($competencia->nombre);
                if (!empty($competencia->porcentaje)) {
                    $compTitle .= " ({$competencia->porcentaje}%)";
                }

                $headerRow1[] = $compTitle;
                // Rellenar espacios vacíos para la fusión horizontal de la competencia
                for ($k = 1; $k < $count; $k++) {
                    $headerRow1[] = '';
                }

                $startColLetter = Coordinate::stringFromColumnIndex($currentColIndex);
                $endColLetter = Coordinate::stringFromColumnIndex($currentColIndex + $count - 1);

                if ($count > 1) {
                    $this->mergeRanges[] = "{$startColLetter}1:{$endColLetter}1";
                }

                foreach ($actividades as $idx => $actividad) {
                    $actNombre = $actividad->nombre ?: $actividad->descripcion ?: "Actividad " . ($idx + 1);
                    $headerRow2[] = "Act. " . ($idx + 1) . " - " . $actNombre;
                }

                $currentColIndex += $count;
            }
        }

        $this->lastActivityColIndex = $currentColIndex - 1;

        if ($isBehavior) {
            $headerRow1[] = 'Comentarios';
            $headerRow2[] = 'Comentario Periodo ' . $this->periodo_id;
            $behaviorColLetter = Coordinate::stringFromColumnIndex($currentColIndex);
            $this->mergeRanges[] = "{$behaviorColLetter}1:{$behaviorColLetter}1";
            $currentColIndex++;
        }

        $this->totalColumns = $currentColIndex - 1;

        // Fusiones verticales de Nombre y Apellido
        $this->mergeRanges[] = 'A1:A2';
        $this->mergeRanges[] = 'B1:B2';

        $this->rows[] = $headerRow1;
        $this->rows[] = $headerRow2;

        // 3. Filas de Estudiantes ordenados por apellido y nombre
        $estudiantesOrdenados = collect($this->estudiantes)->sortBy([
            ['apellido', 'asc'],
            ['nombre', 'asc'],
        ], SORT_NATURAL | SORT_FLAG_CASE)->values();

        foreach ($estudiantesOrdenados as $estudiante) {
            $row = [
                $estudiante->nombre ?? '',
                $estudiante->apellido ?? '',
            ];

            foreach ($competenciasConActividades as $item) {
                $actividades = $item['actividades'];
                foreach ($actividades as $actividad) {
                    $nota = $estudiante->notas ? $estudiante->notas->firstWhere('actividad_id', $actividad->id) : null;
                    $valor = '';
                    if ($nota) {
                        if (!empty($nota->observacion)) {
                            $valor = $nota->observacion;
                        } elseif ($nota->valor !== null && $nota->valor !== '') {
                            $valor = (float) $nota->valor;
                        }
                    }
                    $row[] = $valor;
                }
            }

            if ($isBehavior) {
                $comentario = '';
                if ($estudiante->comentariosPeriodo) {
                    $comentarioObj = $estudiante->comentariosPeriodo->firstWhere('periodo_id', $this->periodo_id);
                    $comentario = $comentarioObj->comentario ?? '';
                }
                $row[] = $comentario;
            }

            $this->rows[] = $row;
        }

        $this->totalRows = count($this->rows);
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function title(): string
    {
        $materiaShort = substr(preg_replace('/[^A-Za-z0-9]/', '', $this->nombre_materia), 0, 15);
        return substr("{$materiaShort}_{$this->grado_nombre}_{$this->grupo_nombre}_P{$this->periodo_id}", 0, 31);
    }

    public function styles(Worksheet $sheet)
    {
        // 1. Aplicar fusiones de celdas
        foreach ($this->mergeRanges as $range) {
            $sheet->mergeCells($range);
        }

        $lastColLetter = Coordinate::stringFromColumnIndex($this->totalColumns);
        $totalRows = max($this->totalRows, 2);

        // 2. Inmovilizar paneles (Nombre, Apellido y Encabezados fijos)
        $sheet->freezePane('C3');

        // 3. Estilos de encabezado Fila 1 (Competencias)
        $sheet->getStyle("A1:{$lastColLetter}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E3A8A'], // Azul marino (blue-900)
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // 4. Estilos de encabezado Fila 2 (Actividades)
        $sheet->getStyle("A2:{$lastColLetter}2")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '1E293B'], // Slate 800
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DBEAFE'], // Azul claro (blue-100)
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // Asegurar que Nombre y Apellido estén centrados verticalmente
        $sheet->getStyle('A1:B2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // 5. Estilos para toda la tabla (bordes delgados)
        $sheet->getStyle("A1:{$lastColLetter}{$totalRows}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'], // Gray-300
                ],
            ],
        ]);

        // 6. Alineación de datos
        if ($totalRows >= 3) {
            // Nombres y apellidos alineados a la izquierda
            $sheet->getStyle("A3:B{$totalRows}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            // Celdas de notas centradas
            $sheet->getStyle("C3:{$lastColLetter}{$totalRows}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Altura de filas de encabezado
        $sheet->getRowDimension(1)->setRowHeight(28);
        $sheet->getRowDimension(2)->setRowHeight(25);

        // 7. Validación de datos para celdas de notas (número mayor a 0 y menor o igual a 10, o NA)
        if ($this->lastActivityColIndex >= 3 && $totalRows >= 3) {
            $lastActColLetter = Coordinate::stringFromColumnIndex($this->lastActivityColIndex);
            $validationRange = "C3:{$lastActColLetter}{$totalRows}";

            $validation = new DataValidation();
            $validation->setType(DataValidation::TYPE_CUSTOM);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Nota inválida');
            $validation->setError('La nota debe ser un número mayor a 0 y menor o igual a 10 (o NA).');
            $validation->setPromptTitle('Ingreso de notas');
            $validation->setPrompt('Ingrese una nota entre 0.1 y 10 (o NA).');
            $validation->setFormula1('OR(AND(ISNUMBER(C3),C3>0,C3<=10),UPPER(TRIM(C3))="NA")');
            $validation->setSqref($validationRange);

            $sheet->setDataValidation($validationRange, $validation);
        }

        return [];
    }
}
