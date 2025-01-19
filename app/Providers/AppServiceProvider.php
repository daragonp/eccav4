<?php

namespace App\Providers;

use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
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
        //
        /*$onair = DB::table('schedules')
        ->where('day', '=', DB::raw('(SELECT DAYOFWEEK(NOW()))'))
        ->whereRaw('CURTIME() BETWEEN start AND end')
        ->first();
        view()->share('onair', $onair);*/
        Paginator::useBootstrap();
    }
}
