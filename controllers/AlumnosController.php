<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/path.php';
require_once CONFIG_PATH . 'database.php';

class AlumnosController {

    private $model;
    private $views;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function listar() {
        $data = $this->model->selectUsuarios();
        $this->views->getView($this, "listar", $data);
    }

    public function listarProyectos() {
        $nombre_usuario = $_SESSION['nombre'];
        $data = $this->model->selectProyectos($nombre_usuario);
        $this->views->getView($this, "listarProyectos", $data);
    }

    public function selectProyectosAlumnos($dni_usuario) {
        $sql = "SELECT p.codigo_proyecto, p.nombre_proyecto, p.tipo_proyecto AS Tipo, 
                       d.nombre_docente AS docente_asesor, sem.semestre AS semestre_proyecto
                FROM proyectos p
                JOIN semestre_proyecto sem ON sem.codigo_producto_semestre = p.codigo_proyecto
                JOIN docentes d ON p.docente_asesor = d.dni_docente
                WHERE p.codigo_proyecto IN (
                    SELECT dp.codigo_proyecto_detalle
                    FROM detalle_proyectos dp
                    WHERE dp.dni_alumno_dp = :dni_usuario)";
        $query = $this->db->prepare($sql);
        $query->bindParam(':dni_usuario', $dni_usuario, PDO::PARAM_STR);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function NameAlumnos($codigo_proyecto) {
        $sql = "SELECT a.nombre_alumno AS integrante 
                FROM alumnos a 
                JOIN detalle_proyectos dp ON a.dni_alumno = dp.dni_alumno_dp 
                WHERE dp.codigo_proyecto_detalle = :codigo_proyecto";
        $query = $this->db->prepare($sql);
        $query->bindParam(':codigo_proyecto', $codigo_proyecto, PDO::PARAM_STR);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUrlVideo($codigo_proyecto, $url_video) {
        try {
            $stmt = $this->db->prepare('
                UPDATE documentos_proyectos
                SET url_video = :url_video
                WHERE codigo_proyecto_document = :codigo_proyecto
            ');
            $stmt->bindParam(':url_video', $url_video, PDO::PARAM_STR);
            $stmt->bindParam(':codigo_proyecto', $codigo_proyecto, PDO::PARAM_INT);
            return $stmt->execute(); // Retorna true si la ejecución fue exitosa
        } catch (PDOException $e) {
            error_log("Error al actualizar la URL del video: " . $e->getMessage());
            return false;
        }
    }

    public function updateUrlDocumento($codigo_proyecto, $url_documento) {
        try {
            $stmt = $this->db->prepare('
                UPDATE documentos_proyectos
                SET url_documento = :url_documento
                WHERE codigo_proyecto_document = :codigo_proyecto
            ');
            $stmt->bindParam(':url_documento', $url_documento, PDO::PARAM_STR);
            $stmt->bindParam(':codigo_proyecto', $codigo_proyecto, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar la URL del documento: " . $e->getMessage());
            return false;
        }
    }

    public function updateUrlPresentacion($codigo_proyecto, $url_presentacion) {
        try {
            $stmt = $this->db->prepare('
                UPDATE documentos_proyectos
                SET url_presentacionpp = :url_presentacionpp
                WHERE codigo_proyecto_document = :codigo_proyecto
            ');
            $stmt->bindParam(':url_presentacionpp', $url_presentacion, PDO::PARAM_STR);
            $stmt->bindParam(':codigo_proyecto', $codigo_proyecto, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar la URL de la presentación: " . $e->getMessage());
            return false;
        }
    }
}

?>
