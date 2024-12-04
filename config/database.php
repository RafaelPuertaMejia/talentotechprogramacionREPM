<?php

// Verificar si el archivo autoload existe antes de incluirlo para evitar errores fatales
$autoloadPath = __DIR__ . '/../../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath; // Cargar dependencias con Composer
} else {
    error_log('El archivo autoload.php no se encuentra en la ruta esperada: ' . $autoloadPath, 3, __DIR__ . "/error.log");
}

use Dotenv\Dotenv;

// Verificar si el archivo .env existe antes de intentar cargarlo
$dotenvPath = __DIR__ . '/../../';
if (file_exists($dotenvPath . '.env')) {
    // Cargar las variables de entorno desde el archivo .env
    $dotenv = Dotenv::createImmutable($dotenvPath);
    $dotenv->load();
} else {
    error_log('El archivo .env no se encuentra en la ruta esperada: ' . $dotenvPath, 3, __DIR__ . "/error.log");
}

class Database {
    private $host = 'localhost'; // Valores por defecto
    private $port = '5432';
    private $dbname = 'ProyectoPPIAmericana';
    private $user = 'postgres';
    private $password = '123';
    private $dbconn;

    public function __construct() {
        // Verificar si las variables de entorno están cargadas
        if (isset($_ENV['DB_HOST'], $_ENV['DB_PORT'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'])) {
            // Obtener las configuraciones desde las variables de entorno
            $this->host = $_ENV['DB_HOST'];
            $this->port = $_ENV['DB_PORT'];
            $this->dbname = $_ENV['DB_NAME'];
            $this->user = $_ENV['DB_USER'];
            $this->password = $_ENV['DB_PASSWORD'];
        }

        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false, // Deshabilitar la emulación de sentencias preparadas para mayor seguridad
        ];
        try {
            $this->dbconn = new PDO($dsn, $this->user, $this->password, $options);
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage(), 3, __DIR__ . "/error.log");
        }
    }

    public function getConnection() {
        return $this->dbconn;
    }

    public function closeConnection() {
        $this->dbconn = null;
    }

    public function testConnection() {
        if ($this->dbconn) {
            echo "Connected to the database successfully!";
        } else {
            echo "Connection failed!";
        }
    }
}

// Código de prueba para verificar la conectividad
$database = new Database();
$database->testConnection();
?>





