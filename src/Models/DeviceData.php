<?php

namespace RanaTuhin\LaravelIoTConnector\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceData extends Model
{
     protected $table = 'iot_device_data';

     protected $fillable = [
          'device_id',
          'key',
          'value',
     ];

     // Relationship to device
     public function device()
     {
          return $this->belongsTo(Device::class, 'device_id');
     }
}
