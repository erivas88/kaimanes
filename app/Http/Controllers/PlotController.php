<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\DataProcessor;

class PlotController extends Controller
{
    public function store(Request $request)
    {   
           
    
        $validated = $request->validate([
            'sensor' => 'required|array', // Acepta un array de sensores
            'sensor.*' => 'integer', // Cada elemento del array debe ser un número entero
            'periodo' => 'required|array', // Asegurar que periodo es un array
            'periodo.*' => 'integer', // Cada elemento debe ser un número entero
            'estacion' => 'required|integer',
        ]);
    
     
        $periodo = $validated['periodo'][0] ?? null;
    
        if ($periodo === null) {
            return response()->json([
                'success' => false,
                'message' => 'El periodo no es válido'
            ], 400);
        }
    
        try {
            $allProcessedData = []; 
            
            foreach ($validated['sensor'] as $sensorId) {
         
                $results = DB::select('CALL GetSensorData(?, ?, ?)', [
                    $validated['estacion'],
                    $sensorId,
                    $periodo
                ]);    
          
                $processedData = DataProcessor::processQueryResults($results, $periodo);   
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
