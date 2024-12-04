
// Carga la API de YouTube de forma asíncrona
var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// Crea un reproductor de YouTube después de que se descargue la API
var player;
function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
        videoId: '', // Inicialmente, no hay ningún video cargado
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
        }
    });
}
$(document).ready(function() {
    $('.video-button').on('click', function() {
        console.log("Botón clickeado");
        var url = $(this).data('url');
        var codigo_proyecto = $(this).data('codigo');
        var semestre_proyecto = $(this).data('semestre');
        loadVideo(url, codigo_proyecto, semestre_proyecto);
    });
});

// La API llamará a esta función cuando el reproductor esté listo
function onPlayerReady(event) {
    // No reproduce ningún video inicialmente
}

// La API llama a esta función cuando cambia el estado del reproductor
function onPlayerStateChange(event) {
    // Aquí puedes agregar controles para el reproductor
}

// Esta función carga un video en el reproductor
// Esta función carga un video en el reproductor
function loadVideo(url, codigo_proyecto, semestre_proyecto) {
    var videoId = url.split('v=')[1];
    var ampersandPosition = videoId.indexOf('&');
    if (ampersandPosition != -1) {
        videoId = videoId.substring(0, ampersandPosition);
    }
    player.loadVideoById(videoId);
    document.getElementById('codProyDoc').value = codigo_proyecto;
    document.getElementById('semestre').value = semestre_proyecto;
}



