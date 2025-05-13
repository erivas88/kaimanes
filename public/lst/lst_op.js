const url = window.location.href.replace(/#$/, ''); // Elimina '#' si está al final
const match = url.match(/\/(\d+)$/); // Busca un número al final de la URL
const idDevice = match ? match[1] : null;


//console.log(idDevice); // Resultado: "2"
