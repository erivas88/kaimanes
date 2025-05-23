<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sector;
use App\Models\Estacion;

class EstacionPublicaController extends Controller
{
    public function show($id_estacion)
    {   
        contarVisita();
        $estacion = DB::select("CALL GetEstacionById(?)", [$id_estacion]);

        if (empty($estacion)) {
            abort(404, "Estación no encontrada");
        }

        $estacion[0]->icon_titlte = rtrim($estacion[0]->icon_titlte, '_');
        $estacion[0]->utm_north = number_format($estacion[0]->utm_north, 0, ',', '.') . ' m';
        $estacion[0]->utm_east = number_format($estacion[0]->utm_east, 0, ',', '.') . ' m';
        $is_dga = $estacion[0]->is_dga;

        $side = $this->getLeftMenu($id_estacion,$is_dga);

        return view('estacion-publica', [
            'estacion' => $estacion[0],
            'is_dga' => $is_dga,
            'side' => $side,
            'gato' =>'little'
        ]);
    }

    public function getLeftMenu($idDevice = null,$is_dga = null)
    {
        try 
        {
           
            $sectores = $this->getsSectores();      
            return $this->generateDropdownHTML($sectores, $idDevice,$is_dga);
        } catch (\Exception $e) {
            return '<p>Error al generar el menú lateral</p>';
        }
    }

    

    private function getStations($sector)
    {
        try {
            //$estaciones = Estacion::where('sector', $sector)->orderBy('nombre')->get();
              $estaciones = Estacion::where('sector', $sector)
                 ->where('enable_site', '1')
                 ->orderBy('nombre')
                 ->get();

            return ['submenu' => json_decode(json_encode($estaciones), true)];
        } catch (\Exception $e) {
            return ['submenu' => []];
        }
    }

    private function generateDropdownHTML($array, $idDevice = null,$is_dga=null)
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

            $html .= '<button class="accordion-button ' . ($isOpen ? "" : "collapsed") . ' ' . ($isOpen !== true ? 'no-arrow' : '') . '" 
                        type="button" 
                        id-sector="' . $menu['id_sector'] . '" 
                        ' . ($isOpen === true
                            ? 'data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '" aria-expanded="true" aria-controls="' . $collapseId . '"'
                            : 'onclick="window.location.href=\'' . url('/sector/' . $menu['id_sector']) . '\'"') . '>';

      
            $html .= '<span class="' . htmlspecialchars($menu['icon']) . '"></span>';
            $html .= '&nbsp; ' . htmlspecialchars($menu['sector']);
            $html .= '</button>';
            $html .= '</h2>';


            $html .= '<div id="' . $collapseId . '" class="accordion-collapse collapse' . ($isOpen ? ' show' : '') . '" 
                        aria-labelledby="' . $headingId . '" 
                        data-bs-parent="#accordionExample">';
            $html .= '<div class="accordion-body" style="background-color: #f5f5f5;">';
            $html .= '<div class="list-group-container">';
            $html .= '<ul class="list-group">';

            foreach ($submenus as $submenu) {
                $submenu = (array) $submenu; 
                $isSelected = isset($submenu['estacion_id']) && $submenu['estacion_id'] == $idDevice;
                
                $html .= '<a title="Ver datos de estación" 
                            href="' . url('estacion-publica/' . urlencode($submenu['estacion_id'])) . '" 
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
      

        if ($is_dga == 1) {
            $html .= '<br><hr>';
            $html .= '<div class="d-grid mt-3">';
            $html .= '<a href="https://snia.mop.gob.cl/dgasat/pages/dgasat_param/dgasat_param.jsp?param=1" 
                         target="_blank" 
                         class="btn btn-primary accordion-button_" 
                         style="background-color: #537898 !important; color: white; font-weight: normal; 
                                padding: 12px 20px; text-align: center; font-size: 16px; text-decoration: none;">';
            $html .= 'Monitoreo DGA</a>';
            $html .= '<div class="mt-2" style="font-size: 13px; line-height: 170%; color: #666; font-weight: 500; text-align: justify;font-weight: normal;">';
            $html .= '<div style="padding: 13px"><br><p>Los registros de monitoreo de esta web son referenciales, los datos oficiales corresponden al monitoreo efectuado por la DGA.</p></div>';
            $html .= '</div><br><hr>';
            $html .= '</div>';
        }
        
        

       
 

        $html .= '<div class="d-grid mt-3">';
        $html .= '<button id="OpenModal" class="btn btn-primary accordion-button_" 
                    style="background-color: #537898 !important; color: white; font-weight: normal; 
                           padding: 12px 20px; text-align: center; font-size: 16px;" 
                    onclick="">';
        $html .= 'Observaciones';
        $html .= '</button>';
        $html .= '</div>';

        return $html;
    }


    private function getsSectores()
    {
        try {
            $sectores = DB::table('sectores')->get();

            $data = [];
            foreach ($sectores as $sector) {
                $submenu = $this->getStations($sector->id_sector);
                $data[] = [
                    'menu' => (array) $sector, // Convertir stdClass a array
                    'submenu' => $submenu
                ];
            }
            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }
}
