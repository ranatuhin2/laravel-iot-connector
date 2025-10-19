<?php

use Illuminate\Support\Facades\Route;

Route::prefix('iot')->group(function () {
     Route::get('status', function () {
          return response()->json([
               'status' => 'IoT Connector is running',
               'message' => 'Your package is loaded successfully!'
          ]);
     });
});
