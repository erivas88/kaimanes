<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeftMenuController extends Controller
{
    public function getLeftMenu()
    {
        try {
            // Obtener los sectores
            $sectores = $this->getsSectores();

            // Generar el HTML del menú desplegable
            $dropdown = $this->generateDropdownHTML($sectores);

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

    // Función para obtener los sectores y sus estaciones
    private function getsSectores()
    {
        try {
            $sectores = DB::select("SELECT * FROM sectores");

            $data = [];
            foreach ($sectores as $sector) {
                $submenu = $this->getStations($sector->id_sector);
                $data[] = [
                    'menu' => $sector,
                    'submenu' => $submenu
                ];
            }
            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }

    // Función para obtener las estaciones por sector
    private function getStations($sector)
    {
        try {
            $estaciones = DB::select("SELECT * FROM estaciones WHERE sector = ? ORDER BY nombre", [$sector]);

            return ['submenu' => $estaciones]; // Formato correcto para la estructura del menú
        } catch (\Exception $e) {
            return ['submenu' => []];
        }
    }
    

    // Función para generar el HTML del menú desplegable como un acordeón
private function generateDropdownHTML($array)
{
    $html = '<div class="accordion" id="accordionExample">';

    foreach ($array as $index => $item) {
        $menu = (array) $item['menu']; // Convertir stdClass a array
        $submenus = (array) $item['submenu']['submenu']; // Obtener estaciones

        // Generar identificadores únicos para los elementos del acordeón
        $collapseId = "collapse" . $menu['id_sector'];
        $headingId = "heading" . $menu['id_sector'];

        // Header del acordeón
        $html .= '<div class="accordion-item">';
        $html .= '<h2 class="accordion-header" id="' . $headingId . '" id-sector="'.$menu['id_sector'].'">';
        $html .= '<button class="accordion-button collapsed" type="button"  id-sector="'.$menu['id_sector'].'"   data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '" aria-expanded="false" aria-controls="' . $collapseId . '">';
        $html .= '<span class="'.$menu['icon'].'_"></span> &nbsp; '.htmlspecialchars($menu['sector']);
        $html .= '</button>';
        $html .= '</h2>';

        // Contenido del acordeón
        $html .= '<div id="' . $collapseId . '" class="accordion-collapse collapse" aria-labelledby="' . $headingId . '" data-bs-parent="#accordionExample">';
        $html .= '<div class="accordion-body" style="background-color: #f5f5f5;">';

        // Lista de estaciones dentro del acordeón
        $html .= '<div class="list-group-container">';
        $html .= '<ul class="list-group">';
        foreach ($submenus as $submenu) {
            $submenu = (array) $submenu; // Convertir stdClass a array
            $html .= '<li class="list-group-item">';
            $html .= '<a title="Ver datos de estación" class="link-item items" href="' . url('estacion-publica/' . urlencode($submenu['estacion_id'])) . '">';
            $html .= '<span class="' . htmlspecialchars($menu['icon']) . '">&nbsp;</span> &nbsp;' . htmlspecialchars($submenu['nombre']);
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
