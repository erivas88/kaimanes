
@section('header')
<!-- Header -->
      
        <div class="header d-flex justify-content-between align-items-center px-3" style="background-color: white; padding: 10px 0;">
            <a href="{{ url('/') }}">
            <img   src="{{ asset('images/image.png') }}"
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
@endsection
