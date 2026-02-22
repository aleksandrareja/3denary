<?php

namespace Webkul\CustomInpostPaczkomatyShipping\Providers;

use Illuminate\Support\ServiceProvider;

class CustomInpostPaczkomatyShippingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // merge carrier configuration
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/carriers.php',
            'carriers'
        );

        // merge system configuration  
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/config.php',
            'inpostshipping'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
       
    }
}