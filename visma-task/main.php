<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';
//require_once (__DIR__ .'/Syllable/Api/Router.php');


use  Syllable\App\Application;
use  Syllable\Api\Router;
use  Syllable\Controllers\WordsController;
use  Syllable\Controllers\Controller;
use Syllable\Database\DatabaseManager;
use Syllable\Api\Routes;



$router = new Router();

$aboutPage = new WordsController();


$router->get('/visma-task/', function () {
    echo 'Home page is here';
});



$router->get('/visma-task/patterns', function () {
//    echo 'About page';

//   Controller::createView();
    $getData = new WordsController();
//    $getData->arMatai();
    $getData->getPatterns();
});

$router->get('/visma-task/words', function () {
//    echo 'About page';


    $getData = new WordsController();

    $getData->getWords();
});


$router->post('/visma-task/words', function ()
{
    $body =(array) json_decode(file_get_contents('php://input'), TRUE );

    $wordsController = new WordsController();
//    var_dump($body);
    $wordsController->postWord($body["givenWord"]);

});

$router->put('/visma-task/words', function ()  //word/Marius
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode('/', $uri);
    $body =(array) json_decode(file_get_contents('php://input'), TRUE );

    if ($uri[2] !== 'words') {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
    $givenWord= null;
    if (isset($uri[3])) {
        $givenWord = $uri[3];
    }


    $wordsController = new WordsController();
    if($wordsController->putWord($givenWord, $body["editedWord"]) == false){
        header("HTTP/1.1 404 Not Found");
    };
});


$router->delete('/visma-task/words', function ()  //word/Marius
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode('/', $uri);

    if ($uri[2] !== 'words') {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
    $givenWord= null;
    if (isset($uri[3])) {
        $givenWord = $uri[3];
    }
    $wordsController = new WordsController();
    $wordsController->deleteWord($givenWord);
});






$router->addNotFoundHandler(function (){
    echo 'not found';
});








if(empty($_SERVER['REQUEST_URI'])) {
    $runApp = new Application();
    $runApp->runApp();
}


$router ->run();