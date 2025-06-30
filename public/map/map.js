let apiKey;
const region = "us-east-1";
let mapStyles;
let markerGroup = [];
let map;

// Obtener BASE_URL desde la etiqueta meta
const BASE_URL = document.querySelector('meta[name="base-url"]').getAttribute('content');

// Obtener el token desde Laravel
fetch(`${BASE_URL}api/map-token`)
    .then(response => response.json())
    .then(data => {
        apiKey = data.apiKey;

        mapStyles = {
            "Standard:Light": `https://maps.geo.${region}.amazonaws.com/v2/styles/Hybrid/descriptor?key=${apiKey}`,
            "Satellite": `https://maps.geo.${region}.amazonaws.com/v2/styles/Satellite/descriptor?key=${apiKey}`,
            "Street Map": `https://maps.geo.${region}.amazonaws.com/v2/styles/Standard/descriptor?key=${apiKey}&color-scheme=Light&political-view=AR`,
        };

        initAll();
    })
    .catch(error => console.error('Error al obtener el token:', error));

const sector = getSectorFromURL();

async function initAll() {
    try {
        const center = await fetchCenter();

        map = new maplibregl.Map({
            container: 'map-container',
            style: mapStyles['Standard:Light'],
            center: [center.longitud, center.latitud],
            zoom: 12,
            attributionControl: false
        });

        map.addControl(new maplibregl.NavigationControl(), 'top-left');
        map.addControl(new maplibregl.FullscreenControl(), 'top-left');
        map.addControl(new maplibregl.ScaleControl({ unit: 'metric' }), 'bottom-right');

        document.querySelectorAll('.layer-control input[name="map-style"]').forEach(input => {
            input.addEventListener('change', (e) => {
                const selectedStyle = e.target.value;
                map.setStyle(mapStyles[selectedStyle]);
            });
        });

        map.on('load', async () => {
            try {
                const sectorMarkers = await fetchMarkersallSector();
                const MarkerSectors = sector ? await fetchMarkersBySector(sector) : null;
                addMarkersToMap(map, MarkerSectors && MarkerSectors.length > 0 ? MarkerSectors : sectorMarkers);
            } catch (error) {
                console.error("Error al cargar los marcadores:", error);
            }
        });
    } catch (error) {
        console.error("Error al inicializar el mapa:", error);
    }
}

function getSectorFromURL() {
    const path = window.location.pathname;
    const segments = path.split('/');
    const sectorIndex = segments.indexOf("sector");
    if (sectorIndex !== -1 && segments[sectorIndex + 1]) {
        return segments[sectorIndex + 1];
    }
    return null;
}

if (sector) {
    var sector_route = loadMarkersForSector(sector);
}

async function loadMarkersForSector(sector) {
    try {
        const sector_route = await fetchMarkersBySector(sector);
        if (!map) {
            await initializeMap();
        }
        addMarkersToMap(map, sector_route);
    } catch (error) {
        console.error("Error al cargar los marcadores del sector:", error);
    }
}

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
            resolve();
        });
    });
}

async function fetchCenter() {
    try {
        const response = await axios.get(`${BASE_URL}api/location`);
        if (response.data && response.data.latitud && response.data.longitud) {
            return response.data;
        } else {
            return { latitud: -33.4569, longitud: -70.6483 };
        }
    } catch (error) {
        return { latitud: -33.4569, longitud: -70.6483 };
    }
}

async function fetchMarkersBySector(id_sector) {
    try {
        const response = await axios.get(`${BASE_URL}api/location/sector/sector_publico/${id_sector}`);
        return Array.isArray(response.data) ? response.data : [];
    } catch (error) {
        return [];
    }
}

async function fetchMarkersallSector() {
    try {
        const response = await axios.get(`${BASE_URL}api/estaciones`);
        return Array.isArray(response.data) ? response.data : [];
    } catch (error) {
        return [];
    }
}

function addMarkersToMap(map, markers) {
    const totalMarkers = markers.length;
    const sectores = [...new Set(markers.map(marker => marker.sector))];

    if (totalMarkers > 25) {
        $('#sectorView').html('');
    } else if (totalMarkers > 0) {
        $('#sectorView').html(`${sectores.join(', ')} (${totalMarkers} estaciones)`);
    } else {
        $('#sectorView').html('');
    }

    markerGroup.forEach(marker => marker.remove());
    markerGroup = [];

    const bounds = new maplibregl.LngLatBounds();

    const icons = {
        agua_subterranea: `${BASE_URL}images/icons/markers/pin-agua-subterranea.png`,
        aguas_superficiales: `${BASE_URL}images/icons/markers/pin-agua-superficial.png`,
        reservorios: `${BASE_URL}images/icons/markers/pin-reservorio.svg`
    };

    markers.forEach(marker => {
        if (marker.latitud && marker.longitud) {
            let iconUrl;
            switch (marker.tipo.toString()) {
                case '1': iconUrl = icons.agua_subterranea; break;
                case '2': iconUrl = icons.aguas_superficiales; break;
                case '3': iconUrl = icons.reservorios; break;
                default: return;
            }

            const customIcon = document.createElement('div');
            customIcon.className = 'custom-marker';
            customIcon.style.backgroundImage = `url(${iconUrl})`;
            customIcon.style.backgroundSize = 'contain';
            customIcon.style.backgroundRepeat = 'no-repeat';
            customIcon.style.backgroundPosition = 'center';
            customIcon.style.width = '22px';
            customIcon.style.height = '26px';
            customIcon.style.imageRendering = 'auto';
            customIcon.style.filter = 'drop-shadow(0px 0px 1px rgba(0, 0, 0, 0.5))';

            const newMarker = new maplibregl.Marker({ element: customIcon })
                .setLngLat([marker.longitud, marker.latitud])
                .setPopup(new maplibregl.Popup({
                    offset: { top: [0, -20], bottom: [0, -15], left: [-20, 0], right: [20, 0] }
                }).setHTML(`<div style="font-family: 'Poppins', sans-serif; font-size: 12px; text-align: center; padding: 5px;">
                    <b style="display: block; margin-bottom: 6px; color: #333;">${marker.nombre}</b>
                    <div style="display: flex; justify-content: center;">
                        <button class="ver-estacion-btn" onclick="explorarEstacion('${marker.id}')" style="background: linear-gradient(135deg, #00768e, #00a6b8); color: white; border: none; padding: 8px 14px; font-size: 12px; font-weight: 600; border-radius: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0px 4px 8px rgba(0, 118, 142, 0.3); outline: none; text-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);">
                            <i class="fas fa-map-marker-alt"></i> Ver estación
                        </button>
                    </div>
                </div>`))
                .addTo(map);

            markerGroup.push(newMarker);
            bounds.extend([marker.longitud, marker.latitud]);

            const label = document.createElement('div');
            label.className = 'marker-label';
            label.innerHTML = `<span>${marker.map_name || marker.nombre}</span>`;
            label.style.position = 'absolute';
            label.style.top = '23px';
            label.style.left = '50%';
            label.style.right = '50%';
            label.style.transform = 'translateX(-50%,0)';
            label.style.fontSize = '14px';
            label.style.color = 'black';
            label.style.wordWrap = 'break-word';
            label.style.lineHeight = '10px';
            label.style.padding = '2px 4px';

            customIcon.appendChild(label);
        }
    });

    if (!bounds.isEmpty()) {
        map.fitBounds(bounds, { padding: 50 });
    }
}

$(document).on("click", ".accordion-button", async function () {
    let idSector = $(this).attr("id-sector");
    const markers = await fetchMarkersBySector(idSector);
    addMarkersToMap(map, markers);
});

window.explorarEstacion = function(id) {
    window.location.href = `${window.location.origin}/estacion-publica/${id}`;
};
