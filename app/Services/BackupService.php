<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Task;
use App\Exports\TaskMessageExport;
use Maatwebsite\Excel\Facades\Excel;
class BackupService
{
    public function dailyBackupTaskMessages()
    {
        $this->backupTaskMessages('every_day');
    }

    public function weeklyBackupTaskMessages()
    {
        $this->backupTaskMessages('once_a_week');
    }

    public function monthlyBackupTaskMessages()
    {
        $this->backupTaskMessages('once_a_month');
    }

    /**
     * General method to backup task messages based on frequency.
     *
     * @param string $frequencySlug
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function backupTaskMessages(string $frequencySlug)
    {
        $tasks = Task::where('status', true)->get();
        $now = Carbon::now();
        $frequency = null;

        switch ($frequencySlug) {
            case 'every_day':
                $frequency = Carbon::yesterday()->setTime($now->hour, $now->minute);
                break;
            case 'once_a_week':
                $frequency = Carbon::now()->subWeek()->setTime($now->hour, $now->minute);
                break;
            case 'once_a_month':
                $frequency = Carbon::now()->subMonth()->setTime($now->hour, $now->minute);
                break;
        }

        foreach ($tasks as $task) {
            if ($task->reportFrequencies()->where('slug', $frequencySlug)->exists()) {
                $data = $task->messages()->whereBetween('created_at', [$frequency, $now])->get();
                return $data;
            }
        }

        return collect();
    }
}
