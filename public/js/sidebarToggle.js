document.addEventListener("DOMContentLoaded", function() {
    // Obtener referencias a los elementos relevantes
    const sidebarToggle = document.getElementById("sidebarToggle");
    const layoutSidenav = document.getElementById("layoutSidenav");

    // Verificar que los elementos existen antes de agregar el evento
    if (sidebarToggle && layoutSidenav) {
        // Agregar un controlador de eventos para el botón de alternar la barra lateral
        sidebarToggle.addEventListener("click", function() {
            // Alternar la clase 'sb-sidenav-collapsed' en el contenedor de la barra lateral
            layoutSidenav.classList.toggle("sb-sidenav-collapsed");
        });
    }
});


