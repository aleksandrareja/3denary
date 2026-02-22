<?php

namespace Webkul\CustomPrzelewy24Payment\Providers;

use Illuminate\Support\ServiceProvider;

class CustomPrzelewy24PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // merge payment method configuration
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/payment-methods.php',
            'payment_methods'
        );

        // merge system configuration  
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}