<?php

namespace App\Providers;

use App\Models\Department;
use App\Observers\DepartmentObserver;
use App\Observers\EmployeeObserver;
use App\Observers\MessageObserver;
use App\Observers\TaskObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Models\{Task, Message, Employee};
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Support\Facades\FilamentTimezone;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

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
        $this->configureCommands();
        $this->configureModels();
        $this->configureUrl();
        $this->configureFilament();
        Task::observe(TaskObserver::class);
        Message::observe(MessageObserver::class);
        Department::observe(DepartmentObserver::class);
        Employee::observe(EmployeeObserver::class);
    }

    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->environment('production')
        );
    }

    private function configureModels(): void
    {
        Model::shouldBeStrict();
        Model::unguard();
    }

    public function configureUrl(): void
    {
        if ($this->app->environment('production')) {

            URL::forceScheme('https');
        }
    }

    public function configureFilament(): void
    {
        FilamentTimezone::set('Asia/Jakarta');

        Select::configureUsing(fn(Select $select) => $select
            ->native(false));

        DatePicker::configureUsing(fn(DatePicker $datePicker) => $datePicker
            ->format('d/m/Y')
            ->native(false)
            ->displayFormat('d/m/Y')
            ->locale('id')
            ->placeholder('dd/mm/yyyy'));

        FileUpload::configureUsing(fn(FileUpload $fileUpload) => $fileUpload
            ->visibility('public')
            ->openable()
            ->downloadable()
            ->previewable(true)
            ->maxSize(1024)
            ->imagePreviewHeight('100')
            ->loadingIndicatorPosition('left')
            ->panelAspectRatio('3:1')
            ->removeUploadedFileButtonPosition('right')
            ->uploadButtonPosition('left')
            ->uploadProgressIndicatorPosition('left'));
    }
}
