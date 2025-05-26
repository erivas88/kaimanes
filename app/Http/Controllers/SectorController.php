<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sector;
use App\Models\Estacion;


class SectorController extends Controller
{

    public function glosary($id = null)
    {
        // Obtener los sectores y generar el dropdown
        contarVisita();
        $sectores = $this->getsSectores();
        $dropdown = $this->generateDropdownHTML($sectores, $id);

        // Retornar la vista 'glosario'
        return view('glosario', [
            'sector' => $id,
            'dropdown' => $dropdown
        ]);
    }

    public function show($id = null)
    {
    // Obtener los sectores y estaciones
        contarVisita();
        $sectores = $this->getsSectores();
        // Generar el dropdown con todos los sectores cerrados si $id es nulo
        $dropdown = $this->generateDropdownHTML($sectores, $id);

        // Pasar los datos a la vista
        return view('mapa', [
            'sector' => $id,
            'dropdown' => $dropdown
        ]);
    }

   
    private function getsSectores()
    {
        try {
            
            $sectores = Sector::all();
    
            $data = [];
            foreach ($sectores as $sector) {
                $submenu = $this->getStations($sector->id_sector);
                $data[] = [
                    'menu' => $sector->toArray(), // Convertir modelo Eloquent a array
                    'submenu' => $submenu
                ];
            }
            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getStations($sector)
    {
        try {
            $estaciones = Estacion::where('sector', $sector)
                            ->where('enable_site', '1')
                            ->orderBy('nombre')
                            ->get();
            /*$estaciones = Estacion::where('sector', $sector)->orderBy('nombre')->get(); */   
            return ['submenu' => $estaciones->toArray()]; 
        } catch (\Exception $e) {
            return ['submenu' => []];
        }
    }
    
    private function generateDropdownHTML($array, $idSectorActivo = null)
    {
        $html = '<div class="accordion" id="accordionExample">';

        foreach ($array as $index => $item) {
            $menu = (array) $item['menu']; 
            $submenus = (array) $item['submenu']['submenu'];

            // Determinar si este sector debe estar abierto (solo si $idSectorActivo está definido)
            $isOpen = ($idSectorActivo !== null && $idSectorActivo == $menu['id_sector']);

            // Generar identificadores únicos para los elementos del acordeón
            $collapseId = "collapse" . $menu['id_sector'];
            $headingId = "heading" . $menu['id_sector'];

            // Header del acordeón con `aria-expanded`
            $html .= '<div class="accordion-item">';
            $html .= '<h2 class="accordion-header" id="' . $headingId . '" id-sector="' . $menu['id_sector'] . '">';
            $html .= '<button class="accordion-button ' . ($isOpen ? "" : "collapsed") . '" type="button" id-sector="' . $menu['id_sector'] . '" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '" aria-expanded="' . ($isOpen ? "true" : "false") . '" aria-controls="' . $collapseId . '">';
            $html .= '<span class="' . htmlspecialchars($menu['icon']) . '"></span> &nbsp; ' . htmlspecialchars($menu['sector']);
            $html .= '</button>';
            $html .= '</h2>';
      
            $html .= '<div id="' . $collapseId . '" class="accordion-collapse collapse' . ($isOpen ? ' show' : '') . '" aria-labelledby="' . $headingId . '" data-bs-parent="#accordionExample">';
            $html .= '<div class="accordion-body" style="background-color: #f5f5f5;">';

            // Lista de estaciones dentro del acordeón
            $html .= '<div class="list-group-container">';
            $html .= '<ul class="list-group">';
            /*foreach ($submenus as $submenu) {
                $submenu = (array) $submenu; // Convertir cada estación a array
                $html .= '<li class="list-group-item">';
                $html .= '<a title="Ver datos de estación" class="link-item items" href="' . url('estacion-publica/' . urlencode($submenu['estacion_id'])) . '">';
                $html .= '<span class="' . htmlspecialchars($menu['icon']) . '"></span> &nbsp; ' . htmlspecialchars($submenu['nombre']);
                $html .= '</a>';
                $html .= '</li>';
            }*/

            foreach ($submenus as $submenu) {
                $submenu = (array) $submenu; // Convertir cada estación a array
                $html .= '<a title="Ver datos de estación" class="list-group-item list-group-item-action link-item items" href="' . url('estacion-publica/' . urlencode($submenu['estacion_id'])) . '" style="text-decoration: none; color: inherit;">';
                $html .= '<span class="' . htmlspecialchars($menu['icon']) . '"></span> &nbsp; ' . htmlspecialchars($submenu['nombre']);
                $html .= '</a>';
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
