<?php

namespace RanaTuhin\LaravelIoTConnector\Console;

use Illuminate\Console\Command;
use RanaTuhin\LaravelIoTConnector\Drivers\MqttDriver;

class ListenToDevices extends Command
{
     protected $signature = 'iot:listen';
     protected $description = 'Listen for incoming device data via MQTT';

     public function handle()
     {
          $this->info('Starting MQTT listener...');
          $mqtt = new MqttDriver();
          $mqtt->subscribeAllDevices(); // You implement this to subscribe all registered devices
     }
}
