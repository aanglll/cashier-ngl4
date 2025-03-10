<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    // public function boot()
    // {
    //     // Set locale Carbon ke Bahasa Indonesia
    //     Config::set('app.locale', 'id');
    //     Carbon::setLocale('id');
    // }
}
