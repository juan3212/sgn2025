<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea una clase de servicio en app/Services';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path('Services/' . $name . '.php');

        // Crear carpeta si no existe
        if (!File::exists(app_path('Services'))) {
            File::makeDirectory(app_path('Services'), 0755, true);
        }

        // Evitar sobreescritura
        if (File::exists($path)) {
            $this->error("El servicio {$name} ya existe.");
            return;
        }

        // Plantilla del archivo
        $stub = <<<EOT
        <?php

        namespace App\Services;

        class {$name}
        {
            public function __construct()
            {
                //
            }
        }
        EOT;

        File::put($path, $stub);

        $this->info("Servicio {$name} creado exitosamente en app/Services.");
    }

}
