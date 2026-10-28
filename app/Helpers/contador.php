<?php

use Illuminate\Support\Facades\Request;
use App\Models\Visita;
use App\Models\IpVisita;

/**
 * Ya no cuenta durante la carga de la página: con la base de datos remota eran ~1,5 s de espera.
 * La visita la registra el navegador con una petición aparte (public/js/visita.js -> POST /api/visita).
 */
function contarVisita()
{
    // intencionalmente vacío
}

function registrarVisita(?string $ip): void
{
    $fechaHoy = date('Y-m-d');

    $visita = Visita::firstOrNew(['fecha' => $fechaHoy]);
    $visita->total = ($visita->total ?? 0) + 1;
    $visita->save();

    IpVisita::firstOrCreate([
        'fecha' => $fechaHoy,
        'ip' => $ip,
    ]);
}
