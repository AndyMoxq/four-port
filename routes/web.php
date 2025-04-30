<?php
use Illuminate\Support\Facades\Route;
use Tracking\Ocean\Controllers\OceanTrackingController;
use Tracking\Ocean\Controllers\OceanTrackingCarriersController;

Route::post('api/ocean-tracking', [OceanTrackingController::class, 'update']);
Route::get('api/ocean-tracking-carriers', [OceanTrackingCarriersController::class, 'index']);