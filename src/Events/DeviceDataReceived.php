<?php

namespace RanaTuhin\LaravelIoTConnector\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use RanaTuhin\LaravelIoTConnector\Models\Device;

class DeviceDataReceived implements ShouldBroadcast
{
     use Dispatchable, InteractsWithSockets, SerializesModels;

     public $device;
     public $data;

     /**
      * Create a new event instance.
      */
     public function __construct(Device $device, array $data)
     {
          $this->device = $device;
          $this->data = $data;
     }

     /**
      * Get the channels the event should broadcast on.
      */
     public function broadcastOn(): Channel
     {
          return new PrivateChannel('iot-device.' . $this->device->token);
     }

     public function broadcastWith(): array
     {
          return [
               'device_name' => $this->device->name,
               'data' => $this->data,
          ];
     }
}
