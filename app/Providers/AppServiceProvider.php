<?php

namespace App\Providers;

use App\Observers\TaskObserver;
use Illuminate\Support\ServiceProvider;
use App\Models\Task;

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
        //
        Task::observe(TaskObserver::class);
    }
}
