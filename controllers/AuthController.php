<?php

require_once __DIR__ . '/../config/path.php';
require_once CONFIG_PATH . 'database.php';
require_once MODELS_PATH . 'Alumno.php';
require_once MODELS_PATH . 'Docente.php';

class AuthController {

    private $alumnoModel;
    private $docenteModel;
    private $sessionTimeout = 1800; // Tiempo de inactividad permitido en segundos (30 minutos)

    public function __construct($db) {
        // Inicializar los modelos con la conexión a la base de datos
        $this->alumnoModel = new Alumno($db);
        $this->docenteModel = new Docente($db);

        // Iniciar sesión si no está activa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            error_log("Iniciando sesión en AuthController.php");
        }
    }
public function login($email, $password) {
    // Sanitizar el correo electrónico
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = trim($password);

    // Buscar al usuario en la base de datos
    $user = $this->findUserByEmail($email);

    if ($user && $this->verifyPassword($password, isset($user['password_alumno']) ? $user['password_alumno'] : (isset($user['password_docente']) ? $user['password_docente'] : ''))) {
        // Comprobar el estado de la sesión
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Regenerar el ID de sesión para prevenir fijación de sesión
        session_regenerate_id(true);

        // Guardar información del usuario en la sesión
        if (isset($user['dni_alumno'])) {
            $_SESSION['user_id'] = $user['dni_alumno'];
            $_SESSION['user_role'] = 'alumno';
            $_SESSION['user_name'] = $user['nombre_alumno'];
        } elseif (isset($user['dni_docente'])) {
            $_SESSION['user_id'] = $user['dni_docente'];
            $_SESSION['user_role'] = $user['rol_docente'];
            $_SESSION['user_name'] = $user['nombre_docente'];
        }

        $_SESSION['last_activity'] = time(); // Control de tiempo de inactividad
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

        // Registrar información de la sesión para verificar
        error_log("Datos de sesión después del login: " . json_encode($_SESSION));

        // Redirigir al dashboard correspondiente
        $this->redirectToDashboard($_SESSION['user_role']);
    } else {
        $_SESSION['error_message'] = 'Correo electrónico o contraseña incorrectos.';
        header('Location: ' . VIEWS_URL . 'auth/login.php?error=' . urlencode($_SESSION['error_message']));
        exit();
    }
}

    

    private function findUserByEmail($email) {
        // Sanitizar nuevamente para mayor seguridad
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Buscar al usuario en las tablas de Alumno y Docente
        $user = $this->alumnoModel->findByEmail($email);
        if ($user) {
            $user['rol'] = 'alumno';
        } else {
            $user = $this->docenteModel->findByEmail($email);
            if ($user) {
                $user['rol'] = 'docente';
            }
        }
        return $user;
    }

    private function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }

    private function redirectToDashboard($role) {
        // Redirigir al dashboard correspondiente según el rol del usuario
        switch ($role) {
            case 'alumno':
                header('Location: ' . VIEWS_URL . 'Alumnos/dashboardAlumnos.php');
                break;
            case 'docente':
                header('Location: ' . VIEWS_URL . 'Docentes/dashboardDocentes.php');
                break;
            case 'administrador':
                header('Location: ' . VIEWS_URL . 'admin/dashboardAdmin.php');
                break;
            default:
                header('Location: ' . VIEWS_URL . 'auth/login.php?error=' . urlencode('Rol no reconocido.'));
                break;
        }
        exit();
    }
}

?>




