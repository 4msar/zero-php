<?php

namespace App\Core;

/**
 * View the page content
 */
class View extends Base
{
    /**
     * The page title
     * @var string
     */
    public $title = "App";

    /**
     * Set the page title
     * @param string $title 
     */
    public function setTitle($title = "")
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Load the page header
     * @return mixed 
     */
    public function getHeader()
    {
        return $this->load('layouts.header', ['title' => $this->title]);
    }

    /**
     * Load the page footer
     * @return mixed 
     */
    public function getFooter()
    {
        return $this->load('layouts.footer', ['title' => $this->title]);
    }

    /**
     * Show the page content
     * @param  string $file 
     * @param  array  $data 
     * @return void
     */
    public function show($file, $data = [])
    {
        $this->getHeader();

        $this->load($file, $data);

        $this->getFooter();
    }

    /**
     * Check the view file is exist or not
     * @param  string  $file 
     * @return boolean 
     */
    public function has($file)
    {
        $parsedFile = str_replace(".", DS, $file);
        $path = ROOT_DIR . DS . 'views' . DS . $parsedFile . '.php';
        if (file_exists($path)) {
            return true;
        }
        return false;
    }

    /**
     * Load the view file
     * @param  string $file 
     * @param  array  $data 
     * @return mixed       
     */
    public function load($file, $data = [])
    {
        if (!is_array($data)) {
            $data = [$data];
        }
        extract($data);

        $parsedFile = str_replace(".", DS, $file);
        $path = ROOT_DIR . DS . 'views' . DS . $parsedFile . '.php';
        if (file_exists($path)) {
            return include_once $path;
        }
        return;
    }

    /**
     * Show the 404 not found page
     * @param  integer $code    
     * @param  string  $message 
     * @return mixed
     */
    public function show404($code = 404, $message = "Not Found")
    {
        if ($this->has('404')) {
            $this->load('404');
            return;
        }
        return '<center style="display: flex; flex-direction: column; justify-content: center;align-items: center;height: 100vh;"><h2>' . $code . '</h2><h1>' . $message . '</h1></center>';
    }
}
