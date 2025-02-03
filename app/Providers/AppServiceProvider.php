<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(\ComLaude\Amqp\AmqpServiceProvider::class);

        $loader = AliasLoader::getInstance();
        $loader->alias('Amqp', \ComLaude\Amqp\Facades\Amqp::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
