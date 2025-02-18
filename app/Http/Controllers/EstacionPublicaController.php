<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstacionPublicaController extends Controller
{
    public function show($id_estacion)
    {
       
        $estacion = DB::select("CALL GetEstacionById(?)", [$id_estacion]);


        if (empty($estacion)) {
            abort(404, "Estación no encontrada");
        }


        $estacion[0]->icon_titlte = rtrim($estacion[0]->icon_titlte, '_');
        $estacion[0]->utm_north = number_format($estacion[0]->utm_north, 0, ',', '.') . ' m';
        $estacion[0]->utm_east = number_format($estacion[0]->utm_east, 0, ',', '.') . ' m';
       
        $side = $this->getLeftMenu($id_estacion);


        return view('estacion-publica', [
            'estacion' => $estacion[0], 
            'side' => $side 
        ]);
    }

   
    public function getLeftMenu($idDevice = null)
    {
        try {
            // Obtener los sectores y estaciones
            $sectores = $this->getsSectores();

            // Generar el HTML del menú desplegable con la estación seleccionada
            return $this->generateDropdownHTML($sectores, $idDevice);

        } catch (\Exception $e) {
            return '<p>Error al generar el menú lateral</p>';
        }
    }

   
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

    
    private function getStations($sector)
    {
        try {
            $estaciones = DB::select("SELECT * FROM estaciones WHERE sector = ? ORDER BY nombre", [$sector]);

            return ['submenu' => json_decode(json_encode($estaciones), true)]; // Convertir stdClass a array
        } catch (\Exception $e) {
            return ['submenu' => []];
        }
    }

 
    private function generateDropdownHTML($array, $idDevice = null)
    {
        $html = '<div class="accordion" id="accordionExample">';

        foreach ($array as $index => $item) {
            // Convertir stdClass a array
            $menu = (array) $item['menu']; 
            $submenus = (array) $item['submenu']['submenu'];

           
            $isOpen = false;
            foreach ($submenus as $submenu) {
                $submenu = (array) $submenu; // Asegurar conversión a array
                if (isset($submenu['estacion_id']) && $submenu['estacion_id'] == $idDevice) {
                    $isOpen = true;
                    break;
                }
            }

         
            $collapseId = "collapse" . $menu['id_sector'];
            $headingId = "heading" . $menu['id_sector'];

          
            $html .= '<div class="accordion-item">';
            $html .= '<h2 class="accordion-header" id="' . $headingId . '" id-sector="' . $menu['id_sector'] . '">';
            $html .= '<button class="accordion-button ' . ($isOpen ? "" : "collapsed") . '" type="button" id-sector="' . $menu['id_sector'] . '" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '" aria-expanded="' . ($isOpen ? "true" : "false") . '" aria-controls="' . $collapseId . '">';
            $html .= '<span class="' . htmlspecialchars($menu['icon']) . '"></span> &nbsp; ' . htmlspecialchars($menu['sector']);
            $html .= '</button>';
            $html .= '</h2>';
        
            $html .= '<div id="' . $collapseId . '" class="accordion-collapse collapse' . ($isOpen ? ' show' : '') . '" aria-labelledby="' . $headingId . '" data-bs-parent="#accordionExample">';
            $html .= '<div class="accordion-body" style="background-color: #f5f5f5;">';

          
            $html .= '<div class="list-group-container">';
            $html .= '<ul class="list-group">';
            /*foreach ($submenus as $submenu) {
                $submenu = (array) $submenu; // Convertir cada estación a array

               
                $isSelected = isset($submenu['estacion_id']) && $submenu['estacion_id'] == $idDevice;
                
                $html .= '<li class="list-group-item' . ($isSelected ? ' seleccionado' : '') . '">';
                $html .= '<a title="Ver datos de estación" class="link-item items" href="' . url('estacion-publica/' . urlencode($submenu['estacion_id'])) . '">';
                $html .= '<span class="' . htmlspecialchars($menu['icon']) . '"></span> &nbsp; ' . htmlspecialchars($submenu['nombre']);
                $html .= '</a>';
                $html .= '</li>';
            }*/

            foreach ($submenus as $submenu) {
                $submenu = (array) $submenu; // Convertir cada estación a array
            
                $isSelected = isset($submenu['estacion_id']) && $submenu['estacion_id'] == $idDevice;
            
                // Convertir <li> en <a> para que todo el elemento sea clickeable
                $html .= '<a title="Ver datos de estación" href="' . url('estacion-publica/' . urlencode($submenu['estacion_id'])) . '" 
                            class="list-group-item link-item items list-group-item-action' . ($isSelected ? ' seleccionado' : '') . '" 
                            style="text-decoration: none; color: inherit;">';
                $html .= '<span class="' . htmlspecialchars($menu['icon']) . '"></span> &nbsp; ' . htmlspecialchars($submenu['nombre']);
                $html .= '</a>';
            }
            

            $html .= '</ul>';
            $html .= '</div>'; 
            $html .= '</div>'; 
            $html .= '</div>'; 
            $html .= '</div>'; 
        }

        $html .= '</div>'; 

        return $html;
    }
}
