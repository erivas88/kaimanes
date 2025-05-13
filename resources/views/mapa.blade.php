<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
      <title>Sistema de Monitoreo</title>
      <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
      <!-- Bootstrap CSS -->
      <link href="https://cdn.jsdelivr.net/npm/maplibre-gl@4.7.1/dist/maplibre-gl.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://unpkg.com/maplibre-gl-minimap/dist/maplibre-gl-minimap.css">
      <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://kit.fontawesome.com/e5291bc371.js" crossorigin="anonymous"></script>
      <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

   <script async src="https://www.googletagmanager.com/gtag/js?id=G-8HDCBQ80LW"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-8HDCBQ80LW');
</script>

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
   </style>
   <body>
      <div class="main-container">
         <!-- Header -->
         <div class="header d-flex justify-content-between align-items-center px-3" style="background-color: white; padding: 10px 0;">
            <a href="{{ url('/') }}">
            <img   src="{{ asset('images/image.png') }}"
               style="max-width: 150px; height: auto; float: left; margin-right: 15px; padding: 10px 0;" 
               alt="Logo Los Pelambres" class="logo">
            </a>
            <nav class="d-flex">
               <a href="{{ url('/') }}" class="text-dark text-decoration-none mx-2 title_pop hover-underline x">Mapa</a>
               <a href="{{ url('/glosary') }}" class="text-dark text-decoration-none mx-2  title_pop hover-underline">Glosario</a>      
            </nav>
         </div>
         <nav class="d-flex align-items-center px-4 py-3" style="background: linear-gradient(to right, #02697e, #3e98a6);">
            <a href="{{ url('/') }}" class="text-white text-decoration-none mx-1 fw-bold">Sistema de Mediciones en Linea de Aguas - Valle Pupío</a>
         </nav>
         <div class="container-fluid mt-4">
            <div class="row" style="padding-top: 20px;">
               <div class="col-md-3">
                  <div class="panel">
                     <a data-bs-toggle="tooltip"
                        data-bs-placement="left"
                        title="Selecciona un sector para visualizar las estaciones">
                        <div  style="background-color: #f5f5f5; padding: 15px 10px 0 10px;">
                           <h5 class="fw-bold title_pop"> Seleccionar </h5>
                           <hr style="width: 100%">
                        </div>
                     </a>
                     <div style="background-color: #f5f5f5">
                        {!! $dropdown !!}
                     </div>
                  </div>
               </div>
               <div class="col-md-9">
                  <div class="panel">
                     <div style="background-color: transparent; padding: 15px 10px 0 10px;">
                        <h5 class="fw-bold title_pop"> <span class="title_reply" style="color: #555555"  id="sectorView"><span  style="color: transparent">.</span></span> </h5>
                        <hr>
                     </div>
                     <div id="map-container" style="height: 600px; position: relative;">
                        <div class="layer-control" style="display: none">
                           <label><input type="radio" name="map-style" value="Street Map"> Mapa</label>     
                           <label><input type="radio" name="map-style" value="Satellite" checked> Satelite</label>
                        </div>
                        <style>
                        </style>
                        <div id="map-legend">
                           <div class="legend-item">
                              <span class="icon-agua-superficial_alt" ></span>&nbsp; Agua Superficial
                           </div>
                           <div class="legend-item">
                              <span class="icon-agua-subterranea_alt" ></span>&nbsp; Agua Subterránea
                           </div>
                           <div class="legend-item">
                              <span class="icon-agua-reservorio_alt" ></span>&nbsp; Reservorio
                           </div>
                        </div>
                     </div>
                     <p class="mt-3 text-muted">
                        <br>                       
                        <!-- <span class="title_pop"  style="color: #555555; font-size: 12px" >En su mayoría, los datos se entregan cada 1 hora y pueden sufrir modificaciones.</span>-->
                        <span class="title_pop"  style="color: #555555; font-size: 12px" > Los datos son entregados con una frecuencia de una hora, lo que asegura una actualización constante y puntual de la información.</span>
                     </p>
                  </div>
               </div>
            </div>
         </div>

        
         @include('layouts.partials.footer')
         <style>
            .parrafos{
            font-size: 15px; 
            text-align: justify !important;
            line-height: 1.8; 
            margin-bottom: 15px;
            color : #4a4a4a !important;
            }
            .parrafo_red{
            font-size: 22px !important; 
            color: #C0392B !important;
            }
            .modal-lgx {
            max-width: 960px;
            }
         </style>
         <div class="modal fade" id="welcome" tabindex="-1" aria-labelledby="miModalLabel">
            <div class="modal-dialog modal-lgx modal-dialog-centered">
               <div class="modal-content">
                  <div class="modal-header">
                     <div style="margin-right: auto; display: flex; align-items: center;">
                        <button type="button" data-bs-dismiss="modal" aria-label="Cerrar" style="color: red; font-size: 25px; border: none; background: transparent;">×</button>
                        <span style="margin-left: 5px; font-size: 14px; font-weight: bold">Cerrar</span>
                     </div>
                  </div>
                  <div class="modal-body" style="font-weight: normal !important; font-size: 12px;">
                     <div class="d-flex flex-wrap">
                        <div class="col-lg-8" style="padding: 10px 20px 10px 10px;">
                           <span class="parrafo_red" style="font-size: 20px;">
                           Bienvenido al sistema de monitoreo de aguas <br><b>Valle Pupío</b>
                           </span>
                           <br><br><br>
                           <p class="parrafos">
                              <b>Como parte del Acuerdo Marco de Entendimiento y Cooperación Recíproca entre Minera Los Pelambres y Habitantes del Valle de Pupío</b>, 
                              se estableció la habilitación de un Sistema de Monitoreo en Línea, de acceso público, de la cantidad y calidad de las aguas en distintos puntos del Valle.
                           </p>
                           <br>
                           <p class="parrafos">
                              Este sitio web busca asegurar el acceso a la información por parte de la comunidad, incluyendo los distintos parámetros a través de los cuales 
                              se evalúa el estado del recurso hídrico.
                           </p>
                        </div>
                        <div class="col-lg-4 d-flex" style="padding-left: 15px;">
                           <div class="w-100">
                              <img src="{{ asset('images/valle-pupio.jpg') }}" class="img-fluid" style="max-width: 100%; height: auto; border-radius: 1px;">
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button 
                        type="button" 
                        data-bs-dismiss="modal"
                        class="me-auto" 
                        style="
                        background: linear-gradient(45deg, #00768e, #3f8f99);
                        width: 150px; 
                        color: white;
                        padding: 8px 18px;
                        font-size: 14px;
                        line-height: 1.2;
                        border: none;
                        border-bottom-right-radius: 10px; 
                        height: 42px;     
                        transform: scale(1.0);
                        cursor: pointer;">
                     Ingresar
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Bootstrap JS -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>   
      <script src="https://cdn.jsdelivr.net/npm/maplibre-gl@4.7.1/dist/maplibre-gl.min.js"></script>
      <script src="https://unpkg.com/maplibre-gl-minimap/dist/maplibre-gl-minimap.js"></script>
      <script src="{{ asset('map/map.js') }}"></script>
      <script src="{{ asset('utils/utils.js') }}"></script> 
      
   </body>
</html>
