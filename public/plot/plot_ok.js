






document.addEventListener('DOMContentLoaded', function () {
    // Selectores
    const typeSelect = $('#typeSelect');
    const dateRangeSelect = $('#dateRangeSelect');
    const imgDevice = $('#imgDevice');

    // Función para mostrar el spinner dentro de una imagen
    function showSpinner(img) {
        const spinner = $('<div class="spinner"></div>');
        img.append(spinner);
    }

    // Función para ocultar el spinner
    function hideSpinner(img) {
        img.find('.spinner').remove();
    }

    // Mostrar un ícono de carga en #codeStation mientras se resuelve la petición
    $('#pointStation').html('<i class="fas fa-spinner fa-spin"></i> &nbsp; Cargando...');
    $('#imgDevice').html('<i class="fas fa-spinner fa-spin"></i> &nbsp; Cargando...');

 

    if (!idDevice) {
        $('#codeStation').html('<div style="text-align: center; color: red;">Error: Parámetro id_device no proporcionado.</div>');
        return;
    }


    $.ajax({
        url: `http://10.0.0.75/api_caimanes/public/api/info_device/${idDevice}`, 
        method: 'GET',        
        dataType: 'json',
        success: function (response) {
            if (response && response.parametros && response.periodos) {
                $('#typeSelect').select2({
                    placeholder: 'Parámetros',
                    allowClear: true,
                    multiple: true,
                    minimumResultsForSearch: Infinity,
                    maximumSelectionLength: 2,
                    data: response.parametros.map(item => ({ id: item.sensor, text: item.tipo })),
                    language: { 
                        noResults: () => "Sin parámetros asociados",
                        maximumSelected: () => "Solo puedes seleccionar 2 parámetros"
                    },
                    escapeMarkup: markup => markup
                }).prop('disabled', false);
    
                $('#dateRangeSelect').select2({
                    placeholder: 'Periodo',
                    allowClear: true,
                    multiple: true,
                    minimumResultsForSearch: Infinity,
                    maximumSelectionLength: 1,
                    data: response.periodos.map(item => ({ id: item.id_periodo, text: item.descripcion })),
                    language: { 
                        noResults: () => "Sin parámetros asociados",
                        maximumSelected: () => "Solo puedes seleccionar 1 periodo"
                    }
                }).prop('disabled', false);
    
                const typeSelectValue = response.parametros.length > 0 ? response.parametros[0].sensor : null;
                const dateRangeSelectValue = response.periodos.length > 0 ? response.periodos[0].id_periodo : null;
    
                console.log('Valor inicial de #typeSelect:', typeSelectValue);
                console.log('Valor inicial de #dateRangeSelect:', dateRangeSelectValue);
                drawChart([typeSelectValue], [dateRangeSelectValue], idDevice);
            } else {
                console.error('Datos incompletos en la respuesta del servidor.');
            }
        },
        error: function (xhr, status, error) {
            $('#codeStation').html('<div style="text-align: center; color: red;">Error al cargar los datos.</div>');
            console.error('Error al obtener los datos:', status, error);
        }
    });   
   
});







function validateAndDrawChart(typeSelectValue, dateRangeSelectValue, idDevice) {
    if (!typeSelectValue || typeSelectValue.length === 0) {
        console.warn("Advertencia: #typeSelect está vacío.");
        return;
    }
    if (!dateRangeSelectValue || dateRangeSelectValue.length === 0) {
        console.warn("Advertencia: #dateRangeSelect está vacío.");
        return;
    }
    if (!idDevice) {
        console.warn("Advertencia: idDevice no está definido.");
        return;
    }

    drawChart(typeSelectValue, dateRangeSelectValue, idDevice);
}

$('#typeSelect').on('select2:select select2:unselect', function () {
    let typeSelectValue = $('#typeSelect').val();
    let dateRangeSelectValue = $('#dateRangeSelect').val();    
    validateAndDrawChart(typeSelectValue, dateRangeSelectValue, idDevice);
});

$('#dateRangeSelect').on('select2:select', function () {
    let typeSelectValue = $('#typeSelect').val();
    let dateRangeSelectValue = $('#dateRangeSelect').val();    
    validateAndDrawChart(typeSelectValue, dateRangeSelectValue, idDevice);
});;







function drawChart(selectedId, dateRangeSelectValue, idDevice) {
    $('#loadingSpinner').show();

    console.log('Datos enviados:', {
        sensor: selectedId,
        periodo: dateRangeSelectValue,
        estacion: idDevice,
    });

    $.ajax({
        url: 'http://10.0.0.75/api_caimanes/public/api/plot',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ 
            sensor: selectedId, 
            periodo: dateRangeSelectValue,
            estacion: idDevice, 
        }),
        success: function(response) {
            console.log('Respuesta del servidor:', response);
            
            $('#loadingSpinner').hide();
        
            let statsContainer = $(".stats-container");
            statsContainer.empty();
            let notaContainer = $("#notas"); // Contenedor de notas
            notaContainer.empty(); // Limpiar notas previas
        
            // Validar si la respuesta tiene datos útiles
            if (!response || response.length === 0 || !response[0].stats) {
                console.warn("No hay datos válidos para mostrar.");
                $('#plotVar').html("Sin datos");
                $('#dateMin').html("—");
                $('#dateMax').html("—");
                plotChart(response);
                return;
            }
        
            let statsHtml = response.map((param, index) => { 
                if (!param.stats) return ""; // Si no hay estadísticas, omitir
        
                // Obtener nota de compromiso si existe
                let compromisoNota = param.compromisos?.nota ? `<p class="compromiso-text">${param.compromisos.nota}</p>` : "";
        
                // Agregar compromiso al contenedor de notas
                if (compromisoNota) {
                    notaContainer.append(compromisoNota);
                }
        
                return `
                    <div class="stats-row">                       
                        <div class="stat-values">
                            <div class="stat-item">
                                <p class="stat-label"><i class="fas fa-arrow-up"></i> Valor Máximo</p>
                                <p class="stat-value">${param.stats.max ?? "N/A"}</p>
                            </div>
                            <div class="stat-item">
                                <p class="stat-label"><i class="fas fa-arrow-down"></i> Valor Mínimo</p>
                                <p class="stat-value">${param.stats.min ?? "N/A"}</p>
                            </div>
                            <div class="stat-item">
                                <p class="stat-label"><i class="fas fa-chart-bar"></i> Promedio</p>
                                <p class="stat-value">${param.stats.average ?? "N/A"}</p>
                            </div>
                            <div class="stat-item">
                                <p class="stat-label"><i class="fas fa-percentage"></i> Desv. Std</p>
                                <p class="stat-value">${param.stats.dvst ?? "N/A"}</p>
                            </div>
                            <div class="stat-item">
                                <p class="stat-label"><i class="fas fa-leaf"></i> Parámetro</p>
                                <p class="stat-value">${param.parametro || "N/A"} (${param.unidad || "N/A"})</p>
                            </div>
                        </div>
                    </div>
                `;
            }).join("");
        
            statsContainer.append(statsHtml);
        
            // Actualizar detalles de la variable y fechas
            $('#plotVar').html(response.length === 1 ? (response[0].parametro || "N/A") : `${response[0].parametro || "N/A"} vs ${response[1]?.parametro || "N/A"}`);
            $('#dateMin').html(response[0].dateRange?.minDate || "—");
            $('#dateMax').html(response[0].dateRange?.maxDate || "—");
        
            // Llamar a la función para graficar si hay datos válidos
            if (response[0].data && response[0].data.length > 0) {
                plotChart(response);
            } else {
                console.warn("No hay datos para graficar.");
            }
        },
        
        error: function(error) {
            console.error('Error en la solicitud:', error);
            $('#loadingSpinner').hide();
        }
    });
}


// Mostrar mensaje si se limpia la selección
$('#typeSelect').on('select2:clear', function () {
    console.log('Selección limpia');
});

// Configuración global para idioma español


function convertToBoolean(value) {
return value === 0 ? true : false;
}



function plotChart(dataArray) {
    Highcharts.setOptions({
        lang: {
            loading: 'Cargando...',
            months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            shortMonths: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            exportButtonTitle: "Exportar",
            printButtonTitle: "Imprimir",
            rangeSelectorFrom: "Desde",
            rangeSelectorTo: "Hasta",
            rangeSelectorZoom: "Periodo",
            contextButtonTitle: 'Exportar',
            downloadPNG: 'Descargar imagen PNG',
            downloadJPEG: 'Descargar imagen JPEG',
            downloadPDF: 'Descargar imagen PDF',
            downloadSVG: 'Descargar imagen SVG',
            downloadXLS: 'Descargar Archivo Excel',
            viewData: 'Ver Data',
            printChart: 'Imprimir',
            resetZoom: 'Reiniciar zoom',
            resetZoomTitle: 'Reiniciar zoom',
            decimalPoint: ",",
            thousandsSep: '.',
            noData: "No hay datos disponibles"
        }
    });

    const colors = ['#00768e', '#ff5733'];

    // Asegurar que dataArray tiene datos válidos
    if (!Array.isArray(dataArray) || dataArray.length === 0) {
        console.warn("No hay datos disponibles, se mostrará el mensaje de 'No hay datos'.");
        dataArray = [{ name: "Sin datos", unidad: "", parametro: "", data: [], yAxis: 0 }];
    }

    // Título seguro para el gráfico
    const chartTitle = dataArray.length === 1 
        ? (dataArray[0]?.name || "Gráfica de Parámetros") 
        : `${dataArray[0]?.name || "Parámetro 1"} vs ${dataArray[1]?.name || "Parámetro 2"}`;

    Highcharts.chart('conductivityChart', {
        lang: {
            loading: 'Cargando...',
            months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            shortMonths: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            exportButtonTitle: "Exportar",
            printButtonTitle: "Imprimir",
            rangeSelectorFrom: "Desde",
            rangeSelectorTo: "Hasta",
            rangeSelectorZoom: "Periodo",
            contextButtonTitle: 'Exportar',
            downloadPNG: 'Descargar imagen PNG',
            downloadJPEG: 'Descargar imagen JPEG',
            downloadPDF: 'Descargar imagen PDF',
            downloadSVG: 'Descargar imagen SVG',
            downloadXLS: 'Descargar Archivo Excel',
            viewData: 'Ver Data',
            printChart: 'Imprimir',
            resetZoom: 'Reiniciar zoom',
            resetZoomTitle: 'Reiniciar zoom',
            decimalPoint: ",",
            thousandsSep: '.',
            noData: "No hay datos disponibles"
        },
        chart: {
            type: 'line',
            backgroundColor: 'transparent',
            style: { fontFamily: 'Poppins, serif' },
            zoomType: 'x',
        },
        exporting: {
            enabled: true,
            buttons: {
                contextButton: {
                    symbol: 'menu',
                    menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG']
                }
            }
        },
        navigator: {
            enabled: true,
            height: 50,
            maskFill: 'rgba(200, 200, 200, 0.3)', // Un gris claro para un efecto más moderno y suave
            outlineColor: '#cccccc',             // Contorno en gris claro
            outlineWidth: 1,
            handles: {
              width: 12,
              height: 15,
              backgroundColor: '#00768e',        // Se mantiene el color del selector
              borderColor: 'white'               // Borde blanco para resaltar
            }
          },
        credits: { enabled: false },
        title: { 
            text: chartTitle, 
            style: { color: '#949494', fontFamily: 'Poppins, serif', fontWeight: '300', fontSize: '13px' } 
        },
        subtitle: { 
            text: dataArray[0]?.periodo || "Periodo no disponible", 
            style: { color: '#949494', fontFamily: 'Poppins, serif', fontSize: '11px' } 
        },
        xAxis: {
            type: 'datetime',
            title: { 
                text: '', 
                style: { color: '#00768e', fontFamily: 'Poppins, serif', fontWeight: '600', fontSize: '10px'  } 
            },
            labels: { style: { fontFamily: 'Poppins, serif', fontSize: '10px' } },
            gridLineWidth: 1,
            gridLineColor: '#E0E0E0',
            gridLineDashStyle: 'dash',
            crosshair: { width: 1, dashStyle: 'solid' },
            lineColor: '#babbbc', // Cambia la línea del eje X a negro
            lineWidth: 1 // Asegura que la línea sea visible
        },
        yAxis: dataArray.length > 0 ? [
            dataArray[0] ? {
                title: { 
                    text: `${dataArray[0]?.parametro || ""} [${dataArray[0]?.unidad || ""}]`,
                    style: { color: colors[0], fontFamily: 'Poppins, serif', fontWeight: '600' } 
                },
                labels: {
                    formatter: function () { 
                        let decimales = dataArray[0]?.decimales ?? 1; // Usa el valor de dataArray[0].decimales o 1 por defecto
                        return this.value.toFixed(decimales); 
                    },
                    style: { 
                        color: colors[0], 
                        fontFamily: 'Poppins, serif', 
                        fontSize: '10px' 
                    }
                },
                crosshair: { width: 1, dashStyle: 'solid' },
                gridLineWidth: 1,
                gridLineColor: '#E0E0E0',
                gridLineDashStyle: 'dash',
                min: dataArray[0]?.limite_inferior ?? undefined,
                max: dataArray[0]?.limite_superior ?? undefined,
                reversed: dataArray[0]?.yAxis === 0,
                plotLines: dataArray[0]?.plotlines ?? [] // Asigna los plotLines correspondientes
            } : null,
        
            dataArray[1] ? {
                title: { 
                    text: `${dataArray[1]?.parametro || ""} [${dataArray[1]?.unidad || ""}]`,
                    style: { color: colors[1], fontFamily: 'Poppins, serif', fontWeight: '600' } 
                },
                labels: {
                    formatter: function () { 
                        let decimales = dataArray[1]?.decimales ?? 1; // Usa el valor de dataArray[0].decimales o 1 por defecto
                        return this.value.toFixed(decimales); 
                    },
                    style: { 
                        color: colors[0], 
                        fontFamily: 'Poppins, serif', 
                        fontSize: '10px' 
                    }
                },
                crosshair: { width: 1, dashStyle: 'solid' },
                opposite: true,               
                min: dataArray[1]?.limite_inferior ?? undefined,
                max: dataArray[1]?.limite_superior ?? undefined,
                reversed: dataArray[1]?.yAxis === 0,
                plotLines: dataArray[1]?.plotlines ?? [] // Asigna los plotLines correspondientes
            } : null
        ].filter(Boolean) : [], 
        tooltip: {
            shared: true,
            headerFormat: '<b>{point.x:%d-%m-%Y %H:%M}</b><br>',
            pointFormatter: function () {
                // Buscar la serie en dataArray por su nombre
                let serie = dataArray.find(s => s.name === this.series.name);
                let unidad = serie?.unidad ? ` ${serie.unidad}` : ""; // Agregar unidad si existe
                
                return `<span style="color:${this.color}">●</span> 
                        ${this.series.name}: <b>${this.y ?? "N/A"}${unidad}</b><br>`;
            },
            style: { fontFamily: 'Poppins, serif' }
        },
        
        lang: {
            noData: "No hay datos disponibles"
        },
        noData: {
            style: {
                fontSize: '16px',
                fontWeight: 'bold',
                color: '#666'
            }
        },
        series: dataArray.map((param, index) => ({
            name: `${param?.name || " " }`,
            data: Array.isArray(param?.data) ? param.data : [],
            yAxis: index, // Asigna el eje Y correspondiente (0 o 1)
            color: colors[index % colors.length],
            threshold: null,
            dashStyle:'Solid',
            marker: {
                enabled: true,
                symbol: 'circle', // Forma circular
                lineColor: colors[index % colors.length], // Color del borde = color de la serie
                lineWidth: 2, // Ancho del borde
                fillColor: '#FFFFFF', // Fondo blanco
                radius: 2  // Tamaño del marcador (puedes ajustar según prefieras)
            }
        }))
    });
}





// Función para graficar
function plotChart_old(data = { parametro: '', unidad: '', name: '', periodo: '', data: [], yAxis : 1 }) {






Highcharts.setOptions({
    lang: {
        loading: 'Cargando...',
        months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        shortMonths: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        exportButtonTitle: "Exportar",
        printButtonTitle: "Importar",
        rangeSelectorFrom: "Desde",
        rangeSelectorTo: "Hasta",
        rangeSelectorZoom: "Periodo",
        contextButtonTitle: 'Exportar',
        downloadPNG: 'Descargar imagen PNG',
        downloadJPEG: 'Descargar imagen JPEG',
        downloadPDF: 'Descargar imagen PDF',
        downloadSVG: 'Descargar imagen SVG',
        downloadXLS: 'Descargar Archivo Excel',
        viewData: 'Ver Data',
        printChart: 'Imprimir',
        resetZoom: 'Reiniciar zoom',
        resetZoomTitle: 'Reiniciar zoom',
        decimalPoint: ",",
        thousandsSep: '.',
        noData: "No hay datos disponibles" // Personaliza el texto
    }
 
   
});

Highcharts.chart('conductivityChart', {
    chart: {
     
        type: 'line', 
        backgroundColor: 'transparent',                  
        style: {
            fontFamily: 'Poppins, serif' // Aplicar fuente al gráfico
        },
        zoomType: 'x', // Permitir zoom en el eje X
        resetZoomButton: {
            position: {
                align: 'right', // Alineación derecha
                verticalAlign: 'top', // Alineación superior
                x: -10, // Margen desde la derecha
                y: 10 // Margen desde la parte superior
            },
            theme: {
                fill: '#f7f7f7', // Fondo del botón
                stroke: '#cccccc', // Borde del botón
                r: 3, // Bordes redondeados
                style: {
                    color: '#333333' // Color del texto
                },
                states: {
                    hover: {
                        fill: '#e6e6e6' // Fondo en hover
                    }
                }
            }
        },
        
    },
    exporting: {
        enabled: true,
        buttons: {
            contextButton: {
                symbol: 'menu',
                menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG']
            }
        }
    },
    navigator: {
        enabled: true,
        series: {
            type: 'line',
            color: '#00768e',
            data: data.data || [] // Inicialmente vacío
        }
    },
    credits: {
        enabled: false
    },
    title: {
        text: `${data.name && data.unidad ? `${data.name} — [ ${data.unidad} ]` : ''}`,
        style: {
            color: 'gray',
            fontFamily: 'Poppins, serif',
            fontWeight: '300',
            fontSize: '12px'
        }
    },
    subtitle: {
        text: `${data.periodo ? data.periodo : ''}`, // Si data.name existe, se usa; si no, queda vacío
        style: {
            color: 'darkgray',
            fontFamily: 'Poppins, serif',
            fontWeight: '300',
            fontSize: '10px'
        }
    },
    xAxis: {
        type: 'datetime',
        title: {
            text: '',
            style: {
                color: '#00768e',
                fontFamily: 'Poppins, serif',
                fontWeight: '600'
            }
        },
        labels: {
            style: {
                fontFamily: 'Poppins, serif',
                fontSize: '12px'
            }
        },
        gridLineWidth: 1,
        gridLineColor: '#E0E0E0'
    },
    yAxis: {
        reversed:  convertToBoolean(data.yAxis),
        title: {
            text: data.parametro ? `${data.parametro} (${data.unidad})` : '',
            style: {
                color: '#00768e',
                fontFamily: 'Poppins, serif',
                fontWeight: '600'
            }
        },
        labels: {
            formatter: function () {
                return this.value.toFixed(1); // Mostrar un decimal
            },
            style: {
                fontFamily: 'Poppins, serif',
                fontSize: '12px'
            }
        },
        gridLineWidth: 1,
        gridLineColor: '#E0E0E0'
    },
    tooltip: {
        headerFormat: '<b>{series.name}</b><br>',
        pointFormat: 'Fecha: {point.x:%d-%m-%Y %H:%M}<br>Valor: {point.y} ' + data.unidad,
        style: {
            fontFamily: 'Poppins, serif'
        }
    },
    noData: {
        style: {
            fontWeight: 'bold',
            fontSize: '16px',
            color: '#04647c'
        }
    },
    series: [{
        name: data.name || '', // Nombre predeterminado
        data: data.data || [], // Sin datos inicialmente
        color: '#00768e',
        marker: {
            enabled: true, // Siempre mostrar los marcadores
            symbol: 'circle', // Marcador circular
            lineColor: '#00768e', // Borde del marcador
            lineWidth: 2, // Ancho del borde
            fillColor: '#FFFFFF', // Fondo blanco
            radius: 2 // Tamaño del marcador
        }
    }]
});
}
