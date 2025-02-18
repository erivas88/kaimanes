async function fetchAndRenderSideMenu() {
    const sideElement = document.getElementById('side');
    
    // Mostrar el indicador de carga
    sideElement.innerHTML = '<div id="loading" style="text-align: center; margin: 20px;"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>';

    try {
        // Realizar la petición GET
        const response = await axios.get('http://10.0.0.75/api_caimanes/public/api/left');
        console.log(response);

        // Validar la respuesta
        if (response.data && response.data.dropdown) {
            // Renderizar el contenido del dropdown
            sideElement.innerHTML = response.data.dropdown;

            // Configurar eventos de clic para los headers
            //configureDropdownToggles();
        } else {
            console.error('La respuesta no contiene el campo "dropdown".');
            sideElement.innerHTML = '<p>Error: No se pudo cargar el contenido.</p>';
        }
    } catch (error) {
        console.error('Error al realizar la petición:', error);
        sideElement.innerHTML = '<p>Error: No se pudo cargar el contenido.</p>';
    }
}


document.addEventListener('DOMContentLoaded', fetchAndRenderSideMenu);