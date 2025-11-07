<?php

namespace Webkul\NewTheme\Providers;

use Illuminate\Support\ServiceProvider;

class NewThemeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
        __DIR__.'/../Resources/views'  => resource_path('themes/new-theme/views'),
    ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
