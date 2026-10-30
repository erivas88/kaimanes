document.addEventListener('DOMContentLoaded', function() {

   const BASE_URL = document.querySelector('meta[name="api-base-url"]').getAttribute('content');
   //console.log(BASE_URL)

   $.ajax({
      url: `${BASE_URL}api/info_device/${idDevice}`,
      method: 'GET',
      dataType: 'json',
      success: function(response) {
         if (response && response.parametros && response.periodos) {
            $('#typeSelect').select2({
               placeholder: 'Parámetros',
               minimumResultsForSearch: Infinity,
               data: response.parametros.map(item => ({
                  id: item.sensor,
                  text: item.tipo
               })),
               language: {
                  noResults: () => "Sin parámetros asociados"
               },
               escapeMarkup: markup => markup
            }).prop('disabled', false);

            $('#dateRangeSelect').select2({
               placeholder: 'Periodo',
               minimumResultsForSearch: Infinity,
               data: response.periodos.map(item => ({
                  id: item.id_periodo,
                  text: item.descripcion
               })),
               language: {
                  noResults: () => "Sin periodos asociados"
               }
            }).prop('disabled', false);

            typeSelectValue = response.parametros.length > 0 ? response.parametros[0].sensor : null;
            dateRangeSelectValue = response.periodos.length > 0 ? response.periodos[0].id_periodo : null;

            $('#typeSelect').val(typeSelectValue).trigger('change');
            $('#dateRangeSelect').val(dateRangeSelectValue).trigger('change');

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
      "ordering": false,
      ajax: {

         url: `${BASE_URL}api/table`,
         type: "POST",
         data: function(d) {
            d.device = idDevice;
            d.periodo = $('#dateRangeSelect').val() || 1;

         },
         dataSrc: function(json) {
            return prepararObservaciones(json.data || []);
         }
      },
      createdRow: function(row, data) {
         $(row).addClass(data._vigente ? 'obs-vigente' : 'obs-vencida');
      },
      columns: [{
            data: 'nombre',
            width: '12%'
         },
         {
            data: 'tipo',
            width: '20%'
         },
         {
            data: 'fecha_inicio',
            width: '14%',
            render: function(valor) {
               return fechaObs(valor);
            }
         },
         {
            data: 'fecha_fin',
            width: '16%',
            render: function(valor, tipo, fila) {
               let chip = fila._vigente ? '<span class="obs-chip">Vigente</span>' : '';
               return fechaObs(valor) + chip;
            }
         },
         {
            data: 'observacion',
            width: '38%'
         }
      ]
   });

   // "dd-mm-yyyy HH:MM" -> Date (el servidor entrega las fechas ya formateadas)
   function parseFechaObs(texto) {
      let m = /^(\d{2})-(\d{2})-(\d{4}) (\d{2}):(\d{2})$/.exec(texto || '');
      return m ? new Date(+m[3], +m[2] - 1, +m[1], +m[4], +m[5]) : null;
   }

   function fechaObs(texto) {
      let partes = (texto || '').split(' ');
      return `<span class="obs-fecha">${partes[0] || ''}</span><span class="obs-hora">${partes[1] || ''}</span>`;
   }

   // Marca las observaciones vigentes y las deja primero.
   function prepararObservaciones(filas) {
      let ahora = new Date();

      return filas.map(function(f) {
         let inicio = parseFechaObs(f.fecha_inicio);
         let fin = parseFechaObs(f.fecha_fin);
         return Object.assign({}, f, {
            _fin: fin,
            _vigente: !!(fin && fin >= ahora && (!inicio || inicio <= ahora))
         });
      }).sort(function(a, b) {
         if (a._vigente !== b._vigente) return a._vigente ? -1 : 1;
         return (b._fin || 0) - (a._fin || 0);
      });
   }

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
      //console.warn("Advertencia: #typeSelect está vacío.");
      return;
   }
   if (!dateRangeSelectValue || dateRangeSelectValue.length === 0) {
      //console.warn("Advertencia: #dateRangeSelect está vacío.");
      return;
   }
   if (!idDevice) {
      //console.warn("Advertencia: idDevice no está definido.");
      return;
   }

   // los selectores son de selección única; drawChart espera arreglos
   drawChart([].concat(typeSelectValue), [].concat(dateRangeSelectValue), idDevice);
}

$('#typeSelect, #dateRangeSelect').on('select2:select', function() {
   validateAndDrawChart($('#typeSelect').val(), $('#dateRangeSelect').val(), idDevice);
});

function drawChart(selectedId, dateRangeSelectValue, idDevice) {
   $('#loadingSpinner').show();

   const BASE_URL = document.querySelector('meta[name="api-base-url"]').getAttribute('content');
   //console.log(BASE_URL)

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
            //console.warn("No hay datos válidos para mostrar.");
            $('#plotVar').html("Sin datos");
            $('#dateMin').html("—");
            $('#dateMax').html("—");
            $('#lastUpdate').attr('hidden', true);
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

            const unidad = param.unidad || "";
            // coma decimal y punto de miles, igual que el eje y el tooltip del gráfico
            // respeta los decimales que entrega el backend (decimales_serie); sin dato, deja el valor tal cual
            const decimalesSerie = param.decimales_serie ?? param.decimales;
            const formatNumber = value => {
               const texto = decimalesSerie !== null && decimalesSerie !== undefined && !isNaN(value) ?
                  Number(value).toFixed(decimalesSerie) : String(value);
               const [entero, decimal] = texto.split('.');
               const conMiles = entero.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
               return decimal !== undefined ? `${conMiles},${decimal}` : conMiles;
            };
            const withUnit = value => value === null || value === undefined ?
               "N/A" :
               `${formatNumber(value)}${unidad ? `<span class="kpi-unit">${unidad}</span>` : ""}`;

            return `
    <div class="kpi-strip">
        <div class="kpi">
            <div class="ds-label">Valor Máximo</div>
            <p class="kpi-value">${withUnit(param.stats.max)}</p>
        </div>
        <div class="kpi">
            <div class="ds-label">Valor Mínimo</div>
            <p class="kpi-value">${withUnit(param.stats.min)}</p>
        </div>
        <div class="kpi">
            <div class="ds-label">Promedio</div>
            <p class="kpi-value">${withUnit(param.stats.average)}</p>
        </div>
        <div class="kpi">
            <div class="ds-label">Desv. Std</div>
            <p class="kpi-value">${withUnit(param.stats.dvst)}</p>
        </div>
    </div>
`;

         }).join("");

         statsContainer.append(statsHtml);

         // Actualizar detalles de la variable y fechas
         $('#plotVar').html(response.length === 1 ? (response[0].parametro || "N/A") : `${response[0].parametro || "N/A"} vs ${response[1]?.parametro || "N/A"}`);
         $('#dateMin').html(response[0].dateRange?.minDate || "—");
         $('#dateMax').html(response[0].dateRange?.maxDate || "—");

         // Fecha y hora del último dato recibido
         (function () {
            const data = response[0]?.data;
            let ultimo = null;
            if (Array.isArray(data)) {
               for (let i = data.length - 1; i >= 0 && ultimo === null; i--) {
                  const p = data[i];
                  const x = Array.isArray(p) ? p[0] : (p && typeof p === 'object' ? p.x : null);
                  const y = Array.isArray(p) ? p[1] : (p && typeof p === 'object' ? p.y : p);
                  if (x !== null && x !== undefined && y !== null && y !== undefined) ultimo = x;
               }
            }
            const el = $('#lastUpdate');
            if (ultimo === null) { el.attr('hidden', true); return; }
            el.find('.chart-updated-text').text('Último dato: ' + Highcharts.dateFormat('%d-%m-%Y %H:%M', ultimo));
            el.removeAttr('hidden');
         })();

         // Llamar a la función para graficar si hay datos válidos
         if (response[0].data && response[0].data.length > 0) {
            plotChart(response);
         } else {
            plotChart(response);
         }
      },

      error: function(error) {
         console.error('Error en la solicitud:', error);
         $('#loadingSpinner').hide();
      }
   });
}

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

// "26-10-2026 01:00:00" -> Date (null si el formato no coincide)
function parseDateRange(text) {
   const m = /^(\d{2})-(\d{2})-(\d{4})[ T](\d{2}):(\d{2})/.exec(text || '');
   return m ? new Date(+m[3], +m[2] - 1, +m[1], +m[4], +m[5]) : null;
}

// Período legible: "Lunes 26 de octubre de 2026 · 01:00 – 13:00" (mismo día)
// o "19 oct 2026 · 01:00  →  26 oct 2026 · 13:00  (7 días)" (varios días).
function formatPeriodo(range, fallback) {
   const from = parseDateRange(range?.minDate);
   const to = parseDateRange(range?.maxDate);
   if (!from || !to) return fallback || "Periodo no disponible";

   const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
   const dias = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
   const hora = d => `${String(d.getHours()).padStart(2, '0')}:${String(d.getMinutes()).padStart(2, '0')}`;
   const corto = d => `${d.getDate()} ${meses[d.getMonth()].slice(0, 3)} ${d.getFullYear()}`;

   if (from.toDateString() === to.toDateString()) {
      const largo = `${dias[from.getDay()]} ${from.getDate()} de ${meses[from.getMonth()]} de ${from.getFullYear()}`;
      return `${largo.charAt(0).toUpperCase()}${largo.slice(1)}  ·  ${hora(from)} – ${hora(to)}`;
   }

   // días completos transcurridos (7,5 días -> 7), coherente con "Últimos 7 Días"
   const nDias = Math.max(1, Math.floor((to - from) / 86400000));
   return `${corto(from)} · ${hora(from)}  →  ${corto(to)} · ${hora(to)}  (${nDias} ${nDias === 1 ? 'día' : 'días'})`;
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

   const colors = ['#00768e', '#20c997'];

   if (!Array.isArray(dataArray) || dataArray.length === 0) {
      //console.warn("No hay datos disponibles, se mostrará el mensaje de 'No hay datos'.");
      dataArray = [{
         name: "Sin datos",
         unidad: "",
         parametro: "",
         data: [],
         yAxis: 0
      }];
   }

   // Sin ningún dato: el área del gráfico se muestra enmarcada y sin controles que no sirven
   const sinDatos = !dataArray.some(p => Array.isArray(p?.data) && p.data.length > 0);
   $('#conductivityChart').toggleClass('is-empty', sinDatos);

   const stationName = document.getElementById('primary_title')?.textContent.trim() || '';
   const stationPrefix = stationName ? `${stationName} — ` : '';

   // En pantalla el título es solo el parámetro (la estación ya está en la cabecera);
   // en las descargas (PNG/PDF/SVG) se antepone la estación para que la imagen sea autosuficiente.
   const paramTitle = dataArray.length === 1 ?
      (dataArray[0]?.name || "Gráfica de Parámetros") :
      `${dataArray[0]?.name || "Parámetro 1"} vs ${dataArray[1]?.name || "Parámetro 2"}`;
   const chartTitle = stationPrefix + paramTitle;

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

   // Último valor registrado (para destacarlo con una serie aparte: así el navegador de abajo no lo muestra)
   function getLastPoint(data) {
      if (!Array.isArray(data)) return null;
      for (let i = data.length - 1; i >= 0; i--) {
         const p = data[i];
         const x = Array.isArray(p) ? p[0] : (p && typeof p === 'object' ? p.x : undefined);
         const y = Array.isArray(p) ? p[1] : (p && typeof p === 'object' ? p.y : p);
         if (y !== null && y !== undefined && x !== undefined) return [x, y];
      }
      return null;
   }

   // Asa vertical con rayitas para el navegador: se lee como control de rango, no como un punto de la serie
   Highcharts.SVGRenderer.prototype.symbols.grip = function(x, y, w, h) {
      const r = 3;
      const cx = x + w / 2;
      return [
         'M', x + r, y,
         'L', x + w - r, y,
         'A', r, r, 0, 0, 1, x + w, y + r,
         'L', x + w, y + h - r,
         'A', r, r, 0, 0, 1, x + w - r, y + h,
         'L', x + r, y + h,
         'A', r, r, 0, 0, 1, x, y + h - r,
         'L', x, y + r,
         'A', r, r, 0, 0, 1, x + r, y,
         'Z',
         'M', cx - 1.5, y + h * 0.3, 'L', cx - 1.5, y + h * 0.7,
         'M', cx + 1.5, y + h * 0.3, 'L', cx + 1.5, y + h * 0.7
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
         spacing: [18, 18, 12, 12],
         style: {
            fontFamily: 'Poppins, serif'
         },
         zoomType: 'x',
      },
      exporting: {
         enabled: !sinDatos,
         chartOptions: {
            title: {
               text: chartTitle
            }
         },
         buttons: {
            contextButton: {
               symbol: 'menu',
               menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG']
            }
         }
      },
      navigator: {
         enabled: !sinDatos,
         height: 22,
         margin: 8,
         maskFill: 'rgba(32, 131, 147, 0.10)',
         outlineColor: '#e3ebed',
         outlineWidth: 1,
         series: {
            type: 'line',
            color: '#208393',
            lineWidth: 1,
            fillOpacity: 0
         },
         xAxis: {
            labels: { enabled: false },
            tickLength: 0,
            lineWidth: 0
         },
         yAxis: {
            min: dataArray[0]?.limite_inferior,
            max: dataArray[0]?.limite_superior
         },
         handles: {
            symbols: ['grip', 'grip'],
            lineWidth: 1,
            width: 8,
            height: 16,
            backgroundColor: '#208393',
            borderColor: '#ffffff'
         }
      },
      credits: {
         enabled: false
      },
      title: {
         text: sinDatos ? null : paramTitle,
         style: {
            color: '#3d4f56',
            fontFamily: 'Poppins, serif',
            fontWeight: '600',
            fontSize: '15px'
         }
      },
      legend: {
         enabled: false
      },
      subtitle: {
         text: sinDatos ? null : formatPeriodo(dataArray[0]?.dateRange, dataArray[0]?.periodo),
         style: {
            color: '#8a949a',
            fontFamily: 'Poppins, serif',
            fontSize: '12px'
         }
      },
      xAxis: {
         visible: !sinDatos,
         type: 'datetime',
         breaks: breaksCalculados,
         labels: {
            style: {
               color: '#6c757d',
               fontFamily: 'Poppins, serif',
               fontSize: '10px'
            }
         },
         gridLineWidth: 1,
         gridLineColor: '#eceff1',
         gridLineDashStyle: 'Solid',
         tickColor: '#d5d8da',
         crosshair: {
            width: 1,
            color: '#b8c4c8',
            dashStyle: 'Solid'
         },
         lineColor: '#d5d8da',
         lineWidth: 1
      },
      yAxis: dataArray.map((param, index) => ({
         visible: !sinDatos,
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
               color: '#6c757d',
               fontFamily: 'Poppins, serif',
               fontSize: '10px'
            }
         },
         crosshair: {
            width: 1,
            color: '#b8c4c8',
            dashStyle: 'Solid'
         },
         gridLineWidth: 1,
         gridLineColor: '#eceff1',
         gridLineDashStyle: 'Solid',
         min: param?.limite_inferior,
         max: param?.limite_superior,
         reversed: param?.yAxis === 0,
         opposite: index > 0,
         plotLines: param?.plotlines ?? []
      })),
      tooltip: {
         shared: true,
         backgroundColor: '#ffffff',
         borderColor: '#d5d8da',
         borderRadius: 0,
         borderWidth: 1,
         shadow: {
            color: 'rgba(0, 0, 0, 0.12)',
            offsetX: 0,
            offsetY: 2,
            width: 6
         },
         headerFormat: '<b>{point.x:%d-%m-%Y %H:%M}</b><br>',
         pointFormatter: function() {
            let serie = dataArray.find(s => s.name === this.series.name);
            let unidad = serie?.unidad ? ` ${serie.unidad}` : "";
            // respeta los decimales que entrega el backend (decimales_serie); sin dato, deja el valor tal cual
            let dec = serie?.decimales_serie ?? serie?.decimales;
            let texto = this.y !== null ? (dec !== null && dec !== undefined ? Number(this.y).toFixed(dec) : String(this.y)) : null;
            let valor = texto !== null ? texto.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.') : "N/A";
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
      plotOptions: {
         series: {
            // entrada suave: la línea se dibuja de izquierda a derecha
            animation: { duration: 900 }
         }
      },
      series: dataArray.flatMap((param, index) => {
         const main = {
         name: param?.name || " ",
         data: Array.isArray(param?.data) ? param.data : [],
         yAxis: index,
         color: colors[index % colors.length],
         threshold: null,
         dashStyle: 'Solid',
         lineWidth: 2,
         marker: {
            // con muchos puntos los marcadores saturan la línea: solo al pasar el cursor
            enabled: (param?.data?.length ?? 0) <= 60 ? true : false,
            symbol: 'circle',
            lineColor: colors[index % colors.length],
            lineWidth: 2,
            fillColor: '#FFFFFF',
            radius: 3,
            states: {
               hover: {
                  enabled: true,
                  radius: 5
               }
            }
         },
         connectNulls: true
         };

         // Punto grande en el último valor: serie aparte, sin tooltip ni reflejo en el navegador
         const last = getLastPoint(param?.data);
         if (!last) return [main];
         return [main, {
            type: 'scatter',
            name: ' ',
            data: [last],
            yAxis: index,
            color: colors[index % colors.length],
            showInLegend: false,
            showInNavigator: false,
            enableMouseTracking: false,
            zIndex: 5,
            marker: {
               enabled: true,
               symbol: 'circle',
               radius: 6,
               fillColor: colors[index % colors.length],
               lineColor: '#ffffff',
               lineWidth: 2
            },
            states: { inactive: { opacity: 1 } }
         }];
      })
   });

}