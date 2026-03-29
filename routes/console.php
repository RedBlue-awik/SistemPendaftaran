<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('pmb:auto-tutup')->everyMinute();
Schedule::command('pmb:auto-gugur')->everyMinute();
Schedule::command('pmb:gelombang-selesai')->dailyAt('00:00');