<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableController extends Controller
{
    public function show(Request $request)
    {
        $periodo = $request->input('periodo');
        $device = $request->input('device');
        $processedData = [];


        if (!$periodo || !$device) {
            return response()->json([
                'error' => 'Los parámetros "periodo" y "device" son requeridos.'
            ], 400);
        }

        // Llamamos al procedimiento almacenado con periodo y device
        $data = DB::select("CALL spi_obtener_observaciones(?, ?)", [$periodo, $device]);

        // Procesamos los datos
        if (!empty($data)) {
            $processedData = array_map(function($row) {
                $rowArray = (array) $row;
        
                unset($rowArray['id_estacion'], $rowArray['id_parametro']);
        
                foreach (['fecha_inicio', 'fecha_fin', 'fecha_ingreso'] as $fechaCampo) {
                    if (isset($rowArray[$fechaCampo])) {
                        $rowArray[$fechaCampo] = date('d-m-Y H:i', strtotime($rowArray[$fechaCampo]));
                    }
                }
        
                return $rowArray;
            }, $data);
        }

        return response()->json([
            'draw' => intval($request->input('draw', 1)),
            'recordsTotal' => count($processedData),
            'recordsFiltered' => count($processedData),
            'data' => $processedData
        ], 200, [], JSON_PRETTY_PRINT);
    }
}

