<?php

namespace RanaTuhin\LaravelIoTConnector\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
     protected $table = 'iot_devices';

     protected $fillable = [
          'name',
          'protocol',
          'token',
          'status',
     ];

     protected $casts = [
          'status' => 'string', // can be 'online' or 'offline'
     ];

     // Relationship to device data
     public function data()
     {
          return $this->hasMany(DeviceData::class, 'device_id');
     }
}
