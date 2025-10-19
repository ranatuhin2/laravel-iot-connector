<?php

namespace RanaTuhin\LaravelIoTConnector\Console;

use Illuminate\Console\Command;
use RanaTuhin\LaravelIoTConnector\Facades\IoT;

class SendDeviceCommand extends Command
{
     protected $signature = 'iot:send-command
                            {token : Device token}
                            {payload : JSON payload for command}';

     protected $description = 'Send a command to a device';

     public function handle()
     {
          $token = $this->argument('token');
          $payload = $this->argument('payload');

          $data = json_decode($payload, true);

          if (json_last_error() !== JSON_ERROR_NONE) {
               $this->error('Invalid JSON payload');
               return 1;
          }

          $success = IoT::dispatchCommand($token, $data);

          if ($success) {
               $this->info("Command sent to device successfully!");
          } else {
               $this->error("Device not found or command failed.");
          }
     }
}
