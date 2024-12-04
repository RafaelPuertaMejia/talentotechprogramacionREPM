<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene el rol adecuado
function checkUserSession($requiredRole = null) {
    if (!isset($_SESSION['user_id'])) {
        // No hay sesión, redirigir al login
        header('Location: ../auth/login.php');
        exit;
    }

    if ($requiredRole && (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $requiredRole)) {
        // El usuario no tiene el rol requerido
        header('Location: ../auth/unauthorized.php');
        exit;
    }
}

// Ejemplo de uso:
// checkUserSession('docente'); // Verifica que el usuario sea un docente
?>
