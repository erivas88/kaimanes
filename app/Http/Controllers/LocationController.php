<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\MarkerService;

class LocationController extends Controller
{
    public function getLocation()
    {
        try {
            $result = DB::select('CALL GetAverageCoordinates()');

            if (empty($result)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron coordenadas'
                ], 404);
            }

            return response()->json($result[0], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la ubicación promedio',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getBySectorPublico($sector_publico)
    {
        try {
            $result = DB::select('CALL GetEstacionesBySector(?)', [$sector_publico]);

            if (empty($result)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron estaciones en este sector público'
                ], 404);
            }

            // Usar el servicio para formatear los datos
            $formattedData = MarkerService::formatMarkers($result, "estacion");

            return response()->json($formattedData, 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las estaciones por sector público',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todas las estaciones desde el procedimiento almacenado `GetEstaciones`
     */
    public function getAllEstaciones()
    {
        try {
            $result = DB::select('CALL GetEstaciones()');

            if (empty($result)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron estaciones'
                ], 404);
            }

            // Usar el servicio para formatear los datos si es necesario
            $formattedData = MarkerService::formatMarkers($result, "estacion");

            return response()->json($formattedData, 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener todas las estaciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
