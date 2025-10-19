<p align="center">
  <img src="https://github.com/ranatuhin2/laravel-iot-connector/blob/main/resources/icon.png" width="850" height="200" alt="Laravel IoT Connector">
</p>

# Laravel IoT Connector

**IoT Device Management & Real-Time Data Connector for Laravel**
Easily **register IoT devices, store device data, send commands, and broadcast events** in real-time via MQTT, HTTP, or WebSocket protocols.


## Installation

Install via Composer:

```bash
composer require rana-tuhin/laravel-iot-connector:@dev
```

Laravel will **auto-discover** the package.

---

## Publish Configuration

To customize default settings, publish the package config:

```bash
php artisan vendor:publish --tag=config
```

This will create `config/iotconnector.php` in your Laravel project.
Example configuration:

```php
'default_driver' => 'mqtt', // mqtt / http / websocket
'mqtt_broker' => 'tcp://127.0.0.1:1883',
'mqtt_username' => '',
'mqtt_password' => '',
'topic_prefix' => 'iot/devices',
'http_base_url' => 'http://127.0.0.1:8001/api/devices',
'websocket_url' => 'ws://127.0.0.1:8080'
```


---

## Quick Start

```php
use IoT;

// 1. Register a device
$device = IoT::registerDevice('Sensor_001');

// 2. Store data from the device
IoT::storeDeviceData($device->token, [
    'temperature' => 28.5,
    'humidity' => 65
]);

// 3. Send a command to the device
IoT::dispatchCommand($device->token, ['action' => 'TURN_ON_FAN']);

// 4. Listen to MQTT data (backend)
IoT::listenToDeviceData();
```

---

---

## Usage

### **Using the Facade**

```php
use IoT;

// Register a device
$device = IoT::registerDevice('Sensor_001');

// Store incoming device data
IoT::storeDeviceData($device->token, [
    'temperature' => 28.5,
    'humidity' => 65
]);

// Send command to device
IoT::dispatchCommand($device->token, ['action' => 'TURN_ON_FAN']);

// Listen to MQTT data (backend)
IoT::listenToDeviceData();
```

---

### **Using the Service Class Directly**

```php
use RanaTuhin\LaravelIoTConnector\Services\IoTService;

// Initialize service
$iot = new IoTService();

// Register device
$device = $iot->registerDevice('Sensor_001');

// Store device data
$iot->storeDeviceData($device->token, [
    'temperature' => 28.5,
    'humidity' => 65
]);

// Dispatch command
$iot->dispatchCommand($device->token, ['action' => 'TURN_ON_FAN']);

// Listen for device data via MQTT
$iot->listenToDeviceData();
```

---

## **Console Commands**

```bash
# Register a device via CLI
php artisan iot:register "Sensor_001" mqtt

# Send command via CLI
php artisan iot:send-command <device_token> '{"action":"TURN_ON_FAN"}'
```

---

## **Frontend Integration (Real-Time Dashboard)**

To receive live updates from your devices, use **Laravel Echo + Pusher**:

1. Install Echo & Pusher:

```bash
npm install --save laravel-echo pusher-js
```

2. Configure `.env`:

```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
```

3. Add Echo in `resources/js/bootstrap.js`:

```js
import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});
```

4. Listen for device events:

```js
let deviceToken = 'YOUR_DEVICE_TOKEN';

window.Echo.private('iot-device.' + deviceToken)
    .listen('DeviceDataReceived', (e) => {
        console.log('New device data:', e.data);
        // Update charts or UI live
    });
```

> The package automatically fires `DeviceDataReceived` events whenever new device data is stored.

---

## **Events**

| Event                | Description                      | Broadcast                                            |
| -------------------- | -------------------------------- | ---------------------------------------------------- |
| `DeviceDataReceived` | Fired when device sends new data | `ShouldBroadcast` – can be listened via Laravel Echo |

---

## **Class & Method Reference**

| Class          | Method                                                  | Description                              | Parameters                                 | Returns        |
| -------------- | ------------------------------------------------------- | ---------------------------------------- | ------------------------------------------ | -------------- |
| `IoTService`   | `registerDevice(string $name, string $protocol = null)` | Registers a new IoT device               | `$name`, `$protocol` (mqtt/http/websocket) | `Device`       |
| `IoTService`   | `storeDeviceData(string $deviceToken, array $data)`     | Store data sent from device              | `$deviceToken`, `$data`                    | `DeviceData[]` |
| `IoTService`   | `dispatchCommand(string $deviceToken, array $payload)`  | Send command to a device                 | `$deviceToken`, `$payload`                 | `bool`         |
| `IoTService`   | `listenToDeviceData()`                                  | Subscribe to MQTT topic for device data  | -                                          | void           |
| `IoT` (Facade) | `registerDevice(string $name, string $protocol = null)` | Same as `IoTService::registerDevice`     | -                                          | `Device`       |
| `IoT` (Facade) | `storeDeviceData(string $deviceToken, array $data)`     | Same as `IoTService::storeDeviceData`    | -                                          | `DeviceData[]` |
| `IoT` (Facade) | `dispatchCommand(string $deviceToken, array $payload)`  | Same as `IoTService::dispatchCommand`    | -                                          | `bool`         |
| `IoT` (Facade) | `listenToDeviceData()`                                  | Same as `IoTService::listenToDeviceData` | -                                          | void           |

---

## **Supported Protocols**

* **MQTT** – Real-time device communication
* **HTTP** – Polling or REST-based device integration
* **WebSocket** – Real-time push communication

---

## **Device Flow Diagram**

```
Device → MQTT / HTTP / WebSocket → Laravel IoT Connector (IoTService / Facade)
      → Stores in Database → Fires Event DeviceDataReceived
      → Frontend (Echo / Pusher) → Real-Time Dashboard
```

---

## **Publishing Assets**

```bash
# Publish migrations
php artisan vendor:publish --tag=migrations

# Publish config
php artisan vendor:publish --tag=config
```

---

## **License**

MIT © [Rana Tuhin](https://github.com/ranatuhin2)
