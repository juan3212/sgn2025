<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoNota;

class TipoNotasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $tipo = [
            'Actitudinal',
            'Procedimental',
            'Cognitiva',
            'Reading',
            'Writing',
            'Speaking',
            'Listening'
        ];
        foreach ($tipo as $tipo) {
            TipoNota::create([
                'tipo' => $tipo
            ]);
        }
    }
}
