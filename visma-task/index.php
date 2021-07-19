<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';
require_once (__DIR__ .'/Syllable/Api/Router.php');

use  Syllable\App\Application;
use  Syllable\Api\Router;

//var_dump(function_exists('mysqli_connect'));
//mysql  Ver 8.0.25-0ubuntu0.20.04.1 for Linux on x86_64 ((Ubuntu))

//print_r(PDO::getAvailableDrivers());


//
//
//
//$router = new Router();
//
//$router->get('/visma-task/', function () {
//    echo 'DeleteData page is here';
//});
//
//$router->get('/visma-task/about', function () {
//    echo 'About page';
//
//});
//
//$router->addNotFoundHandler(function (){
//    echo 'not found';
//});
//
//$router ->run();
//
//
//
//echo "<h1>hello World!!!</h1>";


//if(empty($_SERVER['REQUEST_URI'])) {
//    $runApp = new Application();
//    $runApp->runApp();
//}

echo "<h1>Index page</h1>";

$runApp = new Application();
$runApp->runApp();