<?php

require_once __DIR__ . '/../config/database.php';

class Alumno {

    private $conn;
    private $table_name = "alumnos";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Método para buscar un alumno por su correo electrónico.
     * @param string $email Correo electrónico del alumno.
     * @return array|null Retorna un array con los datos del alumno si se encuentra, null en caso contrario.
     */
    public function findByEmail($email) {
        $query = "SELECT * 
                  FROM " . $this->table_name . " 
                  WHERE email_alumno = :email LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("Resultado de la consulta en Alumno: " . print_r($data, true));  // Registrar el resultado
            return $data ? $data : null;
        }

        return null;
    }

    public function selectAlumnos() {
        $sql = "SELECT * FROM alumnos";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectAlumnoPorId(int $dni_alumno) {
        $sql = "SELECT * FROM alumnos WHERE dni_alumno = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$dni_alumno]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertarAlumno(string $nombre_alumno, string $programa, string $email_alumno, string $celular_alumno, string $password_alumno) {
        $sql = "INSERT INTO alumnos (nombre_alumno, programa, email_alumno, celular_alumno, password_alumno) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->getConnection()->prepare($sql);
        $hashed_password = password_hash($password_alumno, PASSWORD_DEFAULT);
        $stmt->execute([$nombre_alumno, $programa, $email_alumno, $celular_alumno, $hashed_password]);
    }

    public function editarAlumno(int $dni_alumno, string $nombre_alumno, string $programa, string $email_alumno, string $celular_alumno, string $password_alumno) {
        $sql = "UPDATE alumnos SET nombre_alumno = ?, programa = ?, email_alumno = ?, celular_alumno = ?, password_alumno = ? WHERE dni_alumno = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $hashed_password = password_hash($password_alumno, PASSWORD_DEFAULT);
        $stmt->execute([$nombre_alumno, $programa, $email_alumno, $celular_alumno, $hashed_password, $dni_alumno]);
    }

    public function eliminarAlumno(int $dni_alumno) {
        $sql = "DELETE FROM alumnos WHERE dni_alumno = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$dni_alumno]);
    }

    public function selectAlumnoPorUsuarioYClave(string $email, string $password) {
        $sql = "SELECT * FROM alumnos WHERE email_alumno = ? AND password_alumno = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$email, $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function reingresarAlumno(int $dni_alumno) {
        $sql = "UPDATE alumnos SET estado = 1 WHERE dni_alumno = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$dni_alumno]);
    }

    public function cambiarPassAlumno(string $clave, int $dni_alumno) {
        $sql = "UPDATE alumnos SET password_alumno = ? WHERE dni_alumno = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $hashed_password = password_hash($clave, PASSWORD_DEFAULT);
        $stmt->execute([$hashed_password, $dni_alumno]);
    }

    public function testConsulta() {
        $sql = "SELECT * FROM alumnos LIMIT 5";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ... Método ejecutar y cualquier otro método adicional necesario ...




    public function ejecutar() {
        // Buscar alumno por email y mostrar resultados
        $resultado = $this->findByEmail("pepito@americana.edu.co");
        if ($resultado) {
            echo "DNI: " . $resultado['dni_alumno'] . "<br>";
            echo "Nombre: " . $resultado['nombre_alumno'] . "<br>";
            // ... Mostrar el resto de los datos ...
        } else {
            echo "No se encontraron resultados para ese correo electrónico.<br>";
        }

        // Datos del alumno a actualizar
        $dni_alumno = 99999999;
        $nombre_alumno = "Pepito Perez";
        $programa = "8082";
        $email_alumno = "pepito@americana.edu.co";
        $celular_alumno = "3001231122";
        $password_alumno = '123456'; // La nueva contraseña sin hashear
        // Actualizar alumno
        $this->editarAlumno($dni_alumno, $nombre_alumno, $programa, $email_alumno, $celular_alumno, $password_alumno);
        echo "El alumno con DNI $dni_alumno ha sido actualizado correctamente.<br>";
    }
}

// Para ejecutar los métodos al instanciar la clase Alumno
//$alumnos = new Alumno();
//$alumnos->ejecutar();
?>



