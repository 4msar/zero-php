<?php

namespace App\Core;

use App\Core\Request;

class Router
{
    /**
     * The GET routes
     * @var array
     */
    private $get = [];

    /**
     * The POST routes
     * @var array
     */
    private $post = [];

    /**
     * The ANY routes
     * @var array
     */
    private $any = [];

    /**
     * The Input::class instance
     * @var instance of Input
     */
    private $request;

    /**
     * Supported HTTP Method
     * @var array
     */
    private $supportedHttpMethods = ["GET", "POST", "ANY"];

    /**
     * Instance of this class
     */
    function __construct()
    {
        $this->request = new Request();
    }

    /**
     * Call a function and load the callback
     * @param  string $name 
     * @param  callable|array $args 
     * @return mixed
     */
    function __call($name, $args)
    {
        list($route, $method) = $args;

        if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {
            $this->invalidMethodHandler();
            die();
        }
        if ($name === 'any') {
            foreach ($this->supportedHttpMethods as $httpMethod) {
                $this->{strtolower($httpMethod)}[$this->formatRoute($route)] = $method;
            }
            return;
        }
        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    /**
     * Removes trailing forward slashes from the right of the route.
     * @param route (string)
     */
    private function formatRoute($route)
    {
        $result = rtrim($route, '/');
        if ($result === '') {
            return '/';
        }
        return $result;
    }

    /**
     * Show the unsupported method error
     * @return void 
     */
    private function invalidMethodHandler()
    {
        $protocol = strtoupper($this->request->protocol());
        header("{$protocol} 405 Method Not Allowed", true, 405);
        echo app(View::class)->show404(405, "Method Not Allowed");
        exit();
        // throw new Error("{$this->request->protocol()} 405 Method Not Allowed");
    }

    /**
     * Show the not found error
     * @return void 
     */
    private function defaultRequestHandler()
    {
        $protocol = strtoupper($this->request->protocol());
        header("{$protocol} 404 Not Found", true, 404);
        echo app(View::class)->show404();
        exit();
        // throw new Error("{$this->request->protocol()} 404 Not Found");
    }

    /**
     * Get the callback from routes list
     * @return array|null 
     */
    public function getCallback()
    {
        if (!isset($this->{strtolower($this->request->method(false))})) {
            return;
        }

        $methodDictionary = $this->{strtolower($this->request->method(false))};
        $formatedRoute = $this->formatRoute($this->request->path());


        foreach ($methodDictionary as $path => $callback) {
            $pattern = "@^" . preg_replace('/\\\:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', preg_quote($path)) . "$@D";

            $matches = [];
            if (preg_match($pattern, $formatedRoute, $matches)) {
                array_shift($matches);
                return [$callback, $matches];
            }
        }
        return null;
    }

    /**
     * Resolves a route
     * @return void
     */
    function resolve()
    {
        list($callback, $params) = $this->getCallback();

        if (is_null($callback)) {
            $this->defaultRequestHandler();
            return;
        }

        if (is_array($callback)) {
            list($object, $method) = $callback;
            if (!is_object($object)) {
                $object = new $object();
            }
            if (method_exists($object, $method)) {
                call_user_func_array(
                    [$object, $method],
                    array_merge([$this->request], $params)
                );
            } else {
                $this->defaultRequestHandler();
            }
            return true;
        }

        $response = call_user_func_array(
            $callback,
            array_merge([$this->request], $params)
        );
        if ($response) {
            if (is_array($response) || is_object($response)) {
                header('Content-Type: application/json');
                echo json_encode($response, JSON_PRETTY_PRINT);
                return;
            }
            echo $response;
        }
    }

    /**
     * Invoke the router
     */
    function __destruct()
    {
        $this->resolve();
    }
}
