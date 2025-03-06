<?php

namespace App\Console\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Command;
use App\Models\Persona; 
use App\Models\PersonaExterna; 
use App\Models\CuotaSocio; 
class DesactivarSocios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:desactivar-socios';
    protected $description = 'Desactiva a todos los socios';

    /**
     * The console command description.
     *
     * @var string
     */
   
    /**
     * Execute the console command.
     */
    public function handle()
    {
        Persona::query()->update(['mesesAbonados' => ""]);
        Persona::where('socio', 1)
        ->update(['socio' => 0]);

        PersonaExterna::query()->update(['mesesAbonados' => ""]);
        PersonaExterna::where('socio', 1)
        ->update(['socio' => 0]);

        CuotaSocio::truncate();

    }


}
