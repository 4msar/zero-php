<?php
// Start the journey
define('ZERO_START', microtime(true));
define('ROOT_DIR', __DIR__);
define('DS', DIRECTORY_SEPARATOR);


// Load the autoloader
if (file_exists('./vendor/autoload.php')) {
    // Load the composer autoloader
    require_once './vendor/autoload.php';
} else {
    // Load the bootstrap files
    require_once './app/Core/functions.php';
    // Load the custom autoloader
    require_once './app/Core/Autoloader.php';

    // Register the autoloader
    Autoloader::setPath(ROOT_DIR . '/app/');
    spl_autoload_register('Autoloader::loader');
}

// Assemble the app parts
$GLOBALS['environment'] = env('env', 'local');
$appPort = env('app_port', '');
$url = env('app_url', 'http://localhost');

// Set Database Credentials
define('APP_NAME', env('app_name', 'LMS'));
define('APP_URL', $url . empty($appPort) ? '' : ':' . $appPort);
define('DB_HOST', env('db_host', 'localhost'));
define('DB_USERNAME', env('db_username', 'root'));
define('DB_PASSWORD', env('db_password', ''));
define('DB_NAME', env('db_name', 'app'));

// Bind the classes with alias
class_alias(App\Core\Auth::class, 'Auth');
class_alias(App\Core\Session::class, 'Session');
class_alias(App\Core\Router::class, 'Router');

// Put the key
App\Core\Session::start();
$router = new App\Core\Router();
