<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Glosario</title>
      <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
      <!-- Bootstrap CSS -->
      <link href="https://cdn.jsdelivr.net/npm/maplibre-gl@4.7.1/dist/maplibre-gl.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://unpkg.com/maplibre-gl-minimap/dist/maplibre-gl-minimap.css">
      <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://kit.fontawesome.com/e5291bc371.js" crossorigin="anonymous"></script>
      <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
   </head>
   <body>
      <div class="main-container">
         <!-- Header -->
         <div class="header d-flex justify-content-between align-items-center px-3" style="background-color: white; padding: 10px 0;">
            <a href="{{ url('/') }}">
            <img  src="{{ asset('images/image.png') }}"
               style="max-width: 150px; height: auto; float: left; margin-right: 15px; padding: 10px 0;" 
               alt="Logo Los Pelambres" class="logo">
            </a>
            <nav class="d-flex">
                <a href="{{ url('/') }}" class="text-dark text-decoration-none mx-2 title_pop">Mapa</a>
                <a href="{{ url('/glosary') }}" class="text-dark text-decoration-none mx-2  title_pop">Glosario</a>      
            </nav>
         </div>
         <nav class="d-flex align-items-center px-4 py-3" style="background: linear-gradient(to right, #02697e, #3e98a6);">
            <a href="{{ url('/') }}" class="text-white text-decoration-none mx-1 fw-bold">Sistema de Mediciones en Linea de Aguas - Valle Pupío</a>

         </nav>

         
         <div class="container-fluid mt-4">
            <div class="row" style="padding-top: 20px;">             
               <div class="col-md-3">
                  <div class="panel">
                     <div style="background-color: #f5f5f5; padding: 15px 10px 0 10px;">
                        <h5 class="fw-bold title_pop"> Seleccionar por :</h5>
                        <hr style="width: 100%">
                     </div>
                     <div id="side" style="background-color: #f5f5f5">
                     </div>
                  </div>
               </div>          
               <div class="col-md-9">
               <div class="panel" style="background-color: #eeeeee; border-bottom-left-radius: 25px; padding-bottom: 15px">
    <div style="background-color: transparent; padding: 15px 10px 0 10px;">
        <h5 class="fw-bold title_pop">       
            <span id="icon_img">&nbsp;</span>&nbsp;
            <span id="primary_title"> <i class="fas fa-book-open" style="font-size: large; color: #60d1cc"></i> &nbsp; Glosario</span>
        </h5>
        <hr />
    </div>
    <div style="padding: 10px;">
        <dl style="display: flex; flex-direction: column; gap: 15px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <img  src="{{ asset('glosario/ce.jpg') }}" style="width: 100px; height: 100px">
                <div>
                    <dt><strong class="title">Conductividad:</strong></dt>
                    <dd class="subtitle">Es la capacidad de una solución de transmitir una corriente eléctrica. Cuando una solución tiene compuestos inorgánicos como sales y metales, suele tener alta conductividad.</dd>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; gap: 15px;">
                <img  src="{{ asset('glosario/ph_1.jpg') }}" style="width: 100px; height: 100px">
                <div>
                    <dt><strong class="title">pH:</strong></dt>
                    <dd class="subtitle">Medida de acidez o alcalinidad de una solución. Un agua "neutra" tiene un valor de pH de 7.</dd>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; gap: 15px;">
            <img  src="{{ asset('glosario/caudal.jpg') }}" style="width: 100px; height: 100px">
                <div>
                    <dt><strong class="title">Caudal:</strong></dt>
                    <dd class="subtitle">Corresponde al volumen de agua que circula por un cauce o tubería en un tiempo determinado.</dd>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; gap: 15px;">
            <img  src="{{ asset('glosario/nf_.jpg') }}" style="width: 100px; height: 100px">
                <div>
                    <dt><strong class="title">Nivel freático:</strong></dt>
                    <dd class="subtitle">Corresponde al nivel superior que alcanza el agua acumulada en el subsuelo.</dd>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; gap: 15px;">
                    <img  src="{{ asset('glosario/limini.jpg') }}" style="width: 100px; height: 100px">
                <div>
                    <dt><strong class="title">Nivel o Altura Limnimétrica:</strong></dt>
                    <dd class="subtitle">Es la altura del agua en la sección que la conduce, medida desde la base hasta la superficie libre.</dd>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; gap: 15px;">
            <img  src="{{ asset('glosario/sd.jpg') }}" style="width: 100px; height: 100px">
                <div>
                    <dt><strong class="title">Desviación estándar:</strong></dt>
                    <dd class="subtitle">Corresponde a una medida de dispersión, que representa qué tan alejados están los datos con respecto al promedio.</dd>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 15px;">
            <img  src="{{ asset('glosario/dga.jpg') }}" style="width: 100px; height: 100px">
                <div>
                    <dt><strong class="title">Monitoreo DGA:</strong></dt>
                    <dd class="subtitle">La Dirección General de Aguas (DGA) cuenta con una red de alrededor de 500 estaciones que transmiten en tiempo real.</dd>
                </div>
            </div>
        </dl>
    </div>
</div>

               </div>
            </div>
         </div>
         <style>
         </style>
         <!-- Footer -->
         <div class="footer text-center mt-4 color_mlp">           
            <div class="row align-items-center pdd">
               <div class="col-lg-3 d-flex justify-content-center">
                  <img src="{{ asset('images/antofagasta-mineralsWT.png') }}" style="max-width: 70%; height: auto; padding-top: 10px;" alt="Logo Los Pelambres" class="logo">
               </div>
               <div class="col-lg-3 d-flex flex-column align-items-start">
                  <span class="text-line txt small-text pdd">
                  <span style="font-weight: bold; "><i class="fas fa-mobile-alt"></i> &nbsp; Teléfono:</span> +56 2 3456 7890
                  </span>
                  <span class="text-line txt small-text pdd">
                  <span style="font-weight: bold;"><i class="fas fa-envelope"></i>&nbsp; Email:</span> comunicacionesexternas@pelambres.cl
                  </span>
                  <span class="text-line txt small-text pdd">
                  <span style="font-weight: bold;"><i class="fas fa-globe"></i>&nbsp; Web:</span> www.aminerals.com
                  </span>
               </div>
               <div class="col-lg-3">
                  <div class="section">
                     <p class="small-text jjtxt">Este desarrollo ha sido implementado por <span style="font-weight: bold">GP Consultores</span>, a través de su equipo especializado en soluciones de monitoreo web.
                     gp@gpconsultores.cl
                     <p>
                  </div>
               </div>
               <div class="col-lg-3 recentra">
                  <img src="{{ asset('images/gp-blanco.png') }}" style="max-width: 65%; height: auto; padding-top: 10px;" alt="Logo Los Pelambres" class="logo">
               </div>
            </div>
         </div>
      </div>
      <!-- Bootstrap JS -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>   

      <script src="{{ asset('lst/lst.js') }}"></script>
   </body>
</html>