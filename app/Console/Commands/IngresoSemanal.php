<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Venta as Venta; 
use App\Models\Compra as Compra; 
use App\Models\Ingreso as Ingreso; 

use Carbon\Carbon;
class IngresoSemanal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ingreso-semanal';
    protected $description = 'Calcula los ingresos semanales';

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

        $inicioSemana = Carbon::now()->startOfWeek();
        $finSemana = Carbon::now()->endOfWeek();

        $totalVentas = Venta::whereBetween('created_at', [$inicioSemana, $finSemana])
        ->sum('precioVenta');

        $totalCompras = Compra::whereBetween('created_at', [$inicioSemana, $finSemana])
        ->sum('monto');

        $total = $totalVentas - $totalCompras;

        // Obtener el número de la semana actual
        $numeroSemana = $inicioSemana->weekOfYear;
        $año = $inicioSemana->year;

        $detalle = 'Compras:$'.number_format($totalCompras, 0, '.', '.').', Ventas:$'.number_format($totalVentas, 0, '.', '.');
        
       
            // Registrar el ingreso en la tabla
        Ingreso::create([
            'inicioSemana' => $inicioSemana,
            'finSemana' => $finSemana,
            'anio' => $año,
            'monto' => $total,
            'detalle' => $detalle
            ]);

            //para ejecutar el comando de ingresos, 

        $this->info("Ingreso semanal registrado: Semana $numeroSemana - Monto: $total");

       

    }


}