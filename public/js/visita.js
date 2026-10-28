/* Registra la visita sin retrasar la página: petición aparte, no bloqueante. */
(function () {
   var meta = document.querySelector('meta[name="api-base-url"]');
   var base = meta ? meta.getAttribute('content') : (location.origin + '/');
   function send() {
      try { fetch(base + 'api/visita', { method: 'POST', keepalive: true }); } catch (e) {}
   }
   if (document.readyState === 'complete') send();
   else window.addEventListener('load', send, { once: true });
})();
