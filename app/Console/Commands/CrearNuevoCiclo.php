<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class CrearNuevoCiclo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:crear-nuevo-ciclo {year : Año del nuevo ciclo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clona la base de datos actual, crea la del nuevo año y limpia las tablas transaccionales';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $newYear = $this->argument('year');
        $dbOrigen = DB::connection()->getDatabaseName();
        $dbDestino = 'sgn' . $newYear;

        $this->info("Iniciando proceso: $dbOrigen -> $dbDestino");

        // 1. Verificar si ya existe
        $exists = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbDestino]);
        if (!empty($exists)) {
            $this->error("¡La base de datos $dbDestino ya existe! Proceso abortado para evitar borrar datos.");
            return;
        }

        // 2. Crear la nueva Base de Datos
        $this->info("Creando base de datos $dbDestino...");
        DB::statement("CREATE DATABASE $dbDestino CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // 3. Clonar Estructura y Datos (Usando mysqldump es lo más seguro)
        // Nota: Esto requiere que 'mysqldump' y 'mysql' estén en las variables de entorno de tu servidor/PC
        $usuario = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');
        
        // Comando para clonar (Exportar | Importar en una sola línea)
        // Usamos --routines para copiar procedimientos almacenados si tienes
        $cmd = "mysqldump -h $host -u $usuario " . ($password ? "-p$password" : "") . " --routines $dbOrigen | mysql -h $host -u $usuario " . ($password ? "-p$password" : "") . " $dbDestino";
        
        $this->info("Clonando tablas y datos (esto puede tardar unos segundos)...");
        // Ejecutar comando en terminal del sistema
        exec($cmd, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error("Error al clonar la base de datos. Verifica que mysqldump esté instalado.");
            // Opcional: Borrar la DB vacía creada
            DB::statement("DROP DATABASE $dbDestino");
            return;
        }

        // 4. Conectarse a la NUEVA base de datos para limpiar
        $this->info("Limpiando datos antiguos en $dbDestino...");
        
        // Cambiar conexión en caliente
        Config::set('database.connections.mysql.database', $dbDestino);
        DB::purge('mysql');
        DB::reconnect('mysql');

        // 5. Ejecutar Limpieza (Truncate)
        // Desactivar FK checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tablasParaLimpiar = [
            'notas',
            'notas_finales_materias',
            'notas_finales_competencias',
            'notas_recuperaciones',
            'actividades',
            'competencias',    
            'materias',    
            'materia_has_competencia',        
            'pago_matricula',
            'usuario_estado_pago',
            'usuarios_promovidos',
            'usuario_bloqueado'
            // Agrega aquí cualquier otra tabla que deba empezar vacía
        ];

        foreach ($tablasParaLimpiar as $tabla) {
            if (Schema::hasTable($tabla)) {
                DB::table($tabla)->truncate();
                $this->line(" - Tabla $tabla limpiada.");
            }
        }
        $this->info("4. Ejecutando promoción de estudiantes...");
        $resultados = $this->promoverEstudiantes();

        // Reactivar FK checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info("=== PROCESO COMPLETADO ===");
        $this->info("Resumen de Promoción:");
        $this->info("- Promovidos por regla especial: " . $resultados['excepcion']);
        $this->info("- Promovidos al siguiente grado: " . $resultados['regular']);
        $this->info("- Sin cambios (Grado Máximo): " . $resultados['sin_cambios']);
    }

    private function promoverEstudiantes()
    {
        $countExcepcion = 0;
        $countGeneral = 0;
        $countGraduados = 0; // Contador para los que salen del colegio

        // 1. Obtener ID del grado máximo (Asumimos que el ID más alto es el grado 11)
        $maxGradoId = DB::table('grados')->max('id');

        // 2. Listado de excepciones (Repitentes o Promoción Anticipada)
        // Clave: usuario_id, Valor: grado_destino
        $excepciones = DB::table('usuarios_promovidos')->pluck('grado_destino', 'usuario_id');
        
        // 3. Todos los estudiantes matriculados (Copia exacta del 2025)
        $matriculas = DB::table('usuario_grado')->get();

        foreach ($matriculas as $matricula) {
            $nuevoGrado = null;
            $esGraduado = false;

            // --- CASO A: REGLA ESPECIAL (Prioridad Máxima) ---
            // Si está en la lista (ej. Repitente de 11 o Sobresaliente de 8 a 10)
            if (isset($excepciones[$matricula->usuario_id])) {
                $nuevoGrado = $excepciones[$matricula->usuario_id];
                $countExcepcion++;
            } 
            // --- CASO B: PROMOCIÓN NORMAL ---
            // Si NO es el último grado, pasa al siguiente
            elseif ($matricula->grado_id < $maxGradoId) {
                $nuevoGrado = $matricula->grado_id + 1;
                $countGeneral++;
            } 
            // --- CASO C: GRADUADOS (Grado 11 que NO repite) ---
            else {
                $esGraduado = true;
                $countGraduados++;
            }

            // --- APLICAR CAMBIOS ---
            if ($nuevoGrado) {
                // Actualizamos el grado
                DB::table('usuario_grado')
                    ->where('id', $matricula->id)
                    ->update([
                        'grado_id' => $nuevoGrado,
                        'updated_at' => now()
                    ]);
            } elseif ($esGraduado) {
                // ¡AQUÍ ESTÁ LA CORRECCIÓN!
                // Si se gradúa, lo sacamos de la tabla de matrículas del 2026
                DB::table('usuario_grado')->delete($matricula->id);
            }
        }

        return [
            'excepcion' => $countExcepcion,
            'regular' => $countGeneral,
            'sin_cambios' => $countGraduados // Ahora representa a los eliminados/graduados
        ];
    }
}
