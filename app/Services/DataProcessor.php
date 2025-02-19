<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;

class DataProcessor
{
    public static function processQueryResults($results)
{
    $series = [];
    $dataPoints = [];
    $unidad = "";

    foreach ($results as $row)
    {
        $milliseconds = self::convertToMilliseconds($row->fecha_hora);
        $value = self::convertToFloat($row->valor);
        $plotlines = self::getPlotlines($row->id_estacion, $row->sensor);
        $series['name'] = $row->sensor;
        $series['parametro'] = $row->sensor;
        $series['unidad'] = $row->unidad;
        $series['limite_superior'] = $row->limite_superior;
        $series['limite_inferior'] = $row->limite_inferior;
        $series['yAxis'] = $row->yAxis;
        $series['decimales'] = $row->decimales;
        $series['plotlines'] = $plotlines; 
        $series['id_estacion'] = $row->id_estacion;
        $dataPoints[] = [$milliseconds, $value];
        $unidad = $row->unidad;
    }

    $series['data'] = $dataPoints;

    if (!empty($dataPoints)) {
        $dateRange = self::getDateRange($dataPoints);
        $series['periodo'] = "Periodo desde {$dateRange['minDate']} hasta {$dateRange['maxDate']}";

        $stats = self::calculateStatistics($dataPoints, $unidad);
        $series['stats'] = $stats;
        $series['dateRange'] = $dateRange;
    } else {
        $series['periodo'] = '';
    }

    return $series;
}



public static function getPlotlines($idEstacion, $parametro)
{
    $query = "CALL GetPlotlineByEstacionParametro(?, ?)";
    $results = DB::select($query, [$idEstacion, $parametro]);

    $plotlines = [];

    foreach ($results as $row) {
        $plotlines[] = [
            'color' => $row->color ?? 'red', // Si no tiene color, usa rojo por defecto
            'dashStyle' => $row->style ?? 'Dash', // Si no tiene estilo, usa Dash por defecto
            'width' => $row->width ?? 2, // Si no tiene ancho, usa 2 por defecto
            'value' => $row->value, // Posición en el eje Y
            'zIndex'=> 3, // M
            'label' => [
                'text' => $row->text ?? 'Límite', // Texto de la etiqueta
                'align' => $row->align ?? 'left', // Alineación de la etiqueta
                'x' => -10, // Ajuste horizontal del texto
                'style' => [
                    'color' => 'black',                   
                ]
            ]
        ];
    }

    return $plotlines;
}




    private static function convertToMilliseconds($datetime)
    {
        return strtotime($datetime) * 1000;
    }


    private static function convertToFloat($value)
    {
        return floatval($value);
    }

 
    private static function getDateRange($dataPoints)
    {
        $timestamps = array_column($dataPoints, 0);
        return [
            'minDate' => date('d-m-Y H:i:s', min($timestamps) / 1000),
            'maxDate' => date('d-m-Y H:i:s', max($timestamps) / 1000),
        ];
    }


    private static function calculateStatistics($dataPoints, $unidad)
    {
        $values = array_column($dataPoints, 1);
        $count = count($values);
        
        if ($count === 0) {
            return [
                'min' => null,
                'max' => null,
                'average' => null,
                'dvst' => null,
                'unidad' => $unidad
            ];
        }
    
        $average = array_sum($values) / $count;
        
        // Cálculo de la desviación estándar
        $variance = array_sum(array_map(fn($val) => pow($val - $average, 2), $values)) / $count;
        $standardDeviation = sqrt($variance);
    
        return [
            'min' => min($values),
            'max' => max($values),
            'average' => round($average, 2),
            'dvst' => round($standardDeviation, 2),
            'unidad' => $unidad
        ];
    }
}
