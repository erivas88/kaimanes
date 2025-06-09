<!DOCTYPE html>
<html lang="es">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Monitoreo Caimanes</title>
      <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
      <link rel="stylesheet" href="{{ asset('css/styles_login.css') }}">
      <script src="https://www.google.com/recaptcha/api.js" async defer></script>

   </head>
   <body>
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-6 left-section">
               <div class="overlay">
                  <div>
                     <h2>Bienvenido a</h2>
                     <h1>Monitoreo<br>Caimanes</h1>
                  </div>
               </div>
            </div>
            <div class="col-lg-6 right-section">
               <div class="row" >
                  <div class="col-lg-6" style="margin-left: 35px ; margin-top:-65px">
                     <p class="texto_welcome">
                        Con el objetivo de mantener informada a la comunidad, <strong>Minera Los Pelambres</strong> implementó dos sitios web en los que los vecinos pueden encontrar información en línea respecto a agua y aire en la localidad.
                     </p>
                     <br>
                     <a href="http://cloud.r9.cl/pelambres_publico/" class="custom-button-green"  >
                        <i class="fas fa-wind icono-viento"></i>
                        <span class="texto-boton">
                        Ir al Sistema de Monitoreo de <br><strong> Calidad del Aire</strong>
                        </span>
                     </a>
                     <br>
                     
                        <a href="{{ url('/mapa') }}" class="custom-button-blue" target="">
                        <i class="fas fa-tint icono-viento"></i>
                        <span class="texto-boton">
                        Ir al Sistema de Monitoreo de <br><strong> Calidad del Agua</strong>
                        </span>
                        </a>
                     <br>
                 
                     <a href="https://monitoreocaimanes.meteodata.cl/" class="custom-button-orange" target="_blank">
                        <i class="fas fa-cloud icono-viento"></i>
                        <span class="texto-boton">
                        Ir al Sistema de Monitoreo de <br> <strong> Pronóstico Meteorológico</strong>
                        </span>
                     </a>
                  </div>
                  <div></div>
               </div>
            </div>
         </div>
      </div>
      <style>
      </style>
      <footer class="footer">
         <div class="container py-4">
            <div class="row align-items-center">
               <!-- Columna 1: Logo Antofagasta -->
               <div class="col-lg-3 text-center mb-3 mb-lg-0">
                  <img src="{{ asset('images/antofagasta-mineralsWT.png') }}" style="max-width: 80%; height: 55px;" alt="Logo Los Pelambres" class="logo">
               </div>
               <!-- Columna 2: Información de contacto -->
               <div class="col-lg-3">
                  <p class="small-text mb-1">
                      Teléfono: +56 2 3456 7890
                  </p>
                  <p class="small-text mb-1">
                      Email: comunicacionesexternas@pelambres.cl
                  </p>
                  <p class="small-text mb-0">
                      Web:  www.aminerals.com
                  </p>
               </div>
               <!-- Columna 3: Créditos -->
               <div class="col-lg-3">
                  <p class="small-text mb-0">
                     Este desarrollo ha sido implementado por GP Consultores, a través de su equipo especializado en soluciones de monitoreo web. <br>
                     gp@gpconsultores.cl
                  </p>
               </div>
               <!-- Columna 4: Logo GP -->
               <div class="col-lg-3 text-center mt-3 mt-lg-0">
                  <img src="{{ asset('images/gp-blanco.png') }}" style="max-width: 65%; height: auto;" alt="Logo GP" class="logo">
               </div>
            </div>
         </div>
      </footer>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
   </body>
</html>