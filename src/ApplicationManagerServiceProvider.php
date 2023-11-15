<?php

namespace Risky2k1\ApplicationManager;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Risky2k1\ApplicationManager\Console\ApplicationManagerCommand;
use Risky2k1\ApplicationManager\Http\Middleware\ValidateApplicationTypeMiddleware;

class ApplicationManagerServiceProvider extends ServiceProvider
{
    public function register()
    {
        //Add a Facades
        $this->app->bind('application', function ($app) {
            return new ApplicationManager();
        });

        //Add config
        $this->mergeConfigFrom(__DIR__ . '/../config/application-manager.php', 'application-manager');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ApplicationManagerCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/application-manager.php' => config_path('application-manager.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../database/migrations/create_applications_tables.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_applications_tables.php'),
                // you can add any number of migrations here
            ], 'migrations');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/pages'),
            ], 'views');
            
        }
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'application-manager');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'application-manager');
        //add Middleware
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('application.type', ValidateApplicationTypeMiddleware::class);
        $this->registerRoutes();

    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => config('application-manager.prefix'),
            'middleware' => config('application-manager.middleware'),
        ];
    }
}
