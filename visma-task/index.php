<?php
declare(strict_types=1);
//require __DIR__ . '/bootstrap.php';
require_once (__DIR__.'/vendor/autoload.php');
require_once (__DIR__ .'/Syllable/Api/Router.php');

const DIR = __DIR__ . '/';   //constants case sensitive


use  Syllable\App\Application;
use  Syllable\Api\Router;



$runApp = new Application();
$runApp->runApp();

