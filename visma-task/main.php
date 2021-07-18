<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';
require_once (__DIR__ .'/Syllable/Api/Router.php');

use  Syllable\App\Application;
use  Syllable\Api\Router;




$router = new Router();

$router->get('/visma-task/', function () {
    echo 'Home page is here';
});

$router->get('/visma-task/about', function () {
    echo 'About page';

});

$router->addNotFoundHandler(function (){
    echo 'not found';
});

$router ->run();



echo "<h1>hello World!!!</h1>";


//if(empty($_SERVER['REQUEST_URI'])) {
//    $runApp = new Application();
//    $runApp->runApp();
//}
