
function copiarAlPortapapeles(link) {
    console.log('Intentando copiar al portapapeles:', link);

    // Intento de usar la API moderna del portapapeles
    if (navigator.clipboard) {
        navigator.clipboard.writeText(link).then(function() {
            console.log('Texto copiado al portapapeles con Clipboard API');
        }).catch(function(error) {
            console.error('Error al copiar al portapapeles con Clipboard API:', error);
            // Fallback si la API moderna falla
            copiarConExecCommand(link);
        });
    } else {
        // Si Clipboard API no está disponible, usa execCommand
        copiarConExecCommand(link);
    }
}

function copiarConExecCommand(link) {
    var aux = document.createElement("input");
    aux.setAttribute("value", link);
    document.body.appendChild(aux);
    aux.select();

    try {
        var successful = document.execCommand("copy");
        console.log('Texto copiado con execCommand:', successful);
    } catch (err) {
        console.error('Error al copiar con execCommand:', err);
    }

    document.body.removeChild(aux);
}

// Evento click para elementos con data-share
document.body.addEventListener('click', function (eve) {
    var target = eve.target.closest('[data-share]');
    
    if (target) {
        var url = target.getAttribute('data-share');
        console.log('Elemento clicado, URL a copiar/compartir:', url);

        // Intento de compartir con Web Share API
        if (navigator.share) {
            navigator.share({
                title: '',
                text: '',
                url: url
            }).then(function() {
                console.log('Compartido exitosamente');
            }).catch(function(error) {
                console.error('Error al compartir:', error);
                copiarAlPortapapeles(url); // Si falla, copia al portapapeles
            });
        } else {
            copiarAlPortapapeles(url); // Si no hay Web Share API, copia directamente
        }
    }
});
