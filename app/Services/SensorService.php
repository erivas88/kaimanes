<?php

namespace App\Services;

class SensorService
{
    public static function formatSensors($sensores)
    {
        $selector = [];

        foreach ($sensores as $row) {
            $selector[] = [
                'sensor' => $row->sensor_id,
                'tipo' => $row->tipo
            ];
        }

        return $selector;
    }
}
