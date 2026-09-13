<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class InformeMateriasReprobadasExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    protected array $informe;
    protected array $rows = [];

    public function __construct(array $informe)
    {
        $this->informe = $informe;
        $this->buildRows();
    }

    protected function buildRows(): void
    {
        foreach ($this->informe as $estudiante) {
            $nombre = $estudiante['estudiante'] ?? '';
            $grado = $estudiante['grado'] ?? '';
            $grupo = $estudiante['grupo'] ?? '';
            $materias = $estudiante['materias'] ?? [];
            $totalPerdidas = $estudiante['cantidad materias perdidas'] ?? count($materias);

            if (!empty($materias)) {
                foreach ($materias as $materia) {
                    $this->rows[] = [
                        'estudiante' => $nombre,
                        'grado' => $grado,
                        'grupo' => $grupo,
                        'materia' => $materia['materia'] ?? '',
                        'promedio' => isset($materia['promedio']) ? number_format((float)$materia['promedio'], 2, '.', '') : '',
                        'total_perdidas' => $totalPerdidas,
                    ];
                }
            } else {
                // Si el estudiante está en el informe pero sin desglose de materias
                $this->rows[] = [
                    'estudiante' => $nombre,
                    'grado' => $grado,
                    'grupo' => $grupo,
                    'materia' => '',
                    'promedio' => '',
                    'total_perdidas' => $totalPerdidas,
                ];
            }
        }
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'Estudiante',
            'Grado',
            'Grupo',
            'Materia Reprobada',
            'Nota / Promedio',
            'Total Materias Perdidas',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E40AF'], // Azul corporativo (blue-800)
                ],
            ],
        ];
    }
}
