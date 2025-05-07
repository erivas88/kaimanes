

let apiKey;
const region = "us-east-1";
let mapStyles;
let markerGroup = [];
let map;

// Obtener el token desde Laravel
fetch('http://caimanes.katta.cl/api/map-token')
    .then(response => response.json())
    .then(data => {
        apiKey = data.apiKey;

        mapStyles = {
            
            "Standard:Light": `https://maps.geo.${region}.amazonaws.com/v2/styles/Hybrid/descriptor?key=${apiKey}`,            
            "Satellite": `https://maps.geo.${region}.amazonaws.com/v2/styles/Satellite/descriptor?key=${apiKey}`,           
            "Street Map": `https://maps.geo.${region}.amazonaws.com/v2/styles/Standard/descriptor?key=${apiKey}&color-scheme=Light&political-view=AR`,

            


        };

        initAll(); // Llama a la inicialización del mapa cuando el token esté listo
    })
    .catch(error => console.error('Error al obtener el token:', error));


    const sector = getSectorFromURL();
    console.log("Sector encontrado:", sector);

async function initAll() {
    try {
        console.log('Inicializando mapa y cargando marcadores...');

        // Obtener el centro del mapa
        const center = await fetchCenter();

        // Crear el mapa
        map = new maplibregl.Map({
            container: 'map-container',
            style: mapStyles['Standard:Light'],
            center: [center.longitud, center.latitud],
            zoom: 12,
            attributionControl: false // Deshabilitar los créditos
        });

        // Agregar controles de navegación
        map.addControl(new maplibregl.NavigationControl(), 'top-left');
        map.addControl(new maplibregl.FullscreenControl(), 'top-left');
        map.addControl(new maplibregl.ScaleControl({ unit: 'metric' }), 'bottom-right');

        // Escuchar cambios de estilo de mapa
        document.querySelectorAll('.layer-control input[name="map-style"]').forEach(input => {
            input.addEventListener('change', (e) => {
                const selectedStyle = e.target.value;
                map.setStyle(mapStyles[selectedStyle]);
            });
        });

        // Esperar a que el mapa esté completamente cargado
        map.on('load', async () => {
            console.log("Mapa inicializado correctamente.");
        
            try {
                // Obtener los marcadores generales
                const sectorMarkers = await fetchMarkersallSector();
                
                // Obtener los marcadores del sector específico (si existe)
                const MarkerSectors = sector ? await fetchMarkersBySector(sector) : null;
        
                // Determinar qué conjunto de marcadores agregar
                addMarkersToMap(map, MarkerSectors && MarkerSectors.length > 0 ? MarkerSectors : sectorMarkers);
            } catch (error) {
                console.error("Error al cargar los marcadores:", error);
            }
        });
        

    } catch (error) {
        console.error("Error al inicializar el mapa:", error);
    }
};



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
        map.addControl(new maplibregl.ScaleControl({ unit: 'metric' }), 'bottom-right');

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
        const response = await axios.get(`http://caimanes.katta.cl/api/location`);
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
        const response = await axios.get(`http://caimanes.katta.cl/api/location/sector/sector_publico/${id_sector}`);
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


async function fetchMarkersallSector(id_sector) {
    try {
        const response = await axios.get(`http://caimanes.katta.cl/api/estaciones`);
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

    if (totalMarkers > 25) {
        console.warn("no se mostrará información.");
        $('#sectorView').html(''); // Limpiar si hay más de 25 marcadores
    } else if (totalMarkers > 0) {
        //console.log(`Total de marcadores: ${totalMarkers}`);
        //console.log(`Sectores encontrados: ${sectores.join(', ')}`);
        $('#sectorView').html(`${sectores.join(', ')} (${totalMarkers} estaciones)`);
    } else {
        console.warn("No hay marcadores disponibles, no se mostrará información.");
        $('#sectorView').html(''); // Limpiar si no hay marcadores
    }
    

    
    markerGroup.forEach(marker => marker.remove());
    markerGroup = []; // Vaciar el grupo

    const bounds = new maplibregl.LngLatBounds();

    // Definir íconos personalizados
    const icons_bad = {
        agua_subterranea: '/api_caimanes/public/images/icons/markers/pin-agua-subterranea.png',
        aguas_superficiales: '/api_caimanes/public/images/icons/markers/pin-agua-superficial.png',
        reservorios: '/api_caimanes/public/images/icons/markers/pin-reservorio.svg'
    };


   const icons = {
        agua_subterranea: '/images/icons/markers/pin-agua-subterranea.png',
        aguas_superficiales: '/images/icons/markers/pin-agua-superficial.png',
        reservorios: '/images/icons/markers/pin-reservorio.svg'
    };

    

   
    markers.forEach(marker => {
        if (marker.latitud && marker.longitud) {
            // Determinar el ícono a usar según el tipo de marcador
            let iconUrl;
            //iconUrl = icons.agua_subterranea;
            //console.log(marker.tipo);
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
            /*const customIcon = document.createElement('div');
            customIcon.className = 'custom-marker'; // Clase CSS para personalización
            customIcon.style.backgroundImage = `url(${iconUrl})`;
            customIcon.style.backgroundSize = 'cover';
            customIcon.style.width = '20px'; // Ancho del ícono
            customIcon.style.height = '24px'; // Altura del ícono
            customIcon.style.imageRendering = 'auto'; // Optimiza para imágenes normales
            customIcon.style.imageRendering = 'auto'; // Intenta mantener la calidad original
            customIcon.style.backgroundSize = 'contain'; // Evita estiramientos
            customIcon.style.backgroundRepeat = 'no-repeat'; // Evita repeticiones no deseadas
            customIcon.style.backgroundPosition = 'center'; // Asegura una buena alineación
            customIcon.style.filter = 'drop-shadow(0px 0px 1px rgba(0, 0, 0, 0.5))'; // Agrega un ligero suavizado*/



            const customIcon = document.createElement('div');
            customIcon.className = 'custom-marker'; // Clase CSS para personalización
            customIcon.style.backgroundImage = `url(${iconUrl})`;
            customIcon.style.backgroundSize = 'contain'; // Evita estiramientos
            customIcon.style.backgroundRepeat = 'no-repeat'; // Evita repeticiones no deseadas
            customIcon.style.backgroundPosition = 'center'; // Asegura una buena alineación
            customIcon.style.width = '22px'; // Ancho del ícono
            customIcon.style.height = '26px'; // Altura del ícono
            customIcon.style.imageRendering = 'auto'; // Mantiene la calidad original
            customIcon.style.filter = 'drop-shadow(0px 0px 1px rgba(0, 0, 0, 0.5))'; // Suavizado

// Aplicar filtro para cambiar el color del SVG a rojo





          

            

            // Crear el marcador con el ícono personalizado
            const newMarker = new maplibregl.Marker({ element: customIcon })
                .setLngLat([marker.longitud, marker.latitud])
                .setPopup(
        /*new maplibregl.Popup({
            offset: {
                'top': [0, -30], // Desplaza el popup hacia arriba 30 píxeles
                'bottom': [0, -20],
                'left': [-30, 0],
                'right': [30, 0]
            }
        })*/
            new maplibregl.Popup({
                offset: {
                    'top': [0, -20], // Reducir la distancia para acercarlo al marcador
                    'bottom': [0, -15],
                    'left': [-20, 0],
                    'right': [20, 0]
                }
            }).setHTML(`<div style="
    font-family: 'Poppins', sans-serif; 
    font-size: 12px; 
    text-align: center; 
    padding: 5px;
">
    <b style="display: block; margin-bottom: 6px; color: #333;">${marker.nombre}</b>
    <div style="display: flex; justify-content: center;">
        <button 
            class="ver-estacion-btn"
            onclick="explorarEstacion('${marker.id}')"
            style="
                background: linear-gradient(135deg, #00768e, #00a6b8);
                color: white;
                border: none;
                padding: 8px 14px;
                font-size: 12px;
                font-weight: 600;
                border-radius: 20px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                transition: all 0.3s ease;
                box-shadow: 0px 4px 8px rgba(0, 118, 142, 0.3);
                outline: none;
                text-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
            "
            aria-label="Explorar estación ${marker.nombre}"
            tabindex="0"
        >
            <i class="fas fa-map-marker-alt"></i> Ver estación
        </button>
    </div>
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
            label.style.top = '23px'; // Posicionar debajo del marcador
            label.style.left = '50%';
            label.style.right = '50%';
            label.style.transform = 'translateX(-50%,0)';
            label.style.fontSize = '12px';
            label.style.color = 'black';
            //label.style.maxWidth = '120px'; // Limita el ancho para dividir etiquetas largas en múltiples líneas
            label.style.wordWrap = 'break-word'; // Permite dividir palabras largas
            

            label.style.lineHeight = '10px'; // Reduce el espacio entre líneas
            label.style.padding = '2px 4px'; // Ajusta el espacio interno para un diseño más compacto
            


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









$(document).on("click", ".accordion-button", async function () {
    let idSector = $(this).attr("id-sector"); // Obtener el valor del atributo id-sector
    console.log("ID Sector:", idSector);
    const markers = await fetchMarkersBySector(idSector);
    console.log(markers);
    addMarkersToMap(map, markers); // Usar la variable global `map`
});



window.explorarEstacion = function(id) {
    console.log(`Explorar estación con ID: ${id}`);
    

    //window.location.href = window.location.origin + "/api_caimanes/public/estacion-publica/" + id;
    window.location.href = window.location.origin + "/estacion-publica/" + id;


};

