<?php

namespace App\Providers;

use App\Models\Report;
use App\Observers\ReportObserve;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Report::observe(ReportObserve::class);
    }
}
