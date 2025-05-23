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
                  <div class="col-lg-5" style="margin-left: 35px ; margin-top:-65px">
                     <p class="texto_welcome">
                        Con el objetivo de mantener informada a la comunidad, <strong>Minera Los Pelambres</strong> implementó dos sitios web en los que los vecinos pueden encontrar información en línea respecto a agua y aire en la localidad.
                     </p>
                     <br>
                     <a href="#" class="custom-button_blue" target="_blank">
                        <i class="fas fa-wind  2x icono-viento"></i>&nbsp;
                        <div class="texto-boton">
                           <p> Ir al Sistema de Monitoreo de <br> Calidad de <span class=" calidad ">Aire<span> </p>
                        </div>
                     </a>
                     <br>
                     <br>
                     <a href="{{ url('/mapa') }}" class="custom-button_green">
                        <i class="fas fa-tint 2x icono-viento"></i> &nbsp;
                        <div class="texto-boton">
                           <p> Ir al Sistema de Monitoreo de <br> Calidad de <span class=" calidad ">Agua<span> </p>
                        </div>
                     </a>
                     <br>
                     <br>
                     <a href="#" class="custom-button_orange" target="_blank">
                        <i class="fas fa-cloud 2x icono-viento"></i>&nbsp;
                        <div class="texto-boton">
                           <p> Ir al Sistema de Monitoreo de <br>  Pronostico  <span class=" calidad ">Meteorológico<span> </p>
                        </div>
                     </a>
                  </div>
                  <div></div>
               </div>
            </div>
         </div>
      </div>
      <footer class="footer">
         <div class="container text-center"> 
            <div class="row" style="padding-top: 10px; margin-bottom: -10px">           
               <div class="col-lg-3 d-flex justify-content-center">
                  <img src="{{ asset('images/antofagasta-mineralsWT.png') }}" style="max-width: 80%; height: 80px; margin-top: 5px;" alt="Logo Los Pelambres" class="logo">
               </div>
               <div class="col-lg-3 d-flex flex-column align-items-start">            
                  <span class="text-line txt small-text pdd">
                  <span style="font-weight: normal; font-size: 10px ">
                  <i class="fas fa-mobile-alt"></i> &nbsp; Teléfono: </span > <span style="font-size: 11px !important"> +56 2 3456 7890  </span> 
                  </span>
                  <span class="text-line txt small-text pdd">
                  <span style="font-weight: normal; font-size: 10px ">
                  <i class="fas fa-envelope"></i>&nbsp; Email: </span> <span style="font-size: 11px !important"> comunicacionesexternas@pelambres.cl </span> </span>
                  <span class="text-line txt small-text pdd">
                  <span style="font-weight: normal; font-size: 10px ">
                  <i class="fas fa-globe"></i>&nbsp; Web: </span>  <span style="font-size: 11px !important">  www.aminerals.com  </span> </span>
               </div>
               <div class="col-lg-3">
                  <div class="section" style="padding-top: 10px">
                     <p class="small-text jjtxt" style="font-size: 11px !important">Este desarrollo ha sido implementado por GP Consultores, a través de su equipo especializado en soluciones de monitoreo web. gp@gpconsultores.cl
                     <p>
                  </div>
               </div>
               <div class="col-lg-3 recentra">
                  <img src="{{ asset('images/gp-blanco.png') }}" style="max-width: 65%; height: auto; padding-top: 1px;" alt="Logo Los Pelambres" class="logo">
               </div>
            </div>
         </div>
      </footer>   
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
   </body>
</html>