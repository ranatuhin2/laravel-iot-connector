<?php

namespace RanaTuhin\LaravelIoTConnector\Drivers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HttpDriver
{
     protected $baseUrl;

     public function __construct()
     {
          // Example: base URL of IoT devices API
          $this->baseUrl = config('iotconnector.http_base_url', 'http://127.0.0.1:8001/api/devices');
     }

     /**
      * Send data to a device via HTTP POST
      */
     public function sendCommand(string $deviceToken, array $payload): bool
     {
          try {
               $response = Http::post("{$this->baseUrl}/{$deviceToken}/command", $payload);
               return $response->successful();
          } catch (\Exception $e) {
               Log::error("HTTPDriver: Failed to send command - " . $e->getMessage());
               return false;
          }
     }

     /**
      * Fetch latest device data (polling)
      */
     public function fetchDeviceData(string $deviceToken): ?array
     {
          try {
               $response = Http::get("{$this->baseUrl}/{$deviceToken}/data");

               if ($response->successful()) {
                    return $response->json();
               }

               return null;
          } catch (\Exception $e) {
               Log::error("HTTPDriver: Failed to fetch device data - " . $e->getMessage());
               return null;
          }
     }
}
