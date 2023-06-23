<?php

namespace App\Providers;

use App\Models\Week;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
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
        // The view composer defers execution of weeks, which prevents issues
        // that we were having when migrating an empty database, since there is
        // no weeks table then, but the boot function has to run to run a
        // migration!
        View::composer('*', function ($view) {
            $this_week = \App\Models\Week::orderBy('start_date', 'asc')
                ->where('start_date', '>', Carbon::now()->subWeek())->first();

            $view->with('weeks', \App\Models\Week::orderBy('start_date', 'asc')->get())
                ->with('this_week', $this_week);
        });

        $this->app->singleton(Week::class, function ($app) {
            return Week::find(request()->cookie('week_id'));
        });
    }
}
