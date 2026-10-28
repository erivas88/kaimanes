{{-- Precarga de la foto de fondo de styles.css (versión según el ancho, igual que la media query del CSS) --}}
<link rel="preload" as="image" type="image/webp" href="{{ asset('images/caimanes_city_1920.webp') }}" media="(min-width: 769px)" fetchpriority="high" />
<link rel="preload" as="image" type="image/webp" href="{{ asset('images/caimanes_city_1280.webp') }}" media="(max-width: 768px)" fetchpriority="high" />
