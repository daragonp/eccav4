<?php

namespace App\Providers;

use App\Services\BibleSQLiteService;
use Illuminate\Support\ServiceProvider;

class BibleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(BibleSQLiteService::class, function ($app) {
            return new BibleSQLiteService();
        });
    }
}