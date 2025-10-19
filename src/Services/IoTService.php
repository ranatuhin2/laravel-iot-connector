<?php

namespace RanaTuhin\LaravelIoTConnector\Services;

use RanaTuhin\LaravelIoTConnector\Models\Device;
use RanaTuhin\LaravelIoTConnector\Models\DeviceData;
use Illuminate\Support\Facades\Log;
use RanaTuhin\LaravelIoTConnector\Events\DeviceDataReceived;


class IoTService
{
     protected $defaultDriver;

     protected $driver;

     public function __construct()
     {
          $this->defaultDriver = config('iotconnector.default_driver', 'mqtt');

          switch ($this->defaultDriver) {
               case 'mqtt':
                    $this->driver = new \RanaTuhin\LaravelIoTConnector\Drivers\MqttDriver();
                    $this->driver->connect();
                    break;

               case 'http':
                    $this->driver = new \RanaTuhin\LaravelIoTConnector\Drivers\HttpDriver();
                    break;

               case 'websocket':
                    $this->driver = new \RanaTuhin\LaravelIoTConnector\Drivers\WebSocketDriver();
                    $this->driver->connect();
                    break;
          }
     }

     /**
      * Register a new device
      */
     public function registerDevice(string $name, string $protocol = null): Device
     {
          $protocol = $protocol ?? $this->defaultDriver;

          return Device::create([
               'name' => $name,
               'protocol' => $protocol,
               'token' => bin2hex(random_bytes(16)),
               'status' => 'offline',
          ]);
     }

     /**
      * Store data sent from device
      */
     public function storeDeviceData(string $deviceToken, array $data): ?array
     {
          $device = Device::where('token', $deviceToken)->first();

          if (!$device) return null;

          $device->status = 'online';
          $device->save();

          $records = [];

          foreach ($data as $key => $value) {
               $records[] = DeviceData::create([
                    'device_id' => $device->id,
                    'key' => $key,
                    'value' => $value,
               ]);
          }

          // Broadcast the event
          event(new DeviceDataReceived($device, $data));

          return $records;
     }

     /**
      * Send command to device
      */
     public function dispatchCommand(string $deviceToken, array $payload): bool
     {
          $device = Device::where('token', $deviceToken)->first();

          if (!$device) return false;

          $topicPrefix = config('iotconnector.topic_prefix', 'iot/devices');
          $topic = "{$topicPrefix}/{$deviceToken}/commands";

          $this->driver->publish($topic, json_encode($payload));

          return true;
     }


     /**
      * Placeholder for event listener
      */
     public function onDataReceived(callable $callback)
     {
          // Will implement event broadcasting later
     }


     public function listenToDeviceData()
     {
          $topicPrefix = config('iotconnector.topic_prefix', 'iot/devices');

          $this->driver->subscribe("{$topicPrefix}/+/data", function ($topic, $message) {
               // Extract device token from topic
               $parts = explode('/', $topic);
               $deviceToken = end($parts);

               $payload = json_decode($message, true);
               $this->storeDeviceData($deviceToken, $payload);
          });
     }
}
