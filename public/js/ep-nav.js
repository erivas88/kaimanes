/* Panel lateral de estaciones (/estacion-publica)
   Solo actúa si el panel tiene la clase "ep-nav": quitar esa clase revierte el comportamiento. */
document.addEventListener('DOMContentLoaded', function () {
   const nav = document.querySelector('.ep-nav');
   if (!nav) return;

   const accordion = nav.querySelector('.accordion');
   if (!accordion) return;

   const norm = text => (text || '').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '').trim();
   const sections = Array.from(accordion.querySelectorAll('.accordion-item'));

   // Buscador
   const search = document.createElement('div');
   search.className = 'ep-nav-search';
   search.innerHTML =
      '<input type="search" class="ep-nav-search-input" placeholder="Buscar estación…" aria-label="Buscar estación" autocomplete="off">' +
      '<p class="ep-nav-empty" hidden>Sin resultados</p>';
   accordion.parentNode.insertBefore(search, accordion);

   const input = search.querySelector('input');
   const empty = search.querySelector('.ep-nav-empty');

   // Estado original (qué secciones estaban abiertas) para restaurar al limpiar
   const initialShown = sections.map(section => {
      const collapse = section.querySelector('.accordion-collapse');
      return collapse ? collapse.classList.contains('show') : false;
   });

   function applyFilter() {
      const query = norm(input.value);
      let anyMatch = false;

      sections.forEach((section, i) => {
         const collapse = section.querySelector('.accordion-collapse');
         const rows = Array.from(section.querySelectorAll('.list-group-item'));
         let matches = 0;

         rows.forEach(row => {
            const hit = !query || norm(row.textContent).includes(query);
            row.hidden = !hit;
            if (hit) matches++;
         });

         if (!query) {
            section.hidden = false;
            if (collapse) collapse.classList.toggle('show', initialShown[i]);
            return;
         }

         section.hidden = matches === 0;
         if (collapse && matches > 0) collapse.classList.add('show');
         if (matches > 0) anyMatch = true;
      });

      empty.hidden = !query || anyMatch;
   }

   input.addEventListener('input', applyFilter);
   input.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
         input.value = '';
         applyFilter();
      }
   });

   // Móvil: el selector va plegado tras un botón con la estación actual (el CSS lo muestra solo en pantallas angostas)
   const current = (document.getElementById('primary_title') || {}).textContent || 'Seleccionar';
   const toggle = document.createElement('button');
   toggle.type = 'button';
   toggle.className = 'ep-nav-toggle';
   toggle.setAttribute('aria-expanded', 'false');
   toggle.innerHTML =
      '<span class="ep-nav-toggle-text"><small>Estación</small><strong></strong></span>' +
      '<span class="ep-nav-toggle-action">Cambiar <i class="fas fa-chevron-down" aria-hidden="true"></i></span>';
   toggle.querySelector('strong').textContent = current.trim();
   nav.insertBefore(toggle, nav.firstChild);
   toggle.addEventListener('click', function () {
      const open = nav.classList.toggle('ep-nav-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
   });
});
