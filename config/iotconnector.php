<?php

return [

     /*
    |--------------------------------------------------------------------------
    | MQTT Broker Configuration
    |--------------------------------------------------------------------------
    |
    | Define the MQTT broker connection settings. You can change these in
    | your .env file.
    |
    */

     'mqtt_broker' => env('IOT_MQTT_BROKER', 'tcp://127.0.0.1:1883'),
     'mqtt_username' => env('IOT_MQTT_USERNAME', ''),
     'mqtt_password' => env('IOT_MQTT_PASSWORD', ''),

     /*
    |--------------------------------------------------------------------------
    | Default Driver
    |--------------------------------------------------------------------------
    |
    | The default communication driver for IoT devices.
    | Supported: "mqtt", "http", "websocket"
    |
    */

     'default_driver' => 'mqtt',

     /*
    |--------------------------------------------------------------------------
    | Device Topic Prefix
    |--------------------------------------------------------------------------
    |
    | Prefix for all MQTT topics for your devices. You can change it
    | if you want a custom namespace.
    |
    */

     'topic_prefix' => 'iot/devices',

];
