<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstacionPublicaController;
use App\Http\Controllers\SectorController;
use App\Models\Visita;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Models\IpVisita;




Route::get('/inicio', function ()
{
    //contarVisita();
    //return redirect('/sector/');
     return view('inicio');
});


Route::get('/', function ()
{
    contarVisita();
    return redirect('/sector/');
});

Route::get('/estacion-publica/{id_estacion}', function ($id_estacion)
{
    contarVisita();
    return view('estacion-publica', ['id_estacion' => $id_estacion]);
});

Route::get('/estacion-publica/{id_estacion}', [EstacionPublicaController::class, 'show']);
Route::get('/estacion-publica/{id_estacion}', [EstacionPublicaController::class, 'show']);

Route::get('/glosary', [SectorController::class, 'glosary']);
Route::get('/sector/{id}', function ($id) {
    contarVisita();
    return view('mapa', ['sector' => $id]);
})->where('id', '[0-9]+'); 
Route::get('/sector/{id}', [SectorController::class, 'show'])->where('id', '[0-9]+');
Route::get('/sector/{id?}', [SectorController::class, 'show'])->where('id', '[0-9]+');