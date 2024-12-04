<?php
session_start();

// Verificar si el usuario ha iniciado sesión correctamente y tiene el rol de 'docente'
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'docente') {
    session_unset();
    session_destroy();
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../config/path.php';
require_once CONFIG_PATH . 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // El formulario ha sido enviado, llama al método NotaVideoInsert
    // Calcula la nota a partir de los ítems evaluados
    $nota = 0;
    $nota += $_POST['aspecto1'] == 'cumple' ? 1 : 0;
    $nota += $_POST['aspecto2'] == 'cumple' ? 1 : 0;
    $nota += $_POST['aspecto3'] == 'cumple' ? 1 : 0;
    $nota += $_POST['aspecto4'] == 'cumple' ? 1 : 0;
    $nota += $_POST['aspecto5'] == 'cumple' ? 1 : 0;
    $codProyDoc = $_POST['codProyDoc'];
    $semestre = $_POST['semestre'];

    $docenteController = new DocenteController();
    $result = $docenteController->NotaVideoInsert($nota, $codProyDoc, $semestre);
    if ($result === false) {
        // Si NotaVideoInsert retorna false, llama a NotaVideoUpgrade
        $result = $docenteController->NotaVideoUpgrade($nota, $codProyDoc, $semestre);
        if ($result === false) {
            echo 'Ocurrió un error al actualizar el registro.';
        } else {
            echo 'El registro fue actualizado con éxito.';
        }
    } else {
        echo 'El registro fue guardado con éxito.';
    }
}

class DocenteController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getVideoUrl($codigo_proyecto_document) {
        $sql = "SELECT url_video FROM detalle_proyecto WHERE codigo_proyecto_document = :codigo_proyecto_document";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['codigo_proyecto_document' => $codigo_proyecto_document]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['url_video'];
    }

    public function getProyectos($dni) {
        $sql = "SELECT p.codigo_proyecto, p.nombre_proyecto, d.url_video, d.url_documento, d.url_presentacionpp, d.semestre 
                FROM proyectos p 
                JOIN documentos_proyectos d ON p.codigo_proyecto = d.codigo_proyecto_document
                WHERE p.docente_asesor = :dni";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['dni' => $dni]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function esEvaluado($codProyDoc) {
        $sql = "SELECT * FROM detalle_evaluacion_videos WHERE codigo_proyecto_document = :codigo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['codigo' => $codProyDoc]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retorna true si el proyecto ha sido evaluado, false de lo contrario
        return $result != false;
    }

   public function NotaVideoInsert($Nota, $codProyDoc, $semestre) {
        try {
            $sql = "INSERT INTO public.detalle_evaluacion_videos(codigo_proyecto_document, nota_documento_video, semestre_proyecto)
                    VALUES (:codigo, :nota, :semestre)";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(['nota' => $Nota, 'codigo' => $codProyDoc, 'semestre' => $semestre]);

            // Si la inserción fue exitosa, establece un mensaje de confirmación
            if ($result) {
                $_SESSION['message'] = 'El registro fue guardado con éxito.';
                return true;
            }
        } catch (PDOException $e) {
            // Si ocurre una excepción, verifica si es debido a una violación de la restricción de unicidad
            if ($e->getCode() == 23505) {
                // Si es así, retorna false para indicar que el registro ya existe
                return false;
            } else {
                // Si no, lanza la excepción para que pueda ser manejada por el código que llamó a este método
                throw $e;
            }
        }
    }

    // Método para actualizar la nota
    public function NotaVideoUpgrade($Nota, $codProyDoc) {
        try {
            $sql = "UPDATE detalle_evaluacion_videos SET nota_documento_video = :nota WHERE codigo_proyecto_document = :codigo";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(['nota' => $Nota, 'codigo' => $codProyDoc]);

            // Si la actualización fue exitosa, devuelve un mensaje de confirmación
            if ($result && $stmt->rowCount() > 0) {
                $_SESSION['message'] = 'El registro fue actualizado con éxito.';
                return true;
            } else {
                // Si la actualización no fue exitosa, devuelve un mensaje de error
                return 'Ocurrió un error al actualizar el registro.';
            }
        } catch (PDOException $e) {
            // Si ocurre una excepción, devuelve un mensaje de error
            return 'Error: ' . $e->getMessage();
        }
    }

}
?>


<!--//$docenteController = new DocenteController();
//$proyectos = $docenteController->getProyectos(98662503);
//print_r($proyectos);
//$respuesta=$docenteController->NotaVideoInsert(4, 240123021,4);
//print_r($respuesta)-->




