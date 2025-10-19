<?php

namespace RanaTuhin\LaravelIoTConnector;

use Illuminate\Support\ServiceProvider;

class IoTServiceProvider extends ServiceProvider
{
     /**
      * Register services.
      */
     public function register(): void
     {
          // Merge package config
          $this->mergeConfigFrom(__DIR__ . '/../config/iotconnector.php', 'iotconnector');


          // Bind IoTService to the container
          $this->app->singleton('iotconnector', function ($app) {
               return new \RanaTuhin\LaravelIoTConnector\Services\IoTService();
          });
     }

     /**
      * Bootstrap services.
      */
     public function boot(): void
     {
          // Load package routes
          $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

          // Publish config file
          $this->publishes([
               __DIR__ . '/../config/iotconnector.php' => config_path('iotconnector.php'),
          ], 'config');

          $this->publishes([
               __DIR__ . '/../database/migrations/' => database_path('migrations'),
          ], 'migrations');

          if ($this->app->runningInConsole()) {
               $this->commands([
                    \RanaTuhin\LaravelIoTConnector\Console\RegisterDeviceCommand::class,
                    \RanaTuhin\LaravelIoTConnector\Console\SendDeviceCommand::class,
               ]);
          }
     }
}
