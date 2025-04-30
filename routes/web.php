<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstacionPublicaController;
use App\Http\Controllers\SectorController;

/*Route::get('/', function () {
    return view('mapa');
});*/

Route::get('/', function () {
    return redirect('/sector/');
});

Route::get('/estacion-publica/{id_estacion}', function ($id_estacion) {
    return view('estacion-publica', ['id_estacion' => $id_estacion]);
});

Route::get('/estacion-publica/{id_estacion}', [EstacionPublicaController::class, 'show']);

Route::get('/estacion-publica/{id_estacion}', [EstacionPublicaController::class, 'show']);


Route::get('/glosary', [SectorController::class, 'glosary']);

Route::get('/sector/{id}', function ($id) {
    return view('mapa', ['sector' => $id]);
})->where('id', '[0-9]+'); 



Route::get('/sector/{id}', [SectorController::class, 'show'])->where('id', '[0-9]+');
Route::get('/sector/{id?}', [SectorController::class, 'show'])->where('id', '[0-9]+');