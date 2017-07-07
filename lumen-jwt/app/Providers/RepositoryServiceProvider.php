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
        $this->app->bind(
            'App\Repositories\CityInterface',
            'App\Repositories\Eloquent\CityRepository'
        );
        $this->app->bind(
            'App\Repositories\PropertyInterface',
            'App\Repositories\Eloquent\PropertyRepository'
        );
        $this->app->bind(
            'App\Repositories\TeamFindingPlayerInterface',
            'App\Repositories\Eloquent\TeamFindingPlayerRepository'
        );
        $this->app->bind(
            'App\Repositories\TeamFindingMatchInterface',
            'App\Repositories\Eloquent\TeamFindingMatchRepository'
        );
        $this->app->bind(
            'App\Repositories\PlayerFindingTeamInterface',
            'App\Repositories\Eloquent\PlayerFindingTeamRepository'
        );
        $this->app->bind(
            'App\Repositories\NotificationInterface',
            'App\Repositories\Eloquent\NotificationRepository'
        );
        $this->app->bind(
            'App\Repositories\NotificationSettingInterface',
            'App\Repositories\Eloquent\NotificationSettingRepository'
        );
        $this->app->bind(
            'App\Repositories\StadiumInterface',
            'App\Repositories\Eloquent\StadiumRepository'
        );
        $this->app->bind(
            'App\Repositories\SettingInterface',
            'App\Repositories\Eloquent\SettingRepository'
        );
        $this->app->bind(
            'App\Repositories\TeamInterface',
            'App\Repositories\Eloquent\TeamRepository'
        );
        $this->app->bind(
            'App\Repositories\JoinInterface',
            'App\Repositories\Eloquent\JoinRepository'
        );
        $this->app->bind(
            'App\Repositories\MatchInterface',
            'App\Repositories\Eloquent\MatchRepository'
        );
    }
}
