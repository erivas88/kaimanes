<?php

namespace App\Services;

class DataProcessor
{
    public static function processQueryResults($results)
    {

        $series = [];
        $dataPoints = [];
        $unidad = "";

        foreach ($results as $row) {
            $milliseconds = self::convertToMilliseconds($row->fecha_hora); 
            $value = self::convertToFloat($row->valor); 

       
            $series['name'] =  $row->sensor;
            $series['parametro'] = $row->sensor;
            $series['unidad'] = $row->unidad;
            $series['limite_superior'] = $row->limite_superior;
            $series['limite_inferior'] = $row->limite_inferior;
            $series['yAxis'] = $row->yAxis;
            $series['decimales'] = $row->decimales;
            $dataPoints[] = [$milliseconds, $value];
            $unidad = $row->unidad; 
        }
       
        $series['data'] = $dataPoints;

       
        if (!empty($dataPoints)) {
            $dateRange = self::getDateRange($dataPoints); 

            
            $series['periodo'] = "{$dateRange['minDate']} — {$dateRange['maxDate']}";

            $stats = self::calculateStatistics($dataPoints, $unidad);
            $series['stats'] = $stats; 

            $series['dateRange'] = $dateRange; 
        } else {
          
            $series['periodo'] = '';
        }

        return $series;
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
