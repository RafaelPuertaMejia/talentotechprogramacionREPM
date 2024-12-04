<?php
require_once __DIR__ . '/../config/database.php';
class programa {

       // Crear un nuevo programa
    public static function create($db, $codigo_programa, $nombre_programa, $faculta, $ciudad) {
        $sql = "INSERT INTO programa (codigo_programa, nombre_programa, faculta, ciudad) VALUES (:codigo_programa, :nombre_programa, :faculta, :ciudad)";
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->bindParam(':codigo_programa', $codigo_programa);
        $stmt->bindParam(':nombre_programa', $nombre_programa);
        $stmt->bindParam(':faculta', $faculta);
        $stmt->bindParam(':ciudad', $ciudad);
        $stmt->execute();
    }

    // Actualizar un programa
    public static function update($db, $codigo_programa, $nombre_programa, $faculta, $ciudad) {
        $sql = "UPDATE programa SET nombre_programa = :nombre_programa, faculta = :faculta, ciudad = :ciudad WHERE codigo_programa = :codigo_programa";
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->bindParam(':nombre_programa', $nombre_programa);
        $stmt->bindParam(':faculta', $faculta);
        $stmt->bindParam(':ciudad', $ciudad);
        $stmt->bindParam(':codigo_programa', $codigo_programa);
        $stmt->execute();
    }

    // Eliminar un programa
    public static function delete($db, $codigo_programa) {
        $sql = "DELETE FROM programa WHERE codigo_programa = :codigo_programa";
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->bindParam(':codigo_programa', $codigo_programa);
        $stmt->execute();
    }

    // Leer todos los programas
    public static function readAll($db) {
        $sql = "SELECT * FROM programa";
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Leer un programa específico
    public static function read($db, $codigo_programa) {
        $sql = "SELECT * FROM programa WHERE codigo_programa = :codigo_programa";
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->bindParam(':codigo_programa', $codigo_programa);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Contar el número total de programas
    public static function count($db) {
        $sql = "SELECT COUNT(*) FROM programa";
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Verificar si un programa existe
    public static function exists($db, $codigo_programa) {
        $sql = "SELECT 1 FROM programa WHERE codigo_programa = :codigo_programa";
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->bindParam(':codigo_programa', $codigo_programa);
        $stmt->execute();
        return $stmt->fetchColumn() === 1;
    }
}

   


function test_programa($db) {
    // Crear un nuevo programa
   

    // Leer todos los programas
    $programas = programa::readAll($db);
    print_r($programas);

}
// Descomenta la siguiente línea para ejecutar las pruebas
$db = new Database();            
test_programa($db);
?>
