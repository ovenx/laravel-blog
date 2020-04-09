<?php

namespace App\Providers;

use App\Helpers\Hasher\MD5Hasher;
use Illuminate\Support\ServiceProvider;

class MD5HashServiceProvider extends ServiceProvider
{


    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('hash', function () {
            return new MD5Hasher;
        });
    }

    public function provides()
    {
    }
}
