<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlotController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\LeftMenuController;
use App\Http\Controllers\LeftOpenController;
use App\Http\Controllers\LeftOpenMenuController;

Route::post('/api/plot', [PlotController::class, 'store']);
#Route::post('/api/plot', [PlotController::class, 'store'])->middleware('internal.token');

Route::get('/api/location', [LocationController::class, 'getLocation']);
Route::get('/api/location/sector/sector_publico/{sector_publico}', [LocationController::class, 'getBySectorPublico']);
Route::get('/api/info_device/{idDevice}', [DeviceController::class, 'getInfoDevice']);
Route::get('/api/left', [LeftMenuController::class, 'getLeftMenu']);
Route::get('/api/left_open/{id_device}', [LeftOpenController::class, 'getLeftMenu']);
Route::get('/api/estaciones', [LocationController::class, 'getAllEstaciones']);
Route::get('/api/map-token', function () {
    return response()->json(['apiKey' => config('aws.map_api_key')]);
});
#Route::post('/api/plot', [PlotController::class, 'store'])->middleware('internal.token');





