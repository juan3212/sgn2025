<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grupo;

class GruposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grupos = ['A', 'B', 'C', 'D'];
    
        foreach ($grupos as $grupo) {
            Grupo::create(['grupo' => $grupo]);
        }

    }
}