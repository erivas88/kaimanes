<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\DataProcessor;

class PlotController extends Controller
{
    public function store(Request $request)
    {   
       
    
        // Validar la estructura del JSON
        $validated = $request->validate([
            'sensor' => 'required|array', // Acepta un array de sensores
            'sensor.*' => 'integer', // Cada elemento del array debe ser un número entero
            'periodo' => 'required|array', // Asegurar que periodo es un array
            'periodo.*' => 'integer', // Cada elemento debe ser un número entero
            'estacion' => 'required|integer',
        ]);
    
        // Extraer el primer valor del array de periodo
        $periodo = $validated['periodo'][0] ?? null;
    
        if ($periodo === null) {
            return response()->json([
                'success' => false,
                'message' => 'El periodo no es válido'
            ], 400);
        }
    
        try {
            $allProcessedData = []; // Aquí almacenaremos los resultados de cada sensor
            
            foreach ($validated['sensor'] as $sensorId) {
                // Llamar al procedimiento almacenado para cada sensor
                $results = DB::select('CALL GetSensorData(?, ?, ?)', [
                    $validated['estacion'],
                    $sensorId,
                    $periodo // Pasar solo el primer elemento del array
                ]);
    
                // Procesar los datos obtenidos
                $processedData = DataProcessor::processQueryResults($results, $periodo);
    
                // Agregar la serie procesada con el identificador del sensor
                $allProcessedData[] = $processedData;
            }
    
            return response()->json($allProcessedData, 200);
        
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
