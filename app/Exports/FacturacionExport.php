<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FacturacionExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Fecha de facturacion',
            'Nombre estudiante',
            'Apellido estudiante',
            'Grado',
            'NUIP estudiante',
            'Nombre del Contacto',
            'Apellido del Contacto',
            'NUIP del Contacto',
            'Telefono del Contacto',
            'Correo del Contacto',
            'Direccion'
        ];
    }

    public function map($row): array
    {
   
        return [
            $row->fecha_facturacion ?? '',
            $row->nombre ?? '',
            $row->apellido ?? '',
            $row->grado ?? '',
            $row->nuip ?? '',
            $row->nombre_acudiente ?? '',
            $row->apellido_acudiente ?? '',
            $row->nuip_acudiente ?? '',
            $row->telefono ?? '',
            $row->email ?? '',
            $row->direccion ?? ''
        ];
    }
}
