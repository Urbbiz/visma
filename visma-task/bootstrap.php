<?php

session_start();

//define('INSTALL_DIR', '/home/internalandriusurbonas/php/visma-pirmas/');
define('DIR', __DIR__ . '/');   //constants case sensitive


spl_autoload_register(
    function ($class) {
        $rootDir = __DIR__;
        $sourceDir = '//';

        $file = $rootDir.$sourceDir.str_replace('\\', '/', $class).'.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
);



//
//require DIR . 'Syllable/App/Application.php';
//require DIR . 'Syllable/PatternModel/PatternExtractorInterface.php';
//require DIR . 'Syllable/PatternModel/PatternCollection.php';
//require DIR . 'Syllable/PatternModel/PatternExtractor.php';
//require DIR . 'Syllable/IO/Input/UserInput.php';
//require DIR . 'Syllable/PatternModel/Pattern.php';
//require DIR . 'Syllable/Service/SyllableAlgorithmInterface.php';
//require DIR . 'Syllable/Service/SyllableAlgorithm.php';
//require DIR . 'Syllable/Service/SyllableResult.php';
//require DIR . 'Psr/Log/LoggerInterface.php';
//require DIR . 'Psr/Log/Logger.php';
//require DIR. 'Psr/Log/LogLevel.php';








