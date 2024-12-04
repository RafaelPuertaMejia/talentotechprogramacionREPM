<?php
require_once __DIR__ . '../../config/database.php'; // Incluye el archivo que contiene la definición de la clase Database
require_once 'alumno.php'; // Incluye el archivo que contiene la definición de la clase Alumno

$db = new Database(); // Crea una nueva instancia de Database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge los datos enviados desde el formulario
    $dni = $_POST['dni_alumno'];
    $nombre = $_POST['nombre_alumno'];
    $programa = $_POST['programa'];
    $email = $_POST['email_alumno'];
    $celular = $_POST['celular_alumno'];
    $password = $_POST['password_alumno'];

    // Llama a los métodos del CRUD según sea necesario
    // Por ejemplo, para crear un nuevo alumno:
    Alumno::create($db, $dni, $nombre, $email, $password);

    // O para actualizar un alumno existente:
    // Alumno::update($db, $dni, $nombre, $email, $password);

    // O para eliminar un alumno:
    // Alumno::delete($db, $dni);
}
?>
