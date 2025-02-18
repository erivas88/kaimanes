

// Variables globales
const apiKey = "v1.public.eyJqdGkiOiJiMDYzNjcwYi04YTMzLTRlODItOTRmMy0zMGU0NjE2NjJjYTEifU1X7ToMqTfYhAcEqJM3ahuEmtKehaX3Xv56T5ztkXJRk4zwQVDZqigL7R2D-3iFU0cYOM1HOzMKrgD93sOH1OUYdLZrFgFmZlMYTAhxNmTplAremq4Zs8Y6XAOHJ37fI1ESshXQCkLdLBTSEekKIX8KL6x_N4Yz28PFTKNTWlW1xdedy5MyBhqt38qFKDH4XTAModJNKlPIO-x5cbYIhX9GpWd8somfT1fmdMlTIxDme7OmKiu1n8pO3SDflXXuxu1Yu_hULiWGdhaw_D_yp2FuiqQ_416bHiYgT-OYZwsgz6CiRwoRWcAuGN2Ozkshin4XwrekYN078KzrsUgqg0E.ZWU0ZWIzMTktMWRhNi00Mzg0LTllMzYtNzlmMDU3MjRmYTkx";
const region = "us-east-1";
const mapStyles = {
   
    "Standard:Light": `https://maps.geo.${region}.amazonaws.com/v2/styles/Hybrid/descriptor?key=${apiKey}`,
    "Satellite": `https://maps.geo.${region}.amazonaws.com/v2/styles/Satellite/descriptor?key=${apiKey}`
};

let markerGroup = []; // Almacena los marcadores actuales para poder eliminarlos
let map; // Variable global para el mapa


// Inicializar el mapa dinámicamente
(async function () {
    const center = await fetchCenter();
    map = new maplibregl.Map({
        container: 'map-container',
        style: mapStyles['Satellite'],
        center: [center.longitud, center.latitud],
        zoom: 12,
        attributionControl: false // Deshabilitar los créditos
    });

    // Agregar controles de navegación
    map.addControl(new maplibregl.NavigationControl(), 'top-left');
    map.addControl(new maplibregl.FullscreenControl() ,'top-left');
    map.addControl(new maplibregl.ScaleControl({ unit: 'metric' }),'bottom-left');
    /*map.addControl(new maplibregl.GeolocateControl({
        positionOptions: { enableHighAccuracy: true },
        trackUserLocation: true
    }),'bottom-left');*/

    // Cambiar estilos de mapa
    document.querySelectorAll('.layer-control input[name="map-style"]').forEach(input => {
        input.addEventListener('change', (e) => {
            const selectedStyle = e.target.value;
            map.setStyle(mapStyles[selectedStyle]);
        });
    });
   

})();



// Función para inicializar el mapa si no está definido
async function initializeMap() {
    const center = await fetchCenter();
    
    return new Promise((resolve) => {
        map = new maplibregl.Map({
            container: 'map-container',
            style: mapStyles['Satellite'],
            center: [center.longitud, center.latitud],
            zoom: 12,
            attributionControl: false
        });

        map.addControl(new maplibregl.NavigationControl(), 'top-left');
        map.addControl(new maplibregl.FullscreenControl(), 'top-left');
        map.addControl(new maplibregl.ScaleControl({ unit: 'metric' }), 'bottom-left');

        map.on('load', () => {
            console.log("Mapa inicializado correctamente.");
            resolve(); // Indicar que la inicialización está completa
        });
    });
}



function getSectorFromURL() {
    const path = window.location.pathname; // Obtiene la parte de la URL después del dominio
    const segments = path.split('/'); // Divide la URL en partes

    // Buscar si "sector" está en la URL y obtener el valor
    const sectorIndex = segments.indexOf("sector");
    if (sectorIndex !== -1 && segments[sectorIndex + 1]) {
        return segments[sectorIndex + 1]; // Devuelve el número del sector
    }

    return null; // Si no encuentra "sector", devuelve null
}
const sector = getSectorFromURL();
console.log("Sector encontrado:", sector);

// Solo ejecutar `loadMarkersForSector(sector)` si `sector` tiene un valor válido
if (sector) {
    var sector_route = loadMarkersForSector(sector);
} else {
    console.log("No se encontró sector en la URL. No se cargarán marcadores.");
}





async function loadMarkersForSector(sector) {
    try {
        console.log(`Cargando marcadores para el sector: ${sector}`);

        // Esperar a que `fetchMarkersBySector(sector)` devuelva los datos
        const sector_route = await fetchMarkersBySector(sector);
        console.log("Marcadores obtenidos:", sector_route);

        // Verificar si `map` está inicializado antes de agregar marcadores
        if (!map) {
            console.warn("El mapa no está inicializado. Inicializando ahora...");
            await initializeMap();
        }

        // Ahora que `map` está listo, agregar los marcadores
        addMarkersToMap(map,sector_route);
    } catch (error) {
        console.error("Error al cargar los marcadores del sector:", error);
    }
}


// Obtener coordenadas dinámicas
async function fetchCenter() {
    try {
        const response = await axios.get(`http://10.0.0.75/api_caimanes/public/api/location`);
        if (response.data && response.data.latitud && response.data.longitud) {
            return response.data;
        } else {
            console.error('Respuesta no válida o sin datos:', response);
            return { latitud: -33.4569, longitud: -70.6483 }; // Coordenadas por defecto
        }
    } catch (error) {
        console.error('Error al obtener los datos del centro:', error);
        return { latitud: -33.4569, longitud: -70.6483 }; // Coordenadas por defecto
    }
}

// Obtener marcadores desde el backend
async function fetchMarkersBySector(id_sector) {
    try {
        const response = await axios.get(`http://10.0.0.75/api_caimanes/public/api/location/sector/sector_publico/${id_sector}`);
        if (response.data && Array.isArray(response.data)) {
            return response.data;
        } else {
            console.error('La respuesta no es válida o no contiene datos:', response);
            return [];
        }
    } catch (error) {
        console.error('Error al obtener los marcadores:', error);
        return [];
    }
}


function addMarkersToMap(map, markers) {
    // Limpiar marcadores existentes

    const totalMarkers = markers.length;
    const sectores = [...new Set(markers.map(marker => marker.sector))]; // Sectores únicos

    if (totalMarkers > 0) {
        console.log(`Total de marcadores: ${totalMarkers}`);
        console.log(`Sectores encontrados: ${sectores.join(', ')}`);
        $('#sectorView').html(`${sectores.join(', ')} (${totalMarkers} estaciones)`);
    } else {
        console.warn("No hay marcadores disponibles, no se mostrará información.");
        $('#sectorView').html(''); // Limpiar si no hay marcadores
    }


    
    markerGroup.forEach(marker => marker.remove());
    markerGroup = []; // Vaciar el grupo

    const bounds = new maplibregl.LngLatBounds();

    // Definir íconos personalizados
    const icons = {
        agua_subterranea: '/api_caimanes/public/images/icons/markers/pin-agua-subterranea.png',
        aguas_superficiales: '/api_caimanes/public/images/icons/markers/pin-agua-superficial.png',
        reservorios: '/api_caimanes/public/images/icons/markers/pin-reservorio.svg'
    };
    

    console.log(markers)
    markers.forEach(marker => {
        if (marker.latitud && marker.longitud) {
            // Determinar el ícono a usar según el tipo de marcador
            let iconUrl;
            //iconUrl = icons.agua_subterranea;
            console.log(marker.tipo);
            switch (marker.tipo.toString()) {

                case '1':
                    iconUrl = icons.agua_subterranea;
                    break;
                case '2':
                    iconUrl = icons.aguas_superficiales;
                    break;
                case '3':
                    iconUrl = icons.reservorios;
                    break;
                default:
                    console.error('Tipo de marcador desconocido:', marker.tipo);
                    return;
            }

            // Crear un elemento HTML personalizado para el marcador
            const customIcon = document.createElement('div');
            customIcon.className = 'custom-marker'; // Clase CSS para personalización
            customIcon.style.backgroundImage = `url(${iconUrl})`;
            customIcon.style.backgroundSize = 'cover';
            customIcon.style.width = '34px'; // Ancho del ícono
            customIcon.style.height = '40px'; // Altura del ícono
            customIcon.style.imageRendering = 'auto'; // Optimiza para imágenes normales
          

            

            // Crear el marcador con el ícono personalizado
            const newMarker = new maplibregl.Marker({ element: customIcon })
                .setLngLat([marker.longitud, marker.latitud])
                .setPopup(
        new maplibregl.Popup({
            offset: {
                'top': [0, -30], // Desplaza el popup hacia arriba 30 píxeles
                'bottom': [0, -20],
                'left': [-30, 0],
                'right': [30, 0]
            }
        }).setHTML(`
            <div style="font-family: 'Poppins', serif; font-size: 14px; text-align: center;">
                <b>${marker.nombre}</b>
                <br>
              
                <button 
                    class="ver-estacion-btn" 
                    onclick="explorarEstacion('${marker.id}')"
                >
                    <i class="fas fa-map-marker-alt"></i> Ver estación
                </button>
            </div>
        `)
    )
                .addTo(map);

            markerGroup.push(newMarker); // Almacenar el marcador
            bounds.extend([marker.longitud, marker.latitud]);

            // Crear una etiqueta debajo del marcador
            const label = document.createElement('div');
            label.className = 'marker-label'; // Clase CSS personalizada para la etiqueta
            label.innerHTML = `<span>${marker.map_name || marker.nombre}</span>`;
            label.style.position = 'absolute';
            label.style.top = '40px'; // Posicionar debajo del marcador
            label.style.left = '50%';
            label.style.right = '50%';
            label.style.transform = 'translateX(-50%)';
            label.style.fontSize = '12px';
            label.style.color = 'black';

            // Agregar la etiqueta al marcador
            customIcon.appendChild(label);
        }
    });

    if (!bounds.isEmpty()) {
        map.fitBounds(bounds, { padding: 50 });
    }
}


// Crear marcadores y ajustar la vista
function addMarkersToMap_old(map, markers) {
    // Limpiar marcadores existentes
    markerGroup.forEach(marker => marker.remove());
    markerGroup = []; // Vaciar el grupo

    const bounds = new maplibregl.LngLatBounds();

    markers.forEach(marker => {
        if (marker.latitud && marker.longitud) {
            const newMarker = new maplibregl.Marker({ color: marker.color || 'white' })
                .setLngLat([marker.longitud, marker.latitud])
                .setPopup(
                    new maplibregl.Popup().setHTML(`
                        <div>
                            <strong>${marker.nombre}</strong><br>
                            <button onclick="explorarEstacion('${marker.id}')">Ver estación</button>
                        </div>
                    `)
                )
                .addTo(map);

            markerGroup.push(newMarker); // Almacenar el marcador
            bounds.extend([marker.longitud, marker.latitud]);
        }
    });

    if (!bounds.isEmpty()) {
        map.fitBounds(bounds, { padding: 50 });


        const bbox = bounds.toArray(); // [sw, ne]
        const polygon = [
            [
                [bbox[0][0] - 0.05, bbox[0][1] - 0.05],
                [bbox[1][0] + 0.05, bbox[0][1] - 0.05],
                [bbox[1][0] + 0.05, bbox[1][1] + 0.05],
                [bbox[0][0] - 0.05, bbox[1][1] + 0.05],
                [bbox[0][0] - 0.05, bbox[0][1] - 0.05]
            ]
        ];

        map.addSource('border-source', {
            type: 'geojson',
            data: {
                type: 'Feature',
                geometry: {
                    type: 'Polygon',
                    coordinates: polygon
                }
            }
        });

        map.addLayer({
            id: 'border-layer',
            type: 'line',
            source: 'border-source',
            layout: {},
            paint: {
                'line-color': '#FF0000',
                'line-width': 2,
                'line-dasharray': [2, 2]
            }
        });
    }
}






// Manejar eventos de clic para actualizar marcadores por sector
document.addEventListener('DOMContentLoaded', () => {
    const sideElement = document.getElementById('side');
    sideElement.addEventListener('click', async (event) => {
        const headerElement = event.target.closest('.header');
        if (headerElement) {
            const idSector = headerElement.getAttribute('id-sector');
            console.log('ID Sector:', idSector);

            // Obtener y agregar marcadores al mapa
            const markers = await fetchMarkersBySector(idSector);
            console.log(markers);
            addMarkersToMap(map, markers); // Usar la variable global `map`
        }
    });
});


$(document).on("click", ".accordion-button", async function () {
    let idSector = $(this).attr("id-sector"); // Obtener el valor del atributo id-sector
    console.log("ID Sector:", idSector);
    const markers = await fetchMarkersBySector(idSector);
    console.log(markers);
    addMarkersToMap(map, markers); // Usar la variable global `map`
});



window.explorarEstacion = function(id) {
    console.log(`Explorar estación con ID: ${id}`);
    

    window.location.href = window.location.origin + "/api_caimanes/public/estacion-publica/" + id;

};

