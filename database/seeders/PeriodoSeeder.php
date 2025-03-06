<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Periodo;

class PeriodoSeeder extends Seeder
{
    
    public function run(): void
    {
        //
        $periodo = [
            ["id"=>1, "periodo"=>1],
            ["id"=>2, "periodo"=>2],
            ["id"=>3, "periodo"=>3],
            ["id"=>4, "periodo"=>4]
        ];
        foreach($periodo as $p){
            Periodo::create($p);
        }
    }
}
