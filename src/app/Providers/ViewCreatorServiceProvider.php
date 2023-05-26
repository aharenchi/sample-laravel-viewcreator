<?php

namespace App\Providers;

use App\View\Creators\ViewSwitchCreator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewCreatorServiceProvider extends ServiceProvider
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
        View::creator("*", ViewSwitchCreator::class);
    }
}
