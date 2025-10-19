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
     }
}
