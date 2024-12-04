<?php
// Incluye los archivos necesarios
require_once __DIR__ . '../../config/database.php';
require_once 'alumno.php';

// Crea una nueva instancia de Database
$db = new Database();

// Llama al método findAlumnos() para obtener los datos de los alumnos
$result = Alumno::findAlumnos($db);

// Establece el encabezado de la respuesta como JSON
header('Content-Type: application/json');

// Devuelve los datos en formato JSON
echo $result;
?>



