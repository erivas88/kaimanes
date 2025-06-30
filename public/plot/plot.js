document.addEventListener('DOMContentLoaded', function() {

    const BASE_URL = document.querySelector('meta[name="api-base-url"]').getAttribute('content');
    console.log(BASE_URL)

    $.ajax({
        url: `${BASE_URL}api/info_device/${idDevice}`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response && response.parametros && response.periodos) {
                $('#typeSelect').select2({
                    placeholder: 'Parámetros',
                    allowClear: true,
                    multiple: true,
                    minimumResultsForSearch: Infinity,
                    maximumSelectionLength: 1,
                    data: response.parametros.map(item => ({
                        id: item.sensor,
                        text: item.tipo
                    })),
                    language: {
                        noResults: () => "Sin parámetros asociados",
                        maximumSelected: () => "Solo puedes seleccionar 1 parámetro"
                    },
                    escapeMarkup: markup => markup
                }).prop('disabled', false);

                $('#dateRangeSelect').select2({
                    placeholder: 'Periodo',
                    allowClear: true,
                    multiple: true,
                    minimumResultsForSearch: Infinity,
                    maximumSelectionLength: 1,
                    data: response.periodos.map(item => ({
                        id: item.id_periodo,
                        text: item.descripcion
                    })),
                    language: {
                        noResults: () => "Sin parámetros asociados",
                        maximumSelected: () => "Solo puedes seleccionar 1 periodo"
                    }
                }).prop('disabled', false);

                typeSelectValue = response.parametros.length > 0 ? response.parametros[0].sensor : null;
                dateRangeSelectValue = response.periodos.length > 0 ? response.periodos[0].id_periodo : null;      
                drawChart([typeSelectValue], [dateRangeSelectValue], idDevice);

            } else {
                console.error('Datos incompletos en la respuesta del servidor.');
            }
        },
        error: function(xhr, status, error) {
            $('#codeStation').html('<div style="text-align: center; color: red;">Error al cargar los datos.</div>');
            console.error('Error al obtener los datos:', status, error);
        }
    });

    let table = $('#tableObservations').DataTable({
        "language": {
            "decimal": "",
            "emptyTable": "Sin Observaciones",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ observaciones",
            "infoEmpty": "Mostrando 0 a 0 de 0 observaciones",
            "infoFiltered": "(filtrados de _MAX_ observaciones)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ observaciones por página",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "searchPlaceholder": "Buscar...",
            "zeroRecords": "No se encontraron registros coincidentes",
            "paginate": {
                "first": '<i class="fa fa-fast-backward" aria-hidden="true"></i>',
                "last": '<i class="fa fa-fast-forward" aria-hidden="true"></i>',
                "next": '<i class="fa fa-chevron-right"></i>',
                "previous": '<i class="fa fa-chevron-left"></i>'
            },
            "aria": {
                "sortAscending": ": Activar para ordenar la columna en orden ascendente",
                "sortDescending": ": Activar para ordenar la columna en orden descendente"
            }
        },
        "lengthChange": false,
        ajax: {

            url: `${BASE_URL}api/table`,
            type: "POST",
            data: function(d) {
                d.device = idDevice;
                d.periodo = $('#dateRangeSelect').val() && $('#dateRangeSelect').val().length > 0 ?
                    $('#dateRangeSelect').val()[0] :
                    1;

            }
        },
        columns: [{
                data: 'nombre',
                width: '15%'
            },
            {
                data: 'tipo',
                width: '15%'
            },
            {
                data: 'fecha_inicio',
                width: '15%'
            },
            {
                data: 'fecha_fin',
                width: '25%'
            },
            {
                data: 'observacion',
                width: '35%'
            }
        ]
    });

    function showSpinner(img) {
        const spinner = $('<div class="spinner"></div>');
        img.append(spinner);
    }

    function hideSpinner(img) {
        img.find('.spinner').remove();
    }

    $('#pointStation').html('<i class="fas fa-spinner fa-spin"></i> &nbsp; Cargando...');
    $('#imgDevice').html('<i class="fas fa-spinner fa-spin"></i> &nbsp; Cargando...');

    if (!idDevice) {
        $('#codeStation').html('<div style="text-align: center; color: red;">Error: Parámetro id_device no proporcionado.</div>');
        return;
    }

    document.getElementById("OpenModal").addEventListener("click", function() {

        let typeSelectValue = $('#typeSelect').val();
        let dateRangeSelectValue = $('#dateRangeSelect').val();

        if (!typeSelectValue || typeSelectValue.length === 0) {
            typeSelectValue = $('#typeSelect option:first').val();
        }

        if (!dateRangeSelectValue || dateRangeSelectValue.length === 0) {
            dateRangeSelectValue = $('#dateRangeSelect option:first').val();
        }   

        const modal = new bootstrap.Modal(document.getElementById('observations'));

        table.ajax.reload();

        modal.show();

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

$('#typeSelect').on('select2:select select2:unselect', function() {
    let typeSelectValue = $('#typeSelect').val();
    let dateRangeSelectValue = $('#dateRangeSelect').val();
    validateAndDrawChart(typeSelectValue, dateRangeSelectValue, idDevice);
});

$('#dateRangeSelect').on('select2:select', function() {
    let typeSelectValue = $('#typeSelect').val();
    let dateRangeSelectValue = $('#dateRangeSelect').val();
    validateAndDrawChart(typeSelectValue, dateRangeSelectValue, idDevice);
});;

function drawChart(selectedId, dateRangeSelectValue, idDevice) {
    $('#loadingSpinner').show();

    /*console.log('Datos enviados:', {
       sensor: selectedId,
       periodo: dateRangeSelectValue,
       estacion: idDevice,
    });*/

    $.ajax({
        url: `${BASE_URL}api/plot`,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            sensor: selectedId,
            periodo: dateRangeSelectValue,
            estacion: idDevice,
        }),
        success: function(response) {
            //console.log('Respuesta del servidor:', response);

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

                let compromisoNota = param.compromisos ?
                    `<p class="compromiso-text">${param.compromisos}</p>` :
                    "";

                if (compromisoNota) {
                    notaContainer.append(compromisoNota);
                    // console.log(compromisoNota);
                }

                /*return `
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
                    `;*/

                return `
    <div class="row justify-content-start">
        <div class="col-custom mb-3">
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-arrow-up"></i></div>
                <div class="stat-text">
                    <p class="stat-label">Valor Máximo</p>
                    <p class="stat-value">${param.stats.max ?? "N/A"}</p>
                </div>
            </div>
        </div>
        <div class="col-custom mb-3">
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-arrow-down"></i></div>
                <div class="stat-text">
                    <p class="stat-label">Valor Mínimo</p>
                    <p class="stat-value">${param.stats.min ?? "N/A"}</p>
                </div>
            </div>
        </div>
        <div class="col-custom mb-3">
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-chart-bar"></i></div>
                <div class="stat-text">
                    <p class="stat-label">Promedio</p>
                    <p class="stat-value">${param.stats.average ?? "N/A"}</p>
                </div>
            </div>
        </div>
        <div class="col-custom mb-3">
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-percentage"></i></div>
                <div class="stat-text">
                    <p class="stat-label">Desv. Std</p>
                    <p class="stat-value">${param.stats.dvst ?? "N/A"}</p>
                </div>
            </div>
        </div>
        <div class="col-custom mb-3">
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-leaf"></i></div>
                <div class="stat-text">
                    <p class="stat-label">Parámetro</p>
                    <p class="stat-value">${cleanParametro(param.parametro)}</p>
                </div>
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

function cleanParametro(nombre) {
    if (!nombre) return "N/A";

    const lower = nombre.toLowerCase();

    if (lower.includes("nivel")) return "Nivel";
    if (lower.includes("conductividad")) return "Conductividad";

    // Por defecto devolver solo la primera palabra
    return nombre.split(" ")[0];
}

$('#typeSelect').on('select2:clear', function() {
    //console.log('Selección limpia');
});

function convertToBoolean(value) {
    return value === 0 ? true : false;
}

function generarBreaksDesdeDatos(data, umbralEnMilisegundos = 3 * 60 * 60 * 1000) {
    const breaks = [];

    for (let i = 1; i < data.length; i++) {
        const actual = data[i][0]; // timestamp actual
        const anterior = data[i - 1][0]; // timestamp anterior
        const delta = actual - anterior;

        if (delta > umbralEnMilisegundos) {
           breaks.push({
  from: anterior + 1,
  to: actual - 1,
  breakSize: 60 * 60 * 1000 // 1 hora visualmente
});
        }
    }

    return breaks;
}

function plotChart(dataArray) {

    const dataPrincipal = dataArray[0]?.data || [];
    const breaksCalculados = generarBreaksDesdeDatos(dataPrincipal);

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

    //const colors = ['#00768e', '#ff5733'];
    const colors = ['#00768e', '#20c997'];

    if (!Array.isArray(dataArray) || dataArray.length === 0) {
        console.warn("No hay datos disponibles, se mostrará el mensaje de 'No hay datos'.");
        dataArray = [{
            name: "Sin datos",
            unidad: "",
            parametro: "",
            data: [],
            yAxis: 0
        }];
    }

    const chartTitle = dataArray.length === 1 ?
        (dataArray[0]?.name || "Gráfica de Parámetros") :
        `${dataArray[0]?.name || "Parámetro 1"} vs ${dataArray[1]?.name || "Parámetro 2"}`;

    // Define a custom symbol path
    Highcharts.SVGRenderer.prototype.symbols.doublearrow = function(
        x, y, w, h
    ) {
        return [
            // right arrow
            'M', x + w / 2 + 1, y,
            'L', x + w / 2 + 1, y + h,
            x + w + w / 2 + 1, y + h / 2,
            'Z',
            // left arrow
            'M', x + w / 2 - 1, y,
            'L', x + w / 2 - 1, y + h,
            x - w / 2 - 1, y + h / 2,
            'Z'
        ];
    };

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
            thousandsSep: '.'
        },
        chart: {
            type: 'line',
            backgroundColor: 'transparent',
            style: {
                fontFamily: 'Poppins, serif'
            },
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
            maskFill: 'rgba(200, 200, 200, 0.3)',
            outlineColor: '#cccccc',
            outlineWidth: 1,
            /*handles: {
                width: 12,
                height: 15,
                backgroundColor: '#00768e',
                borderColor: 'white'
            }*/
            handles: {
                symbols: ['doublearrow', 'doublearrow'],
                lineWidth: 1,
                width: 9,
                height: 17,
                backgroundColor: '#00768e',
                borderColor: 'white'
            }
        },
        credits: {
            enabled: false
        },
        title: {
            text: chartTitle,
            style: {
                color: '#1f8293',
                fontFamily: 'Poppins, serif',
                fontWeight: '600',
                fontSize: '13px'
            },

        },
        legend: {
            enabled: false
        },
        subtitle: {
            text: dataArray[0]?.periodo || "Periodo no disponible",
            style: {
                color: '#949494',
                fontFamily: 'Poppins, serif',
                fontSize: '11px'
            }
        },
        xAxis: {
            type: 'datetime',
            breaks: breaksCalculados,
            labels: {
                style: {
                    fontFamily: 'Poppins, serif',
                    fontSize: '10px'
                }
            },
            gridLineWidth: 1,
            gridLineColor: '#E0E0E0',
            gridLineDashStyle: 'dash',
            crosshair: {
                width: 1,
                dashStyle: 'solid'
            },
            lineColor: '#babbbc',
            lineWidth: 1
        },
        yAxis: dataArray.map((param, index) => ({
            title: {
                text: `${param?.parametro || ""} [${param?.unidad || ""}]`,
                style: {
                    color: colors[index],
                    fontFamily: 'Poppins, serif',
                    fontWeight: '600'
                }
            },
            labels: {
                formatter: function() {
                    return this.value.toFixed(param?.decimales ?? 1).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                },
                style: {
                    color: colors[index],
                    fontFamily: 'Poppins, serif',
                    fontSize: '10px'
                }
            },
            crosshair: {
                width: 1,
                dashStyle: 'solid'
            },
            gridLineWidth: 1,
            gridLineColor: '#E0E0E0',
            gridLineDashStyle: 'dash',
            min: param?.limite_inferior,
            max: param?.limite_superior,
            reversed: param?.yAxis === 0,
            opposite: index > 0,
            plotLines: param?.plotlines ?? []
        })),
        tooltip: {
            shared: true,
            headerFormat: '<b>{point.x:%d-%m-%Y %H:%M}</b><br>',
            pointFormatter: function() {
                let serie = dataArray.find(s => s.name === this.series.name);
                let unidad = serie?.unidad ? ` ${serie.unidad}` : "";
                //let valor = this.y !== null ? this.y.toFixed(serie?.decimales ?? 1).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.') : "N/A";
                let valor = this.y !== null ? String(this.y).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.') : "N/A";
                return `<span style="color:${this.color}">●</span> ${this.series.name}: <b>${valor}${unidad}</b><br>`;
            },
            style: {
                fontFamily: 'Poppins, serif'
            }
        },
        noData: {
            style: {
                fontSize: '16px',
                fontWeight: 'bold',
                color: '#666'
            }
        },
        series: dataArray.map((param, index) => ({
            name: param?.name || " ",
            data: Array.isArray(param?.data) ? param.data : [],
            yAxis: index,
            color: colors[index % colors.length],
            threshold: null,
            dashStyle: 'Solid',
            marker: {
                enabled: true,
                symbol: 'circle',
                lineColor: colors[index % colors.length],
                lineWidth: 2,
                fillColor: '#FFFFFF',
                radius: 3
            },
            connectNulls: true 
        }))
    });

}