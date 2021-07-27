<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';
require_once (__DIR__.'/vendor/autoload.php');
require_once (__DIR__ .'/Syllable/Api/Router.php');

//const DIR = __DIR__ . '/';   //constants case sensitive


use Syllable\Api\Router;
use Syllable\Api\RoutesDefiner;
use Syllable\App\Logger;
use Syllable\Controllers\WordsController;
use Syllable\Database\DatabaseManager;
use Syllable\Database\DatabaseManagerProxy;
use Syllable\IO\UserInputReader;
use Syllable\PatternModel\PatternExtractor;
use Syllable\Service\SyllableAlgorithm;


$router = new Router();
$databaseManager = new DatabaseManager();
$databaseManagerProxy = new DatabaseManagerProxy($databaseManager);
$userInputReader = new UserInputReader();
$patternExtractor = new PatternExtractor();
$logger = new Logger();
$syllableAlgorithm = new SyllableAlgorithm($databaseManager, $userInputReader, $patternExtractor,$logger);
$wordsController = new WordsController($databaseManager, $databaseManagerProxy, $syllableAlgorithm);
$routesDefiner = new RoutesDefiner($router, $wordsController);

$routesDefiner->defineRoutes();









$router ->run();