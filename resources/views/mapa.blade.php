<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
      <title>Sistema de Monitoreo</title>
      @include('layouts.partials.bg-preload')
      <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
      <!-- Bootstrap CSS -->
      <link href="https://cdn.jsdelivr.net/npm/maplibre-gl@4.7.1/dist/maplibre-gl.min.css" integrity="sha384-XPt8o1QuQUrfvyG8MY6VYuFZCJ147mPoGUtgjzC6/j9fw9/scoqo1v96sDhlvnFo" crossorigin="anonymous" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" rel="stylesheet">
      <script src="https://kit.fontawesome.com/e5291bc371.js" crossorigin="anonymous"></script>
      <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ filemtime(public_path('css/styles.css')) }}">
      <link rel="stylesheet" href="{{ asset('css/estacion-publica.css') }}?v={{ filemtime(public_path('css/estacion-publica.css')) }}">
      @include('layouts.partials.app')
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
               <a href="{{ url('/sector') }}" class="text-dark text-decoration-none mx-2 title_pop hover-underline x">Mapa</a>
               <a href="{{ url('/glosary') }}" class="text-dark text-decoration-none mx-2  title_pop hover-underline">Glosario</a>      
            </nav>
         </div>
         <nav class="site-band d-flex align-items-center px-4 py-3">
            <a href="{{ url('/') }}" class="text-white text-decoration-none mx-1 fw-bold">Sistema de Mediciones en Linea de Aguas - Valle Pupío</a>
         </nav>
         <div class="container-fluid mt-4">
            <div class="row" style="padding-top: 20px;">
               <div class="col-md-3">
                  <div class="panel ep-nav">
                     <div title="Selecciona un sector para visualizar las estaciones">
                        <h5 class="fw-bold title_pop">Seleccionar</h5>
                     </div>
                     <div>
                        {!! $dropdown !!}
                     </div>
                  </div>
               </div>
               <div class="col-md-9">
                  <div class="dash ds-surface">
                     <section class="sector-head">
                        <span class="ds-icon ds-icon--md"><i class="fas fa-map-marked-alt"></i></span>
                        <div class="sector-head-text">
                           <div class="ds-label ds-label--accent">Sector</div>
                           <h5 class="sector-title" id="sectorView">{{ $titulo }}</h5>
                        </div>
                     </section>
                     <div id="map-container" class="sector-map">
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
                     <footer class="dash-note">
                        <i class="fas fa-clock"></i>
                        <p><strong>Nota</strong> los datos son entregados con una frecuencia de una hora, lo que asegura una actualización constante y puntual de la información.</p>
                     </footer>
                  </div>
               </div>
            </div>
         </div>

         
         @include('layouts.partials.footer')
         <div class="modal fade welcome-modal" id="welcome" tabindex="-1" aria-labelledby="miModalLabel">
            <div class="modal-dialog modal-lgx modal-dialog-centered">
               <div class="modal-content">
                  <div class="modal-header">
                     <button type="button" class="welcome-close" data-bs-dismiss="modal" aria-label="Cerrar">
                        <span class="welcome-close-x">&times;</span><span>Cerrar</span>
                     </button>
                  </div>
                  <div class="modal-body">
                     <div class="row g-0 align-items-stretch">
                        <div class="col-lg-8 welcome-text">
                           <h2 class="welcome-title">
                              Bienvenido al sistema de monitoreo de aguas <br><b>Valle Pupío</b>
                           </h2>
                           <p class="welcome-lead">
                              <b>Como parte del Acuerdo Marco de Entendimiento y Cooperación Recíproca entre Minera Los Pelambres y Habitantes del Valle de Pupío</b>, 
                              se estableció la habilitación de un Sistema de Monitoreo en Línea, de acceso público, de la cantidad y calidad de las aguas en distintos puntos del Valle.
                           </p>
                           <p class="welcome-p">
                              Este sitio web busca asegurar el acceso a la información por parte de la comunidad, incluyendo los distintos parámetros a través de los cuales 
                              se evalúa el estado del recurso hídrico.
                           </p>
                        </div>
                        <div class="col-lg-4 welcome-photo ep-img">
                           <img src="{{ asset('images/valle-pupio.jpg') }}" alt="Valle Pupío">
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" data-bs-dismiss="modal" class="welcome-cta">
                        <span>Ingresar</span><i class="fas fa-arrow-right"></i>
                     </button>
                  </div>
               </div>
            </div>
         </div>
</div>
      <!-- Bootstrap JS -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/axios@1.20.0/dist/axios.min.js" integrity="sha384-mIJWc6qj9ALoBF2yHkNOf0fbGel66+X3UkhYw57p36/PaTyDpO9pvnxrVZ7ZGJfi" crossorigin="anonymous"></script>   
      <script src="https://cdn.jsdelivr.net/npm/maplibre-gl@4.7.1/dist/maplibre-gl.min.js" integrity="sha384-2h8mp2ca/CxhPdLvv76KPLBoqmxlTL4u1ptEZdfvv1qxkZyRZx2u/WUnyUC3Ky6U" crossorigin="anonymous"></script>
      <script src="{{ asset('map/map.js') }}?v={{ filemtime(public_path('map/map.js')) }}"></script>
      <script src="{{ asset('utils/utils.js') }}?v={{ filemtime(public_path('utils/utils.js')) }}"></script>
      <script src="{{ asset('js/ep-nav.js') }}?v={{ filemtime(public_path('js/ep-nav.js')) }}"></script>
      <script src="{{ asset('js/visita.js') }}?v={{ filemtime(public_path('js/visita.js')) }}"></script>
      <script src="{{ asset('js/ep-img.js') }}?v={{ filemtime(public_path('js/ep-img.js')) }}"></script>
      
   </body>
</html>
