<?php

namespace RanaTuhin\LaravelIoTConnector\Console;

use Illuminate\Console\Command;
use RanaTuhin\LaravelIoTConnector\Facades\IoT;

class RegisterDeviceCommand extends Command
{
     protected $signature = 'iot:register
                            {name : The name of the device}
                            {protocol=mqtt : Protocol (mqtt/http/websocket)}';

     protected $description = 'Register a new IoT device';

     public function handle()
     {
          $name = $this->argument('name');
          $protocol = $this->argument('protocol');

          $device = IoT::registerDevice($name, $protocol);

          $this->info("Device '{$device->name}' registered successfully!");
          $this->line("Token: {$device->token}");
          $this->line("Protocol: {$device->protocol}");
     }
}
