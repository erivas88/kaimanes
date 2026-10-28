<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glosario</title>
    @include('layouts.partials.bg-preload')
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/maplibre-gl@4.7.1/dist/maplibre-gl.min.css" integrity="sha384-XPt8o1QuQUrfvyG8MY6VYuFZCJ147mPoGUtgjzC6/j9fw9/scoqo1v96sDhlvnFo" crossorigin="anonymous" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" rel="stylesheet">
    <script src="https://kit.fontawesome.com/e5291bc371.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.31/dist/fancybox.css" integrity="sha384-kPPlFUTv5pDfb8JmcIDGDPhxzM4ZG0aIQ5/7vANkrD1Z7GR9e3k459LTa7fDCOX/" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ filemtime(public_path('css/styles.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/estacion-publica.css') }}?v={{ filemtime(public_path('css/estacion-publica.css')) }}">
    
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
      <nav class="site-band d-flex align-items-center px-4 py-3">
        <a href="{{ url('/') }}" class="text-white text-decoration-none mx-1 fw-bold">Sistema de Mediciones en Linea de Aguas - Valle Pupío</a>
      </nav>
      <div class="container-fluid mt-4">
        <div class="row" style="padding-top: 20px;">
          <div class="col-md-3">
            <div class="panel ep-nav">
              <div>
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
                  <span class="ds-icon ds-icon--md"><i class="fas fa-book-open"></i></span>
                  <div>
                     <div class="ds-label ds-label--accent">Conceptos</div>
                     <h5 class="sector-title" id="primary_title">Glosario</h5>
                  </div>
               </section>
               <section class="gl-list">
                  <article class="gl-item">
                     <a class="gl-thumb ep-img" data-fancybox="gallery" data-caption="Aguas Subterráneas" href="{{ asset('glosario/agua_subterraneas_.jpg') }}" aria-label="Ampliar imagen: Aguas Subterráneas"><img src="{{ asset('glosario/agua_subterraneas_.jpg') }}" alt="Aguas Subterráneas" loading="lazy"><span class="zoom-badge" aria-hidden="true"><i class="fas fa-plus"></i></span></a>
                     <div class="gl-body">
                        <h6 class="gl-term">Aguas Subterráneas</h6>
                        <p class="gl-def">Las aguas subterráneas son aquellas que se encuentran almacenadas en el subsuelo, en formaciones geológicas conocidas como acuíferos. Se originan a partir de la infiltración del agua de lluvia, ríos o lagos, que se filtra a través de capas permeables del suelo y la roca. Representan una fuente crucial de abastecimiento para el consumo humano, la agricultura y la industria, especialmente en zonas áridas donde los recursos hídricos superficiales son limitados.</p>
                     </div>
                  </article>
                  <article class="gl-item">
                     <a class="gl-thumb ep-img" data-fancybox="gallery" data-caption="Aguas Superficiales" href="{{ asset('glosario/agua_superficial_.jpg') }}" aria-label="Ampliar imagen: Aguas Superficiales"><img src="{{ asset('glosario/agua_superficial_.jpg') }}" alt="Aguas Superficiales" loading="lazy"><span class="zoom-badge" aria-hidden="true"><i class="fas fa-plus"></i></span></a>
                     <div class="gl-body">
                        <h6 class="gl-term">Aguas Superficiales</h6>
                        <p class="gl-def">Las aguas superficiales son aquellas que fluyen o se almacenan en la superficie terrestre, como ríos, lagos, lagunas y embalses. Estas fuentes de agua dependen del régimen de precipitaciones, el deshielo de glaciares y el aporte de manantiales. Son fundamentales para el equilibrio ecológico y el abastecimiento de agua para diversas actividades humanas, incluyendo el riego, la generación de energía hidroeléctrica y el consumo doméstico.</p>
                     </div>
                  </article>
                  <article class="gl-item">
                     <a class="gl-thumb ep-img" data-fancybox="gallery" data-caption="Reservorios" href="{{ asset('glosario/piscina_tipay.jpg') }}" aria-label="Ampliar imagen: Reservorios"><img src="{{ asset('glosario/piscina_tipay.jpg') }}" alt="Reservorios" loading="lazy"><span class="zoom-badge" aria-hidden="true"><i class="fas fa-plus"></i></span></a>
                     <div class="gl-body">
                        <h6 class="gl-term">Reservorios</h6>
                        <p class="gl-def">Los reservorios son cuerpos de agua artificiales o naturales utilizados para almacenar y regular el suministro de agua para distintos fines, como el consumo humano o el riego agrícola. Pueden formarse mediante la construcción de estanques en lagunas, piscinas , embalses naturales u otros.</p>
                     </div>
                  </article>
                  <article class="gl-item">
                     <a class="gl-thumb ep-img" data-fancybox="gallery" data-caption="Conductividad" href="{{ asset('glosario/conductividad_.jpg') }}" aria-label="Ampliar imagen: Conductividad"><img src="{{ asset('glosario/conductividad_.jpg') }}" alt="Conductividad" loading="lazy"><span class="zoom-badge" aria-hidden="true"><i class="fas fa-plus"></i></span></a>
                     <div class="gl-body">
                        <h6 class="gl-term">Conductividad</h6>
                        <p class="gl-def">La medición de la conductividad eléctrica permite evaluar la cantidad de sales disueltas en el agua.</p>
                     </div>
                  </article>
                  <article class="gl-item">
                     <a class="gl-thumb ep-img" data-fancybox="gallery" data-caption="pH" href="{{ asset('glosario/pH_Table.png') }}" aria-label="Ampliar imagen: pH"><img src="{{ asset('glosario/pH_Table.png') }}" alt="pH" loading="lazy"><span class="zoom-badge" aria-hidden="true"><i class="fas fa-plus"></i></span></a>
                     <div class="gl-body">
                        <h6 class="gl-term">pH</h6>
                        <p class="gl-def">La calidad del agua en está sujeta a regulaciones que incluyen parámetros físico-químicos clave como el pH. Este indicador de acidez/alcalinidad es fundamental, pues mantener el pH en rangos adecuados ayuda a asegurar la potabilidad del agua, la productividad agrícola y la salud de los ecosistemas acuáticos.</p>
                     </div>
                  </article>
                  <article class="gl-item">
                     <a class="gl-thumb ep-img" data-fancybox="gallery" data-caption="Caudal" href="{{ asset('glosario/caudal_medicion.jpg') }}" aria-label="Ampliar imagen: Caudal"><img src="{{ asset('glosario/caudal_medicion.jpg') }}" alt="Caudal" loading="lazy"><span class="zoom-badge" aria-hidden="true"><i class="fas fa-plus"></i></span></a>
                     <div class="gl-body">
                        <h6 class="gl-term">Caudal</h6>
                        <p class="gl-def">El caudal es el volumen de agua que fluye por un cauce o tubería en un tiempo determinado.</p>
                     </div>
                  </article>
                  <article class="gl-item">
                     <a class="gl-thumb ep-img" data-fancybox="gallery" data-caption="Nivel Freático" href="{{ asset('glosario/nivel_freatico.png') }}" aria-label="Ampliar imagen: Nivel Freático"><img src="{{ asset('glosario/nivel_freatico.png') }}" alt="Nivel Freático" loading="lazy"><span class="zoom-badge" aria-hidden="true"><i class="fas fa-plus"></i></span></a>
                     <div class="gl-body">
                        <h6 class="gl-term">Nivel Freático</h6>
                        <p class="gl-def">El nivel freático es la superficie subterránea donde la presión del agua es igual a la atmosférica, marcando el límite superior de la zona saturada de un acuífero. Su monitoreo permite regular la extracción de agua subterránea y prevenir la sobreexplotación de los acuíferos.</p>
                     </div>
                  </article>
                  <article class="gl-item">
                     <a class="gl-thumb ep-img" data-fancybox="gallery" data-caption="Nivel o Altura Limnimétrica" href="{{ asset('glosario/regla.jpg') }}" aria-label="Ampliar imagen: Nivel o Altura Limnimétrica"><img src="{{ asset('glosario/regla.jpg') }}" alt="Nivel o Altura Limnimétrica" loading="lazy"><span class="zoom-badge" aria-hidden="true"><i class="fas fa-plus"></i></span></a>
                     <div class="gl-body">
                        <h6 class="gl-term">Nivel o Altura Limnimétrica</h6>
                        <p class="gl-def">Se refiere a la medida de la altura de la superficie del agua en un cuerpo hídrico, como ríos, lagos, embalses o canales. Esta medición se realiza utilizando escalas limnimétricas, que son dispositivos graduados instalados verticalmente o en taludes para proporcionar una lectura visual directa del nivel del agua.</p>
                     </div>
                  </article>
                  <article class="gl-item">
                     <a class="gl-thumb ep-img" data-fancybox="gallery" data-caption="Desviación estándar" href="{{ asset('glosario/sd.jpg') }}" aria-label="Ampliar imagen: Desviación estándar"><img src="{{ asset('glosario/sd.jpg') }}" alt="Desviación estándar" loading="lazy"><span class="zoom-badge" aria-hidden="true"><i class="fas fa-plus"></i></span></a>
                     <div class="gl-body">
                        <h6 class="gl-term">Desviación estándar</h6>
                        <p class="gl-def">La desviación estándar es una medida que nos indica cuánto varían los valores de un conjunto de datos con respecto a su promedio. Si la desviación estándar es baja, significa que los datos están muy próximos entre sí y al valor promedio, mientras que una desviación alta indica que los valores están más dispersos.</p>
                     </div>
                  </article>
                  <article class="gl-item">
                     <a class="gl-thumb ep-img" data-fancybox="gallery" data-caption="DGA-1" href="{{ asset('glosario/dga-1.png') }}" aria-label="Ampliar imagen: DGA-1"><img src="{{ asset('glosario/dga-1.png') }}" alt="Monitoreo DGA" loading="lazy"><span class="zoom-badge" aria-hidden="true"><i class="fas fa-plus"></i></span></a>
                     <div class="gl-body">
                        <h6 class="gl-term">Monitoreo DGA</h6>
                        <p class="gl-def">La Dirección General de Aguas (DGA) de Chile gestiona una amplia red hidrométrica nacional que entrega informacion relevante para la gestion del recurso hídrico esta es crucial para la toma de decisiones en ámbitos como la agricultura, la gestión ambiental y la prevención de desastres naturales.</p>
                     </div>
                  </article>
               </section>
            </div>
          </div>
        </div>
      </div>  
      <!-- Footer -->
       @include('layouts.partials.footer')
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.20.0/dist/axios.min.js" integrity="sha384-mIJWc6qj9ALoBF2yHkNOf0fbGel66+X3UkhYw57p36/PaTyDpO9pvnxrVZ7ZGJfi" crossorigin="anonymous"></script>
    <script src="{{ asset('js/ep-nav.js') }}?v={{ filemtime(public_path('js/ep-nav.js')) }}"></script>
    <script src="{{ asset('js/visita.js') }}?v={{ filemtime(public_path('js/visita.js')) }}"></script>
    <script src="{{ asset('js/ep-img.js') }}?v={{ filemtime(public_path('js/ep-img.js')) }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.31/dist/fancybox.umd.js" integrity="sha384-mfITPNdkUxVtTV4zhg4SDrxOL/aZBoz/p9adHFmaCoLDa43yghU46m5oEkJzLsav" crossorigin="anonymous"></script>
  </body>
</html>