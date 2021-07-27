<?php

namespace Syllable\Api;

use Syllable\Controllers\WordsController;



class RoutesDefiner
{
    private Router $router;
    private WordsController $wordsController;

    public function __construct(Router $router, WordsController $wordsController)
    {

        $this->router = $router;
        $this->wordsController = $wordsController;
    }

    public function defineRoutes()
    {


        $this->router->get('/visma-task/', function () {
            echo 'Home page is here';
        });


        $this->router->get('/visma-task/patterns', function () {

            $this->wordsController->getPatterns();
        });


        $this->router->get('/visma-task/words', function () {

            $this->wordsController->getWords();
        });


        $this->router->post('/visma-task/words', function () {
            $body = (array)json_decode(file_get_contents('php://input'), true);

            $this->wordsController->postWord($body["givenWord"]);
        });

        $this->router->put('/visma-task/words', function () {
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $uri = explode('/', $uri);
            $body = (array)json_decode(file_get_contents('php://input'), true);

            if ($uri[2] !== 'words') {
                header("HTTP/1.1 404 Not Found");
                exit();
            }
            $givenWord = null;
            if (isset($uri[3])) {
                $givenWord = $uri[3];
            }

            if ($this->wordsController->putWord($givenWord, $body["editedWord"]) == false) {
                header("HTTP/1.1 404 Not Found");
            }

        });


        $this->router->delete('/visma-task/words', function () {
  //word/Marius
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $uri = explode('/', $uri);

            if ($uri[2] !== 'words') {
                header("HTTP/1.1 404 Not Found");
//                exit();
            }
            $givenWord = null;
            if (isset($uri[3])) {
                $givenWord = $uri[3];
            }

            $this->wordsController->deleteWord($givenWord);
        });

        $this->router->addNotFoundHandler(function () {
            echo 'not found';
        });
    }
}
