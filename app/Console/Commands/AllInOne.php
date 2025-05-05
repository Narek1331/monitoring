<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\{
    CheckService,
    NotificationService
};
class AllInOne extends Command
{
    public $checkService;
    public $notificationService;

    public function __construct(
        CheckService $checkService,
        NotificationService $notificationService
    )
    {
        $this->checkService = $checkService;
        $this->notificationService = $notificationService;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:all-in-one';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->checkService->index();
        $this->notificationService->send();
    }
}
