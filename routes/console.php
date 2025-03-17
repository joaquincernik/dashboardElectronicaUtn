<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

 
Schedule::command('app:desactivar-socios')->yearly();
Schedule::command('app:ingreso-semanal')->weekly()->fridays()->at('17:00');

//ver tema crontab
/**crontab -e
 * * * * * * php /ruta/a/tu/proyecto/artisan schedule:run >> /dev/null 2>&1

 */