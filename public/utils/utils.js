$(document).ready(function() {

    document.addEventListener("DOMContentLoaded", function() {
       var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
       var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
       });
    });
 
    const today = new Date().toISOString().split('T')[0];
    const lastShown = localStorage.getItem('modalLastShown');
 
    if (lastShown !== today) {
       $('#welcome').modal('show');
    }
 
    $('#welcome .modal-footer button[data-bs-dismiss="modal"]').on('click', function() {
       localStorage.setItem('modalLastShown', today);
       console.log('Fecha guardada al hacer clic en Ingresar:', today);
    });


    function detectZoom() {
        const zoom = Math.round(window.devicePixelRatio * 100);
        if (zoom < 100) {
          alert("Por favor, restablezca el zoom al 100% para una mejor visualización.");
        }
      }
      
      window.addEventListener('load', detectZoom);
      window.addEventListener('resize', detectZoom);
 });