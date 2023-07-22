<?php

namespace App\Core\CLI;

final class Stubs
{

    public static function help()
    {
        return <<<HELP
Usage: php zero [command]

Available commands:
  zero serve
  zero make:controller [name]
  zero make:model [name]

---------------------------------------------\n
HELP;
    }

    public static function controller($name)
    {
        return <<<CONTROLLER
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
    }

    public static function model($name)
    {
        return <<<MODEL
<?php

namespace App\Models;

use App\Core\Model;

class {$name} extends Model
{
    protected \$table = '{$name}s';
}

MODEL;
    }

    public static function view($name)
    {
        return <<<VIEW
        // Path: views/{$name}.php
        VIEW;
    }
}
