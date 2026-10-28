/* Carga de imágenes: cada contenedor con la clase "ep-img" muestra un esqueleto
   y un anillo de carga hasta que su <img> termina de cargar (o falla). */
(function () {
   function init() {
      document.querySelectorAll('.ep-img').forEach(function (box) {
         const img = box.querySelector('img');
         const done = function () { box.classList.add('is-loaded'); };
         if (!img || (img.complete && img.naturalWidth > 0)) { done(); return; }
         img.addEventListener('load', done, { once: true });
         img.addEventListener('error', done, { once: true });
         if (img.complete) done();
      });
   }
   if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
   else init();
})();
