<?php
class Usuarios extends Controllers {

    public function __construct() {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url());
        }
        parent::__construct();
    }

       public function subirContenido() {
        // Configuración de la base de datos
        $host = 'localhost';
        $dbname = 'nombre_de_tu_base_de_datos';
        $user = 'tu_usuario';
        $password = 'tu_contraseña';

        // Crear conexión
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);

        // Verificar conexión
        if (!$conn) {
            echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
            exit;
        }

        // Obtener los datos del cuerpo de la solicitud
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['url']) && isset($data['type'])) {
            $url = $data['url'];
            $type = $data['type'];
            
            // Crear la consulta SQL dependiendo del tipo de contenido
            $sql = "";
            if ($type === 'video') {
                $sql = "INSERT INTO documentos_proyectos (codigo_proyecto_document, url_documento, url_video, url_presentacionpp, semestre) 
                        VALUES (nextval('documentos_proyectos_codigo_proyecto_document_seq'), '', :url, '', 1)";
            } elseif ($type === 'ppt') {
                $sql = "INSERT INTO documentos_proyectos (codigo_proyecto_document, url_documento, url_video, url_presentacionpp, semestre) 
                        VALUES (nextval('documentos_proyectos_codigo_proyecto_document_seq'), '', '', :url, 1)";
            } elseif ($type === 'docx') {
                $sql = "INSERT INTO documentos_proyectos (codigo_proyecto_document, url_documento, url_video, url_presentacionpp, semestre) 
                        VALUES (nextval('documentos_proyectos_codigo_proyecto_document_seq'), :url, '', '', 1)";
            }

            // Preparar y ejecutar la consulta
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':url', $url);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => ucfirst($type) . ' subido exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al subir el ' . $type]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Datos no válidos']);
        }
    }
}
?>


