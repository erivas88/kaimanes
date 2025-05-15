<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Estacion;


class LeftOpenController extends Controller
{
    /**
     * Obtiene el menú lateral y abre automáticamente el sector al que pertenece la estación.
     *
     * @param int|null $idDevice
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeftMenu($idDevice = null)
    {
        try {
            // Obtener los sectores y estaciones
            $sectores = $this->getsSectores();

            // Generar el HTML del menú desplegable con la estación seleccionada
            $dropdown = $this->generateDropdownHTML($sectores, $idDevice);

            // Devolver la respuesta en JSON
            return response()->json([
                'dropdown' => $dropdown
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el menú lateral',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene los sectores y sus estaciones desde la base de datos.
     *
     * @return array
     */
    private function getsSectores()
    {
        try {
            // Obtener los sectores
            $sectores = DB::select("SELECT * FROM sectores");

            $data = [];
            foreach ($sectores as $sector) {
                $submenu = $this->getStations($sector->id_sector);
                $data[] = [
                    'menu' => json_decode(json_encode($sector), true), // Convertir stdClass a array
                    'submenu' => $submenu
                ];
            }
            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Obtiene las estaciones de un sector específico.
     *
     * @param int $sector
     * @return array
     */
    private function getStations($sector)
    {
        try {
            //$estaciones = DB::select("SELECT * FROM estaciones WHERE sector = ? ORDER BY nombre", [$sector]);
              $estaciones = Estacion::where('sector', $sector)
                 ->where('enable_site', '1')
                 ->orderBy('nombre')
                 ->get();

            return ['submenu' => json_decode(json_encode($estaciones), true)]; // Convertir stdClass a array
        } catch (\Exception $e) {
            return ['submenu' => []];
        }
    }

    /**
     * Genera el HTML del menú desplegable como un acordeón.
     *
     * @param array $array
     * @param int|null $idDevice
     * @return string
     */
    private function generateDropdownHTML($array, $idDevice = null)
{
    $html = '<div class="accordion" id="accordionExample">';

    foreach ($array as $index => $item) {
        // Convertir stdClass a array
        $menu = (array) $item['menu']; 
        $submenus = (array) $item['submenu']['submenu'];

        // Verificar si este sector contiene la estación seleccionada
        $isOpen = false;
        foreach ($submenus as $submenu) {
            $submenu = (array) $submenu; // Asegurar conversión a array
            if (isset($submenu['estacion_id']) && $submenu['estacion_id'] == $idDevice) {
                $isOpen = true;
                break;
            }
        }

        // Generar identificadores únicos para los elementos del acordeón
        $collapseId = "collapse" . $menu['id_sector'];
        $headingId = "heading" . $menu['id_sector'];

        // Header del acordeón con `aria-expanded` corregido
        $html .= '<div class="accordion-item">';
        $html .= '<h2 class="accordion-header" id="' . $headingId . '" id-sector="' . $menu['id_sector'] . '">';
        $html .= '<button class="accordion-button ' . ($isOpen ? "" : "collapsed") . '" type="button" id-sector="' . $menu['id_sector'] . '" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '" aria-expanded="' . ($isOpen ? "true" : "false") . '" aria-controls="' . $collapseId . '">';
        $html .= '<span class="' . htmlspecialchars($menu['icon']) . '"></span> &nbsp; ' . htmlspecialchars($menu['sector']);
        $html .= '</button>';
        $html .= '</h2>';

        // Contenido del acordeón (con `show` solo si `$isOpen` es true)
        $html .= '<div id="' . $collapseId . '" class="accordion-collapse collapse' . ($isOpen ? ' show' : '') . '" aria-labelledby="' . $headingId . '" data-bs-parent="#accordionExample">';
        $html .= '<div class="accordion-body" style="background-color: #f5f5f5;">';

        // Lista de estaciones dentro del acordeón
        $html .= '<div class="list-group-container">';
        $html .= '<ul class="list-group">';
        foreach ($submenus as $submenu) {
            $submenu = (array) $submenu; // Convertir cada estación a array

            // Verificar si esta estación es la seleccionada
            $isSelected = isset($submenu['estacion_id']) && $submenu['estacion_id'] == $idDevice;
            
            $html .= '<li class="list-group-item' . ($isSelected ? ' seleccionado' : '') . '">';
            $html .= '<a title="Ver datos de estación" class="link-item items" href="' . url('estacion-publica/' . urlencode($submenu['estacion_id'])) . '">';
            $html .= '<span class="' . htmlspecialchars($menu['icon']) . '"></span> &nbsp; ' . htmlspecialchars($submenu['nombre']);
            $html .= '</a>';
            $html .= '</li>';
        }

        $html .= '</ul>';
        $html .= '</div>'; // Fin de .list-group-container
        $html .= '</div>'; // Fin de .accordion-body
        $html .= '</div>'; // Fin de .accordion-collapse

        $html .= '</div>'; // Fin de .accordion-item
    }

    $html .= '</div>'; // Fin de .accordion

    return $html;
}

    
}
