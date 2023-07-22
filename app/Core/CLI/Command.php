<?php

namespace App\Core\CLI;

class Command
{
    public function listener()
    {
        $command = $this->getCommand();

        if (method_exists($this, $command)) {
            global $argv;
            $this->{$command}($argv[2] ?? null);
        } else {
            $this->help();
        }
    }

    public function getCommand()
    {
        global $argv;

        return $argv[1] ?? 'help';
    }

    public function help()
    {
        echo <<<HELP
Usage: php zero [command]

Available commands:
  zero serve
  zero make:controller [name]
  zero make:model [name]

---------------------------------------------
HELP;

        exit(1);
    }

    public function serve()
    {
        $host = env('APP_HOST', 'localhost');
        $port = env('APP_PORT', 8900);

        $command = sprintf('php -S %s:%d -t .', $host, $port);

        passthru($command);
    }

    public function makeController($name)
    {

        $name = ucfirst($name);

        $controller = <<<CONTROLLER
<?php

namespace App\Controllers;

use App\Core\Controller;

class {$name}Controller extends Controller
{
    public function index()
    {
        //
    }
}

CONTROLLER;

        $path = ROOT_DIR . '/app/Controllers/' . $name . 'Controller.php';

        if (file_exists($path)) {
            echo "Controller already exists!\n";
            exit(1);
        }

        file_put_contents($path, $controller);

        echo "Controller created successfully!\n";
    }
}
