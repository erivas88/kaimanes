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
    <div class="main-container">
      <!-- Header -->
      <div class="header d-flex justify-content-between align-items-center px-3" style="background-color: white; padding: 10px 0;">
        <a href="{{ url('/') }}">
          <img src="{{ asset('images/image.png') }}" style="max-width: 150px; height: auto; float: left; margin-right: 15px; padding: 10px 0;" alt="Logo Los Pelambres" class="logo">
        </a>
        <nav class="d-flex">
          <a href="{{ url('/') }}" class="text-dark text-decoration-none mx-2 title_pop hover-underline">Mapa</a>
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
              <div style="background-color: #f5f5f5; padding: 15px 10px 0 10px;">
                <h5 class="fw-bold title_pop"> Seleccionar por :</h5>
                <hr style="width: 100%">
              </div>
                <div style="background-color: #f5f5f5">
                      {!! $dropdown !!}
                </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="panel" style="background-color: #eeeeee; border-bottom-left-radius: 25px; padding-bottom: 15px">
              <div style="background-color: transparent; padding: 15px 10px 0 10px;">
                <h5 class="fw-bold title_pop">
                  <span id="icon_img">&nbsp;</span>&nbsp; <span id="primary_title">
                    <i class="fas fa-book-open" style="font-size: large; color: #60d1cc"></i> &nbsp; Glosario </span>
                </h5>
                <hr />
              </div>
              <div style="padding: 10px;">
                <dl style="display: flex; flex-direction: column; gap: 15px;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="{{ asset('glosario/agua_subterraneas_.jpg') }}" style="width: 100px; height: 100px">
                    <div>
                      <dt>
                        <strong class="title">Aguas Subterráneas</strong>
                      </dt>
                      <dd class="subtitle_glosario" style="text-align:justify">
                      Las aguas subterráneas son aquellas que se encuentran almacenadas en el subsuelo, en formaciones geológicas conocidas como acuíferos. Se originan a partir de la infiltración del agua de lluvia, ríos o lagos, que se filtra a través de capas permeables del suelo y la roca. Representan una fuente crucial de abastecimiento para el consumo humano, la agricultura y la industria, especialmente en zonas áridas donde los recursos hídricos superficiales son limitados. 
                    </dd>
               
                    </div>
                  </div>
              

                  <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="{{ asset('glosario/agua_superficial_.jpg') }}" style="width: 100px; height: 100px">
                    <div>
                      <dt>
                        <strong class="title">Aguas Superficiales</strong>
                      </dt>
                      <dd class="subtitle_glosario" style="text-align:justify">
                      Las aguas superficiales son aquellas que fluyen o se almacenan en la superficie terrestre, como ríos, lagos, lagunas y embalses. Estas fuentes de agua dependen del régimen de precipitaciones, el deshielo de glaciares y el aporte de manantiales. Son fundamentales para el equilibrio ecológico y el abastecimiento de agua para diversas actividades humanas, incluyendo el riego, la generación de energía hidroeléctrica y el consumo doméstico.
                    </dd>
                    </div>
                  </div>

                  <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="{{ asset('glosario/piscina_tipay.jpg') }}" style="width: 100px; height: 100px">
                    <div>
                      <dt>
                        <strong class="title">Reservorios </strong>
                      </dt>
                      <dd class="subtitle_glosario" style="text-align:justify">
                      Los reservorios son cuerpos de agua artificiales o naturales utilizados para almacenar y regular el suministro de agua para distintos fines, como el consumo humano, el riego agrícola, la producción de energía y el control de inundaciones. Pueden formarse mediante la construcción de represas en ríos o mediante la adecuación de lagunas y embalses naturales. 
                    </dd>
                    </div>
                  </div>


                  <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="{{ asset('glosario/conductividad_.jpg') }}" style="width: 100px; height: 100px">
                    <div>
                      <dt>
                        <strong class="title">Conductividad</strong>
                      </dt>
                      <dd class="subtitle_glosario" style="text-align:justify">
                      La medición de la conductividad eléctrica permite evaluar la cantidad de sales disueltas en el agua de riego y prevenir problemas de salinización que pueden afectar los cultivos. 
                      Un monitoreo constante de la calidad del agua permite tomar decisiones informadas, asegurando la sostenibilidad del riego y la conservación de los suelos agrícolas
                       </dd>
                    </div>
                  </div>

                  <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="{{ asset('glosario/ph_medicion.jpg') }}" style="width: 100px; height: 100px">
                    <div>
                      <dt>
                        <strong class="title">pH</strong>
                      </dt>
                      <dd class="subtitle_glosario" style="text-align:justify">
                      La calidad del agua en está sujeta a regulaciones que incluyen parámetros físico-químicos clave como el pH. Este indicador de acidez/alcalinidad es fundamental, pues mantener el pH en rangos adecuados ayuda a asegurar la potabilidad del agua, la productividad agrícola y la salud de los ecosistemas acuáticos.  Chile cuenta con normativas técnicas que fijan rangos aceptables de pH según el uso del agua. La principal es la Norma Chilena Oficial NCh 1333 (Of.1978) EL Agua de riego agrícola, Debe tener un pH entre 5,5 u.pH y 9,0 u.pH.
                      </dd>
                    </div>
                  </div>

                  <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="{{ asset('glosario/caudal_medicion.jpg') }}" style="width: 100px; height: 100px">
                    <div>
                      <dt>
                        <strong class="title">Caudal</strong>
                      </dt>
                      <dd class="subtitle_glosario" style="text-align:justify">
                      El caudal es el volumen de agua que fluye por un cauce o tubería en un tiempo determinado (L/s o m³/s) y es un factor clave en la gestión del riego.  En Chile, la DGA establece caudales ecológicos mínimos para proteger los ecosistemas y garantizar un uso sustentable del agua, asegurando un equilibrio entre la demanda agrícola y la conservación del recurso hídrico
                      </dd>
                    </div>
                  </div>

                  <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="{{ asset('glosario/nivel_freatico.png') }}" style="width: 100px; height: 100px">
                    <div>
                      <dt>
                        <strong class="title">Nivel Freático</strong>
                      </dt>
                      <dd class="subtitle_glosario" style="text-align:justify">
                      El nivel freático es la superficie subterránea donde la presión del agua es igual a la atmosférica, marcando el límite superior de la zona saturada de un acuífero. Su monitoreo permite regular la extracción de agua subterránea y prevenir la sobreexplotación de los acuíferos, por lo que en Chile, la Dirección General de Aguas (DGA) supervisa su comportamiento mediante pozos de observación y mediciones periódicas
                      </dd>
                    </div>
                  </div>                  
                  
                  <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="{{ asset('glosario/regla.jpg') }}" style="width: 120px !important; height: 100px">
                    <div>
                      <dt>
                        <strong class="title">Nivel o Altura Limnimétrica</strong>
                      </dt>
                      <dd class="subtitle_glosario" style="text-align:justify">
                      Se refiere a la medida de la altura de la superficie del agua en un cuerpo hídrico, como ríos, lagos, embalses o canales. Esta medición se realiza utilizando escalas limnimétricas, que son dispositivos graduados instalados verticalmente o en taludes para proporcionar una lectura visual directa del nivel del agua. El monitoreo constante del nivel limnimétrico es esencial para la gestión de recursos hídricos, permitiendo anticipar crecidas, gestionar embalses y garantizar un suministro adecuado de agua para diversas actividades. </dd>
                    </div>
                  </div>

                  <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="{{ asset('glosario/sd.jpg') }}" style="width: 120px !important ; height: 100px">
                    <div>
                      <dt>
                        <strong class="title">Desviación estándar</strong>
                      </dt>
                      <dd class="subtitle_glosario" style="text-align:justify">
                      La desviación estándar es una medida que nos indica cuánto varían los valores de un conjunto de datos con respecto a su promedio. Si la desviación estándar es baja, significa que los datos están muy próximos entre sí y al valor promedio, mientras que una desviación alta indica que los valores están más dispersos. 
                      </dd>
                    </div>
                  </div>

                  <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="{{ asset('glosario/dga.jpg') }}" style="width: 100px; height: 100px">
                    <div>
                      <dt>
                        <strong class="title">Monitoreo DGA </strong>
                      </dt>
                      <dd class="subtitle_glosario" style="text-align:justify">
                      La Dirección General de Aguas (DGA) de Chile gestiona una amplia red hidrométrica nacional compuesta por aproximadamente 650 estaciones hidrometeorológicas. Estas estaciones transmiten datos en tiempo real mediante sistemas satelitales o GPRS. Esta infraestructura es esencial para monitorear y gestionar los recursos hídricos del país, proporcionando información crucial para la toma de decisiones en ámbitos como la agricultura, la gestión ambiental y la prevención de desastres naturales.
                      </dd>
                    </div>
                  </div>

                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>  
      <!-- Footer -->
       @include('layouts.partials.footer')
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('lst/lst.js') }}"></script>
  </body>
</html>