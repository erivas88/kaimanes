<?php

namespace App\Services;

class MarkerService
{
    public static function formatMarkers($data, $tipo = "default")
    {
        $markers = [];

        foreach ($data as $row) {
            $markers[] = [
                'nombre' => $row->nombre,
                'map_name' => $row->map_name,
                'tipo' => $row->sector,
                'latitud' => $row->lat,
                'longitud' => $row->lon,
                'id' => $row->estacion_id,
                'sector' => $row->nombre_sector,
            ];
        }

        return $markers;
    }
}
