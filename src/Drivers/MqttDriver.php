<?php

namespace RanaTuhin\LaravelIoTConnector\Drivers;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use Illuminate\Support\Facades\Log;

class MqttDriver
{
     protected $client;
     protected $host;
     protected $port;
     protected $username;
     protected $password;

     public function __construct()
     {
          $this->host = parse_url(config('iotconnector.mqtt_broker'), PHP_URL_HOST) ?? '127.0.0.1';
          $this->port = parse_url(config('iotconnector.mqtt_broker'), PHP_URL_PORT) ?? 1883;
          $this->username = config('iotconnector.mqtt_username');
          $this->password = config('iotconnector.mqtt_password');

          $this->client = new MqttClient($this->host, $this->port, uniqid('iot_', true));
     }

     /**
      * Connect to MQTT broker
      */
     public function connect()
     {
          $connectionSettings = (new ConnectionSettings)
               ->setUsername($this->username)
               ->setPassword($this->password)
               ->setKeepAliveInterval(60)
               ->setLastWillTopic('iot/status')
               ->setLastWillMessage('Disconnected');

          $this->client->connect($connectionSettings, true);
     }

     /**
      * Subscribe to a topic
      */
     public function subscribe(string $topic, callable $callback)
     {
          $this->client->subscribe($topic, function ($topic, $message) use ($callback) {
               $callback($topic, $message);
          }, 0);
     }

     /**
      * Publish a message to a topic
      */
     public function publish(string $topic, string $message)
     {
          $this->client->publish($topic, $message, 0, false);
     }

     /**
      * Disconnect
      */
     public function disconnect()
     {
          $this->client->disconnect();
     }
}
