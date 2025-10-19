<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
     {
          Schema::create('iot_devices', function (Blueprint $table) {
               $table->id();
               $table->string('name');
               $table->string('protocol')->default('mqtt');
               $table->string('token')->unique();
               $table->string('status')->default('offline');
               $table->timestamps();
          });
     }

     public function down(): void
     {
          Schema::dropIfExists('iot_devices');
     }
};
