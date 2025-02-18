const mix = require('laravel-mix');

mix.js('resources/js/map.js', 'public/js')
   .options({
       terser: {
           extractComments: false,  // No extraer comentarios
           terserOptions: {
               compress: {
                   drop_console: true,  // Elimina los console.log
               },
               output: {
                   comments: false,     // Elimina todos los comentarios
               },
           },
       },
   })
   .version();  // Agrega un hash al archivo para evitar el caché
