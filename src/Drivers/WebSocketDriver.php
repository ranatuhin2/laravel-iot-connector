<?php

namespace RanaTuhin\LaravelIoTConnector\Drivers;

use Ratchet\Client\connect;
use Illuminate\Support\Facades\Log;

class WebSocketDriver
{
     protected $url;
     protected $connection;

     public function __construct()
     {
          $this->url = config('iotconnector.websocket_url', 'ws://127.0.0.1:8080');
     }

     /**
      * Connect to WebSocket server
      */
     public function connect()
     {
          try {
               $this->connection = connect($this->url)->then(function ($conn) {
                    $conn->on('message', function ($msg) {
                         Log::info("WebSocketDriver: Received message: {$msg}");
                         // Later, you can parse and store the data
                    });
               });
          } catch (\Exception $e) {
               Log::error("WebSocketDriver: Connection failed - " . $e->getMessage());
          }
     }

     /**
      * Send command via WebSocket
      */
     public function sendCommand(string $deviceToken, array $payload)
     {
          if ($this->connection) {
               $this->connection->send(json_encode([
                    'token' => $deviceToken,
                    'payload' => $payload
               ]));
          }
     }
}
