<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>{{ $estacion->nombre }}</title>
      <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon" />
      <link href="https://cdn.jsdelivr.net/npm/maplibre-gl@4.7.1/dist/maplibre-gl.min.css" rel="stylesheet" />
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
      <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
      <link src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
      <link rel="stylesheet" href="{{ asset('css/styles.css') }}" rel="stylesheet" />
      <script src="https://kit.fontawesome.com/e5291bc371.js" crossorigin="anonymous"></script>
   </head>
   <style>
      .hover-underline {
      position: relative;
      text-decoration: none;
      color: inherit; /* Hereda el color del texto */
      }
      .hover-underline::after {
      content: "";
      position: absolute;
      left: 0;
      bottom: 0;
      width: 0;
      height: 3px; /* Grosor del subrayado */
      background-color: #318e9d;
      transition: width 0.3s ease;
      }
      .hover-underline:hover::after {
      width: 100%;
      }
      .notas_view {
      font-size: 13px !important;
      color: #666;
      margin-bottom: 5px; /* Reduce el margen inferior */
      line-height: 170%;
      font-family: "Poppins", serif;
      text-align: justify;
      padding: 20px;
      color: #949494;
      }
      .ultima-nota {
      margin-top: -30px; /* Reduce la separación */
      }
      .no-arrow::after {
      display: none !important;
      }
      label[for="dt-search-0"] {
      display: none;
      }
   </style>
   <body>
      <div class="main-container">
         <div class="header d-flex justify-content-between align-items-center px-3" style="background-color: white; padding: 10px 0;">
            <a href="{{ url('/') }}">
            <img src="{{ asset('images/image.png') }}" style="max-width: 150px; height: auto; float: left; margin-right: 15px; padding: 10px 0;" alt="Logo Los Pelambres" class="logo" />
            </a>
            <nav class="d-flex">
               <a href="{{ url('/mapa') }}" class="text-dark text-decoration-none mx-2 title_pop hover-underline">Mapa</a>
               <a href="{{ url('/glosary') }}" class="text-dark text-decoration-none mx-2 title_pop hover-underline">Glosario</a>
            </nav>
         </div>
         <nav class="d-flex align-items-center px-4 py-3 justify-content-between" style="background: linear-gradient(to right, #02697e, #3e98a6);">
            <a href="{{ url('/') }}" class="text-white text-decoration-none fw-bold">Sistema de Mediciones en Linea de Aguas - Valle Pupío</a>
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb m-0">
                  <li class="breadcrumb-item">
                     <a href="{{ url('/sector/' . $estacion->sector) }}" class="text-white text-decoration-none fw-bold" data-bs-toggle="tooltip" title="Ver en mapa" data-bs-placement="bottom">
                     {{ $estacion->descripcion }}
                     </a>
                  </li>
                  <li class="breadcrumb-item">
                     <a href="{{ url('/estacion-publica/' . $estacion->estacion_id) }}" class="text-white text-decoration-none">
                     {{ $estacion->nombre }}
                     </a>
                  </li>
               </ol>
            </nav>
         </nav>
         <div class="container-fluid mt-4">
            <div class="row" style="padding-top: 20px;">
               <div class="col-md-3">
                  <div class="panel">
                     <div style="background-color: #f5f5f5; padding: 15px 10px 0 10px;">
                        <h5 class="fw-bold title_pop">Seleccionar </h5>
                        <hr style="width: 100%;" />
                     </div>
                     <div style="background-color: #f5f5f5;">
                        {!! $side !!}
                     </div>
                  </div>
               </div>
               <div class="col-md-9">
                  <div class="panel" style="background-color: #eeeeee; border-bottom-left-radius: 25px; padding-bottom: 15px; box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);">
                     <div style="background-color: transparent; padding: 15px 10px 0 10px;">
                        <h5 class="fw-bold title_pop"><span class="{{ $estacion->icon_titlte }}">&nbsp;</span> &nbsp; <span id="primary_title"> {{ $estacion->nombre }}</span></h5>
                        <hr />
                     </div>
                     <div class="row align-items-center detail-container">
                        <div class="col-lg-7 detail-text" style="padding-top: -20px !important;">
                           <div class="container">
                              <div class="row detail-row">
                                 <div class="col-4 title">Cuenca:</div>
                                 <div class="col-7 subtitle" id="cuencaStation">{{ $estacion->cuenca }}</div>
                              </div>
                              <div class="row detail-row">
                                 <div class="col-4 title">Subcuenca:</div>
                                 <div class="col-7 subtitle" id="subcuencaStation">{{ $estacion->subcuenca }}</div>
                              </div>
                              <div class="row detail-row">
                                 <div class="col-4 title">Región:</div>
                                 <div class="col-7 subtitle" id="regionStation">{{ $estacion->region }}</div>
                              </div>
                              <div class="row detail-row">
                                 <div class="col-4 title">Tipo de Monitoreo:</div>
                                 <div class="col-7 subtitle" id="typeStation">{{ $estacion->descripcion }}</div>
                              </div>
                              <div class="row detail-row">
                                 <div class="col-4 title">Coordenadas:</div>
                                 <div class="col-7 subtitle" id="coordenadaStation">{{ $estacion->utm_north }} ; {{ $estacion->utm_east }} ; {{ $estacion->utm_datum }}</div>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-5 detail-image">
                           <img id="imgDevice" src="{{ $estacion->link_imagen }}" alt="loading.." class="img_device" />
                        </div>
                     </div>
                  </div>
                  <br />
                  <div class="row">
                     <div class="col-lg-12">
                        <br />
                        <div class="row">
                           <div class="col-lg-6">
                              <div>
                                 <label for="dateRangeSelect" class="selectOption"><i style="color: #60d1cc; font-size:normal" class="fas fa-calendar-alt gradient-icon"></i>&nbsp;Periodo de tiempo</label>
                                 <select id="dateRangeSelect" style="width: 100%;"> </select>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div>
                                 <!--<i class="fas fa-map-marker-alt gradient-icon"></i>-->
                                 <label for="typeSelect" class="selectOption"><i  class="fas fa-leaf gradient-icon"></i>&nbsp;Tipo de parámetro</label>
                                 <select id="typeSelect" style="width: 100%;"> </select>
                              </div>
                           </div>
                        </div>
                        <div style="margin-left: 0px; margin-right: 10px;">
                           <br />
                           <div id="conductivityChart" style="width: 100%; height: 550px; padding: 0px; position: relative;">
                              <div id="loadingSpinner" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;">
                                 <div style="border: 4px solid #f3f3f3; border-radius: 50%; border-top: 4px solid #3498db; width: 40px; height: 40px; animation: spin 1s linear infinite;"></div>
                              </div>
                           </div>
                           <br />
                           <div style="margin-left: 10px; margin-right: 10px; background-color: #eee;">
                              <style></style>
                              <p id="notas" class="notas_view"></p>
                              <p class="ultima-nota" style="font-size: 13px; color: #949494; margin-bottom: 20px; line-height: 170%; font-family: 'Poppins', serif; text-align: justify; padding: 20px;">
                                 <i class="fas fa-solid fa-bookmark" style="color: #07798f;"></i>
                                 <span class="title_legend">Nota :</span><span class="subtitle">la información de monitoreo disponible está sujeta a las condiciones de conectividad y telecomunicaciones en la zona. La ejecución de mantenciones en los equipos y/o algún otro evento pueden alterar momentáneamente los registros. Para mayores detalles ver sección "Observaciones"</span>
                              </p>
                              <p style="font-size: 14px; font-weight: normal; margin-bottom: 10px; text-align: justify; padding: 10px; font-family: 'Poppins', serif; text-align: center; color: #949494;" class="title_legend">
                                 Detalles de la variable <span id="plotVar"> </span> en el rango: <span id="dateMin">2024-12-31 00:00:00</span> al <span id="dateMax">2024-12-31 00:00:00</span>
                              </p>

                              <div class="stats-container" style="display: flex; justify-content: space-around; margin-top: 10px; padding: 10px; font-family: 'Poppins';">
                              
                              </div>
                                                         
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- Footer -->
         @include('layouts.partials.footer')
         <div class="modal fade" id="observations" tabindex="-1" aria-labelledby="miModalLabel">
            <div class="modal-dialog modal-lg">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="miModalLabel">Observaciones</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                  </div>
                  <div class="modal-body" style="font-weight: normal !important; font-size: 12px";>
                     <style>
                        thead th {
                        background-color: #318a97 !important;
                        color : white ;
                        }
                        .tabla_h{
                        font-size: 13px;
                        font-weight: bold;
                        }
                        .dataTables_empty {
                        text-align: center;
                        }
                     </style>
                     <table class="table" id="tableObservations" style="width:100%">
                        <thead>
                           <tr>
                              <th class="tabla_h">Estación</th>
                              <th class="tabla_h">Variable</th>
                              <th class="tabla_h">Desde</th>
                              <th class="tabla_h">Hasta</th>
                              <th class="tabla_h">Descripción</th>
                           </tr>
                        </thead>
                     </table>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal fade" id="welcome" tabindex="-1" aria-labelledby="miModalLabel">
            <div class="modal-dialog modal-lg">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="miModalLabel">Observaciones</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                  </div>
                  <div class="modal-body" style="font-weight: normal !important; font-size: 12px";>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Bootstrap JS -->
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>      
      <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
      <script src="{{ asset('stock/code/highstock.js') }}"></script>
      <script src="{{ asset('stock/code/modules/data.js') }}"></script>
      <script src="{{ asset('stock/code/modules/export-data.js') }}"></script>
      <script src="{{ asset('stock/code/modules/accessibility.js') }}"></script>
      <script src="{{ asset('stock/code/modules/no-data-to-display.js') }}"></script>
      <script src="{{ asset('lst/lst_op.js') }}"></script>
      <script src="{{ asset('plot/plot.js') }}"></script>
      <script>
         document.addEventListener("DOMContentLoaded", function () {
             var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
             var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                 return new bootstrap.Tooltip(tooltipTriggerEl);
             });
         });
      </script>
      <script></script>
   </body>
</html>