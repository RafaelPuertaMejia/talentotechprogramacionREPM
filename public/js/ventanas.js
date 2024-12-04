$(document).ready(function(){
    // Función para manejar el clic en los enlaces
    $('.stretched-link').click(function(e){
        e.preventDefault(); // Prevenir el comportamiento por defecto de los enlaces
        var page = $(this).attr('href'); // Obtener el valor del atributo href

        // Cargar el contenido de la página en el elemento <section>
        $('section').load(page);
    });
});

