<?php

use Illuminate\Support\Facades\Request;
use App\Models\Visita;
use App\Models\IpVisita;

function contarVisita()
{
    $fechaHoy = date('Y-m-d');
    $ip = Request::ip();

    $visita = Visita::firstOrNew(['fecha' => $fechaHoy]);
    $visita->total = ($visita->total ?? 0) + 1;
    $visita->save();

    IpVisita::firstOrCreate([
        'fecha' => $fechaHoy,
        'ip' => $ip,
    ]);
}
