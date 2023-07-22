<?php

namespace App\Core\CLI;

class Command
{
    public function listener()
    {
        $command = $this->getCommand();
        global $argv;

        if (method_exists($this, $command)) {
            $this->{$command}($argv[2] ?? null);
        } else {
            $this->help();
        }
    }

    private function getCommand()
    {
        global $argv;

        if (str_contains($argv[1], ':')) {
            [$namespace, $method] = explode(':', $argv[1] ?? '');
        } else {
            $namespace = '';
            $method = $argv[1] ?? '';
        }

        $cleanName = strtolower($namespace) . ucfirst($method);

        return $cleanName ?? 'help';
    }

    /**
     * ========================================
     * COMMANDS METHODS
     * ========================================
     */

    public function help()
    {
        echo Stubs::help();
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
        $path = ROOT_DIR . '/app/Controllers/' . $name . 'Controller.php';
        if (file_exists($path)) {
            echo "Controller already exists!\n";
            exit(1);
        }
        file_put_contents($path, Stubs::controller($name));
        echo "Controller created successfully!\n";
    }

    public function makeModel($name)
    {
        $name = ucfirst($name);
        $path = ROOT_DIR . '/app/Models/' . $name . '.php';
        if (file_exists($path)) {
            echo "Model already exists!\n";
            exit(1);
        }
        file_put_contents($path, Stubs::model($name));
        echo "Model created successfully!\n";
    }

    function makeView($name)
    {
        $path = ROOT_DIR . '/views/' . strtolower($name) . '.php';
        if (file_exists($path)) {
            echo "View already exists!\n";
            exit(1);
        }
        file_put_contents($path, Stubs::view($name));
        echo "View created successfully!\n";
    }
}
