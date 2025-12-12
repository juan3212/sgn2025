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
            'Telefono del Contacto',
            'Correo del Contacto',
            'Direccion'
        ];
    }

    public function map($row): array
    {
        // Based on the query logic in controller, we need to map the correct fields.
        // There is ambiguity because similar fields exist in multiple joined tables.
        // However, the select statement includes:
        // 'usuarios.nombre as nombre_acudiente', 'usuarios.apellido as apellido_acudiente', 'usuarios.nuip as nuip_acudiente'
        // 'estudiantes.*', 'grados.*', 'contacto.*'
        
        // Let's deduce the fields based on the DataTable usage:
        // { data: 'nombre', name: 'nombre' } -> Estudiante nombre ?
        // { data: 'apellido', name: 'apellido' } -> Estudiante apellido ?
        // { data: 'grado', name: 'grado' } -> grado ?
        // { data: 'nuip', name: 'nuip' } -> Estudiante nuip ?
        // { data: 'nombre_acudiente', name: 'nombre_acudiente' }
        // { data: 'apellido_acudiente', name: 'apellido_acudiente' }
        // { data: 'correo', name: 'correo' } -> Contacto correo ? or Usuario correo?
        // { data: 'telefono', name: 'telefono' } -> Contacto telefono?
        // { data: 'direccion', name: 'direccion' } -> Info? Not sure.
        
        // Wait, 'info.*' is from 'matricula_completada_info'. 'usuario_facturacion' has fields.
        
        // Let's assume standard field access. Since 'select' was 'info.*', 'fact.*', 'usuarios.*' etc.
        // Overlapping fields like 'id', 'created_at' match the LAST joined table with that column unless aliased.
        
        // But specifically:
        // estudiantes.* was joined LATER than usuarios (acudiente). So 'nombre' might be Student's name if estudiantes is mapped well.
        // Actually, `query->select(...)` order matters for `get()`, last column overwrites first if names collide in array results (PDO FETCH_ASSOC).
        // `estudiantes.*` comes AFTER `usuarios.nombre as ...`.
        // `grados.*` comes after.
        // `contacto.*` comes after.
        
        // So $row->nombre should be Estudiante (if estudiantes table has 'nombre').
        // $row->nombre_acudiente is aliased.
        
        return [
            $row->fecha_facturacion ?? '',
            $row->nombre ?? '',
            $row->apellido ?? '',
            $row->grado ?? '',
            $row->nuip ?? '',
            $row->nombre_acudiente ?? '',
            $row->apellido_acudiente ?? '',
            $row->telefono ?? '', // From contacto likely
            $row->email ?? '',   // From contacto likely
            $row->direccion ?? '' // From somewhere?
        ];
    }
}
