<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\{
    Task,
    TechnicalSupport
};
use App\Observers\{
    TaskObserver,
    TechnicalSupportObserver
};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Task::observe(TaskObserver::class);
        TechnicalSupport::observe(TechnicalSupportObserver::class);
    }
}
