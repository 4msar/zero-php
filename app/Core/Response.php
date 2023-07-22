<?php

namespace App\Core;

/**
 * Response
 */
class Response
{
    /**
     * Response headers
     * @var array
     */
    protected $headers = [];

    /**
     * The final content should be load
     * @var string
     */
    protected $content = "";

    /**
     * Status of the response
     * @var integer
     */
    protected $status = 200;

    /**
     * Load the instance
     * @param string  $content 
     * @param integer $status  
     */
    function __construct($content = "", $status = 200)
    {
        $this->content = $content;
        $this->status = $status;
    }

    /**
     * Set the content
     * @param string $content 
     * @return $this
     */
    public function setContent($content = '')
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Set the content
     * @param integer $status
     * @return $this
     */
    public function setStatus($status = 200)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Show the view 
     * @param string $name
     * @param array $data
     * @param boolean $getInstance
     * @return View::show
     */
    public function view($name, $data = [], $getInstance = false)
    {
        if ($getInstance) {
            return view();
        }
        return view()->show($name, $data);
    }

    /**
     * Send the response the client
     * @param  string  $content 
     * @param  integer $status  
     * @return string
     */
    public function send($content = "", $status = 200)
    {
        if ($content) {
            $this->content = $content;
        }
        if ($status) {
            $this->status = $status;
        }

        http_response_code($this->status);
        return $this->content;
    }

    /**
     * Send JSON response to the client
     * @param  array   $data   
     * @param  integer $status 
     * @return string
     */
    public function json($data = [], $status = 200)
    {
        if ($data) {
            $this->content = $data;
        }
        if ($status) {
            $this->status = $status;
        }

        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json');

        http_response_code($this->status);
        return json_encode($this->content, JSON_PRETTY_PRINT);
    }

    /**
     * Redirect to the path
     * @param  string $to 
     * @return void
     */
    public function redirect($to = '/')
    {
        $path = url($to);
        header("Location: {$path}", true);
        exit();
    }

    /**
     * String of the instance
     * @return string 
     */
    public function __toString()
    {
        if (is_array($this->content)) {
            return $this->json();
        }
        return $this->send();
    }
}
