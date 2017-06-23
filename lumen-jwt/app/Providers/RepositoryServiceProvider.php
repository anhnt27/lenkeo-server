<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * The classes to scan for event annotations.
     *
     * @var array
     */
    protected $scan = [
        //
    ];
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'event.name' => [
            'RepositoryListener',
        ],
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\PlayerInterface',
            'App\Repositories\Eloquent\PlayerRepository'
        );
    }
}
