<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromQuery;


class UsersExport implements FromQuery, WithHeadings, WithMapping
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
            "Nombre",
            "Apellido",
            "Nuip",
            "Grado",
            "Grupo"
        ];
    }

    public function map($row): array
    {
        return [
            $row->nombre,
            $row->apellido,
            $row->nuip,
            $row->grado,
            $row->grupo,
        ];
    }
}
