<?php

namespace App\Providers;

use App\Lib\SimpleLib;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);
        //register to set container when auto resolve
        $this->app->resolving(SimpleLib::class,function ($lib,$app){
            //when SimpleLib is resolve -> $lib is the object of SimpleLib class
            $lib->setContainer($app);
        });
    }
}
