<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $estacion->nombre }}</title>
        @include('layouts.partials.bg-preload')
        <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon" />
        <link href="https://cdn.jsdelivr.net/npm/maplibre-gl@4.7.1/dist/maplibre-gl.min.css" integrity="sha384-XPt8o1QuQUrfvyG8MY6VYuFZCJ147mPoGUtgjzC6/j9fw9/scoqo1v96sDhlvnFo" crossorigin="anonymous" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" integrity="sha384-OXVF05DQEe311p6ohU11NwlnX08FzMCsyoXzGOaL+83dKAb3qS17yZJxESl8YrJQ" crossorigin="anonymous" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" rel="stylesheet" />
        <link src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css" />
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ filemtime(public_path('css/styles.css')) }}" rel="stylesheet" />
        {{-- miniatura WebP cacheada en el servidor; el original (S3) solo se pide al ampliar con Fancybox --}}
        @php($imgThumb = url('estacion-imagen/' . $estacion->estacion_id) . '?v=' . substr(md5($estacion->link_backup), 0, 8))
        <link rel="preload" as="image" type="image/webp" href="{{ $imgThumb }}" fetchpriority="high" />
        <link rel="stylesheet" href="{{ asset('css/estacion-publica.css') }}?v={{ filemtime(public_path('css/estacion-publica.css')) }}" />
        <script src="https://kit.fontawesome.com/e5291bc371.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.31/dist/fancybox.css" integrity="sha384-kPPlFUTv5pDfb8JmcIDGDPhxzM4ZG0aIQ5/7vANkrD1Z7GR9e3k459LTa7fDCOX/" crossorigin="anonymous" />
        @include('layouts.partials.app')
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
            <nav class="site-band d-flex align-items-center px-4 py-3 justify-content-between">
                <a href="{{ url('/') }}" class="text-white text-decoration-none fw-bold">Sistema de Mediciones en Linea de Aguas - Valle Pupío</a>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/sector/' . $estacion->sector) }}" class="text-white text-decoration-none fw-bold" data-bs-toggle="tooltip" title="Ver en mapa" data-bs-placement="bottom">
                                {{ $estacion->descripcion }}
                            </a>
                        </li>
                    </ol>
                </nav>
            </nav>
            <div class="container-fluid mt-4">
                <div class="row" style="padding-top: 20px;">
                    <div class="col-md-3">
                        <div class="panel ep-nav">
                            <div style="background-color: #f5f5f5; padding: 15px 10px 0 10px;">
                                <h5 class="fw-bold title_pop">Seleccionar</h5>
                                <hr style="width: 100%;" />
                            </div>
                            <div style="background-color: #f5f5f5;">
                                {!! $side !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="dash ds-surface">
                            <section class="dash-hero">
                                <div class="hero-stage">
                                    <a data-fancybox="gallery" href="{{ asset($estacion->link_backup) }} " data-caption="{{ $estacion->nombre }}" class="station-photo hero-cover ep-img" aria-label="Ampliar imagen: {{ $estacion->nombre }}">
                                        <img id="imgDevice" src="{{ $imgThumb }}" alt="{{ $estacion->nombre }}" class="img_device" fetchpriority="high" decoding="async" />
                                        <span class="station-photo-badge zoom-badge" aria-hidden="true"><i class="fas fa-plus"></i></span>
                                    </a>
                                    <div class="hero-content">
                                        <div class="dash-title-row">
                                            <span class="ds-icon ds-icon--md"><span class="{{ $estacion->icon_titlte }}">&nbsp;</span></span>
                                            <h5 class="fw-bold title_pop dash-title"><span id="primary_title">{{ $estacion->nombre }}</span></h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="station-meta">
                                    <div class="station-meta-item">
                                        <span class="meta-ico"><i class="fas fa-water"></i></span>
                                        <div class="meta-text">
                                            <div class="ds-label">Cuenca</div>
                                            <div class="ds-value" id="cuencaStation">{{ $estacion->cuenca }}</div>
                                        </div>
                                    </div>
                                    <div class="station-meta-item">
                                        <span class="meta-ico"><i class="fas fa-code-branch"></i></span>
                                        <div class="meta-text">
                                            <div class="ds-label">Subcuenca</div>
                                            <div class="ds-value" id="subcuencaStation">{{ $estacion->subcuenca }}</div>
                                        </div>
                                    </div>
                                    <div class="station-meta-item">
                                        <span class="meta-ico"><i class="fas fa-map-marker-alt"></i></span>
                                        <div class="meta-text">
                                            <div class="ds-label">Región</div>
                                            <div class="ds-value" id="regionStation">{{ $estacion->region }}</div>
                                        </div>
                                    </div>
                                    <div class="station-meta-item">
                                        <span class="meta-ico"><i class="fas fa-crosshairs"></i></span>
                                        <div class="meta-text">
                                            <div class="ds-label">Coordenadas</div>
                                            <div class="ds-value" id="coordenadaStation">{{ $estacion->utm_north }} ; {{ $estacion->utm_east }} ; {{ $estacion->utm_datum }}</div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="dash-toolbar">
                                <div class="row">
                                    <div class="col-lg-6 ds-field">
                                        <label for="dateRangeSelect" class="selectOption">Periodo de tiempo</label>
                                        <select id="dateRangeSelect" style="width: 100%;"> </select>
                                    </div>
                                    <div class="col-lg-6 ds-field">
                                        <label for="typeSelect" class="selectOption">Tipo de parámetro</label>
                                        <select id="typeSelect" style="width: 100%;"> </select>
                                    </div>
                                </div>
                            </section>

                            <section class="dash-chart">
                                <div id="conductivityChart" class="chart-area">
                                    <div id="loadingSpinner" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;">
                                        <div style="border: 4px solid #f3f3f3; border-radius: 50%; border-top: 4px solid #3498db; width: 40px; height: 40px; animation: spin 1s linear infinite;"></div>
                                    </div>
                                </div>
                            </section>

                            <section class="dash-summary">
                                <div class="dash-summary-head">
                                    <h6 class="dash-summary-title">Resumen estadístico</h6>
                                    <span class="chart-updated" id="lastUpdate" hidden><i class="far fa-clock"></i> <span class="chart-updated-text"></span></span>
                                    <span id="plotVar" hidden></span>
                                </div>
                                <div class="stats-container"></div>
                                <div id="notas" class="notas_view"></div>
                            </section>

                            <footer class="dash-note">
                                <i class="fas fa-info-circle"></i>
                                <p>
                                    <strong>Nota</strong> la información de monitoreo disponible está sujeta a las condiciones de conectividad y telecomunicaciones en la zona. La ejecución de mantenciones en los equipos y/o algún
                                    otro evento pueden alterar momentáneamente los registros. Para mayores detalles ver sección <a href="#" class="dash-note-link" onclick="event.preventDefault(); document.getElementById('OpenModal').click();">"Observaciones"</a>.
                                </p>
                            </footer>
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
                        <div class="modal-body">
                            <table class="table" id="tableObservations" style="width: 100%;">
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
                        <div class="modal-body"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios@1.20.0/dist/axios.min.js" integrity="sha384-mIJWc6qj9ALoBF2yHkNOf0fbGel66+X3UkhYw57p36/PaTyDpO9pvnxrVZ7ZGJfi" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" integrity="sha384-d3UHjPdzJkZuk5H3qKYMLRyWLAQBJbby2yr2Q58hXXtAGF8RSNO9jpLDlKKPv5v3" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
        <script src="{{ asset('stock/code/highstock.js') }}"></script>
        <script src="{{ asset('stock/code/modules/data.js') }}"></script>
        <script src="{{ asset('stock/code/modules/export-data.js') }}"></script>
        <script src="{{ asset('stock/code/modules/accessibility.js') }}"></script>
        <script src="{{ asset('stock/code/modules/no-data-to-display.js') }}"></script>
        <script src="{{ asset('lst/lst_op.js') }}"></script>
        <script src="{{ asset('plot/plot.js') }}?v={{ filemtime(public_path('plot/plot.js')) }}"></script>
        <script src="{{ asset('js/ep-nav.js') }}?v={{ filemtime(public_path('js/ep-nav.js')) }}"></script>
        <script src="{{ asset('js/visita.js') }}?v={{ filemtime(public_path('js/visita.js')) }}"></script>
        <script src="{{ asset('js/ep-img.js') }}?v={{ filemtime(public_path('js/ep-img.js')) }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.31/dist/fancybox.umd.js" integrity="sha384-mfITPNdkUxVtTV4zhg4SDrxOL/aZBoz/p9adHFmaCoLDa43yghU46m5oEkJzLsav" crossorigin="anonymous"></script>
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
