<?php
    require_once('config.php');
    require_once __DIR__ . '/vendor/autoload.php';

    use Dotenv\Dotenv;

    // Detectar el entorno actual
    $envFile = getenv('APP_ENV') ?: 'desarrollo';

    // Validar el entorno
    $validEnvs = ['desarrollo', 'produccion'];
    if (!in_array($envFile, $validEnvs)) {
        die("Entorno no válido: $envFile");
    }

    // Cargar el archivo.env según el entorno
    $dotenv = Dotenv::createImmutable(__DIR__, ".env.$envFile");
    $dotenv->load();

    // Definir las variables cargadas desde .env
    define('BASE_URL', $_ENV['BASE_URL']);

    // Definir las variables de acceso a la BD
    define('DB_HOST', $_ENV['DB_HOST']);
    define('DB_USER', $_ENV['DB_USER']);
    define('DB_PASS', $_ENV['DB_PASSWORD']);
    define('DB_NAME', $_ENV['DB_NAME']);

    require_once('./controllers/Autoload.php');

    $autoload = new Autoload();

    $app = new Router();
?>