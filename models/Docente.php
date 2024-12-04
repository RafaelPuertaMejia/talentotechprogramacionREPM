<?php

class Docente {

    private $conn;
    private $table_name = "docentes";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Método para buscar un docente por su correo electrónico.
     * @param string $email Correo electrónico del docente.
     * @return array|null Retorna un array con los datos del docente si se encuentra, null en caso contrario.
     */
    public function findByEmail($email) {
        $query = "SELECT * 
                  FROM " . $this->table_name . " 
                  WHERE email_docente = :email LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("Resultado de la consulta en Docente: " . print_r($data, true));  // Registrar el resultado
            return $data ? $data : null;
        }

        return null;
    }

    /**
     * Método para verificar si un docente existe en la base de datos.
     * @param string $dni DNI del docente.
     * @return bool Retorna true si el docente existe, false en caso contrario.
     */
    public function docenteExists($dni) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE dni_docente = :dni";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':dni', $dni);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['count'] > 0;
    }

    /**
     * Método para crear un nuevo docente en la base de datos.
     * @param array $data Datos del nuevo docente.
     * @return bool Retorna true si el docente se creó correctamente, false en caso contrario.
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " (dni_docente, nombre_docente, email_docente, password_docente, rol_docente)
                  VALUES (:dni, :nombre, :email, :password, :rol)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':dni', $data['dni']);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']); // Asegúrate de que la contraseña esté hasheada antes de pasarla
        $stmt->bindParam(':rol', $data['rol']);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Otros métodos según sea necesario...
}

// Verificar que todas las instancias de Docente se crean correctamente con el argumento de conexión
// Por ejemplo:
// $database = new Database();
// $db = $database->getConnection();
// $docente = new Docente($db); // Asegúrate de que siempre se pase $db
?>
