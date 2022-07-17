<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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
        View::share('weeks', \App\Models\Week::all());
        $this_week = \App\Models\Week::orderBy('start_date', 'asc')
            ->where('start_date', '>', Carbon::now()->subWeek())->first();
        View::share('this_week', $this_week);

    }
}
