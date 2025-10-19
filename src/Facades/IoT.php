<?php

namespace RanaTuhin\LaravelIoTConnector\Facades;

use Illuminate\Support\Facades\Facade;

class IoT extends Facade
{
     /**
      * Get the registered name of the component in the service container.
      *
      * @return string
      */
     protected static function getFacadeAccessor(): string
     {
          return 'iotconnector';
     }
}
