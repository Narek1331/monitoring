<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');

Schedule::command('app:check')->everyMinute();
Schedule::command('app:send-notification')->everyMinute();

Schedule::command('app:daily-backup-task-message')->daily();
Schedule::command('app:weekly-backup-task-message')->weekly();
Schedule::command('app:monthly-backup-task-message')->monthly();
