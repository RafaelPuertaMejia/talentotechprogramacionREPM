<?php
// Ajusta la ruta para incluir el controlador
require_once __DIR__ . '/../controllers/AlumnosController.php';

// Crear instancia del controlador
$alumnosController = new AlumnosController();

// Datos de prueba
$codigo_proyecto = 240123021; // Reemplaza con un código de proyecto válido existente en tu base de datos
$url_video = 'https://www.youtube.com/watch?v=ejemploVideo';
$url_documento = 'https://drive.google.com/file/d/ejemploDocumento/view';
$url_presentacion = 'https://drive.google.com/file/d/ejemploPresentacion/view';

// Llamar al método para actualizar la URL del video
$resultadoVideo = $alumnosController->updateUrlVideo($codigo_proyecto, $url_video);
if ($resultadoVideo) {
    echo "La URL del video se actualizó correctamente.\n";
} else {
    echo "Error al actualizar la URL del video.\n";
}

// Llamar al método para actualizar la URL del documento
$resultadoDocumento = $alumnosController->updateUrlDocumento($codigo_proyecto, $url_documento);
if ($resultadoDocumento) {
    echo "La URL del documento se actualizó correctamente.\n";
} else {
    echo "Error al actualizar la URL del documento.\n";
}

// Llamar al método para actualizar la URL de la presentación
$resultadoPresentacion = $alumnosController->updateUrlPresentacion($codigo_proyecto, $url_presentacion);
if ($resultadoPresentacion) {
    echo "La URL de la presentación se actualizó correctamente.\n";
} else {
    echo "Error al actualizar la URL de la presentación.\n";
}

