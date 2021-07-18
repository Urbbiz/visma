<?php

declare(strict_types =1);

namespace Syllable\Api;

use Syllable\PatternModel;

class Router
{
    private array $handlers;
    private $notFoundHandler;
    private const  METHOD_POST = 'POST';
    private const  METHOD_GET = 'GET';

    public function  get(string $path, $handler): void
    {
       $this->addHandler(self::METHOD_GET, $path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->addHandler( self::METHOD_POST, $path, $handler);

    }

    public  function  run()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
//            var_dump($_SERVER['REQUEST_METHOD']);
//        var_dump($requestPath);

        $method = $_SERVER['REQUEST_METHOD'];

        $callback = null;
        foreach ($this->handlers as $handler) {
            if ($handler['path'] === $requestPath && $method === $handler['method']) {  // check for finding correct handler
                $callback = $handler['handler'];
            }
        }

        if(!$callback) {
            header("HTTP/1.0 404 NOT FOUND");
            if(!empty($this->notFoundHandler)) {
                $callback = $this->notFoundHandler;
            }
        }

        call_user_func_array($callback, [
            array_merge($_GET, $_POST)
        ]);
    }


    public function addNotFoundHandler($handler): void
    {
        $this->notFoundHandler = $handler;
    }



    private  function  addHandler(string  $method, string $path, $handler): void
    {
        $this->handlers[$method . $path] =[
            'path' => $path,
            'method' => $method,
            'handler' => $handler,
        ];

    }

}