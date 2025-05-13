<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\SensorService; // Importar el servicio

class DeviceController extends Controller
{
    public function getInfoDevice($idDevice)
    {
        try {
            // Llamar al procedimiento almacenado para obtener los sensores de la estación
            $sensores = DB::select('CALL GetSensoresByEstacion(?)', [$idDevice]);

            // Llamar al procedimiento almacenado para obtener la información de la estación
            $estacion = DB::select('CALL GetEstacionById(?)', [$idDevice]);

            // Si la estación no existe, devolver error
            if (empty($estacion)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la estación'
                ], 404);
            }

            // Usar el servicio para formatear los sensores
            $formattedSensores = SensorService::formatSensors($sensores);

            // Formatear la salida en el array selector
            $selector = [
                'parametros' => $formattedSensores,
                'periodos' => $this->fillPeriodos(),
                'estacion' => $estacion[0] // Solo devolver el primer resultado
            ];

            return response()->json(
                
                $selector
            , 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la información del dispositivo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Función para devolver los períodos predefinidos
    private function fillPeriodos()
    {
        return [
            ['id_periodo' => 1, 'descripcion' => 'Hoy'],
            ['id_periodo' => 2, 'descripcion' => 'Últimos 7 Días'],
          
        ];
    }
}
