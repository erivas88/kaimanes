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

            $sensores = DB::select('CALL GetSensoresByEstacion(?)', [$idDevice]);           
            $estacion = DB::select('CALL GetEstacionById(?)', [$idDevice]);          
            if (empty($estacion)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la estación'
                ], 404);
            }
        
            $formattedSensores = SensorService::formatSensors($sensores); 
            $selector = [
                'parametros' => $formattedSensores,
                'periodos' => $this->fillPeriodos(),
                'estacion' => $estacion[0] 
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

    private function fillPeriodos()
    {
        return [
            ['id_periodo' => 1, 'descripcion' => 'Hoy'],
            ['id_periodo' => 2, 'descripcion' => 'Últimos 7 Días'],
          
        ];
    }
}
