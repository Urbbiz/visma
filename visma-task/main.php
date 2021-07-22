<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';
//require_once (__DIR__ .'/Syllable/Api/Router.php');


use Syllable\Api\Router;
use Syllable\Api\RoutesDefiner;
use Syllable\Controllers\WordsController;
use Syllable\Database\DatabaseManager;
use Syllable\Database\DatabaseManagerProxy;
use Syllable\Service\SyllableAlgorithm;


$router = new Router();
$databaseManager = new DatabaseManager();
$databaseManagerProxy = new DatabaseManagerProxy($databaseManager);
$syllableAlgorithm = new SyllableAlgorithm();
$wordsController = new WordsController($databaseManager, $databaseManagerProxy, $syllableAlgorithm);
$routesDefiner = new RoutesDefiner($router, $wordsController);

$routesDefiner->defineRoutes();








$router ->run();