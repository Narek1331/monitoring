<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WeeklyBackupTaskMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:weekly-backup-task-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\BackupService $backupService)
    {
        $backupService->weeklyBackupTaskMessages();
    }
}
