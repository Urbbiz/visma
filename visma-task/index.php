<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';
require_once (__DIR__ .'/Syllable/Api/Router.php');
require_once (__DIR__.'/vendor/autoload.php');

use  Syllable\App\Application;
use  Syllable\Api\Router;



$runApp = new Application();
$runApp->runApp();