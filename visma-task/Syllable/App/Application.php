<?php
namespace Syllable\App;

use Syllable\Database\Database;
use Syllable\Database\DatabaseManager;
use Psr\Log\Logger;
use Syllable\App;
use Syllable\Service\SyllableAlgorithm;
use Syllable\Service\SyllableResult;
use Syllable\PatternModel\PatternExtractor;
use Syllable\PatternModel\PatternCollection;
use Syllable\IO\UserInputReader;

class Application
{
    public function runApp ()
    {
        $databaseManager = new DatabaseManager();
        $patternExtractor = new PatternExtractor();
        $userInputReader = new UserInputReader();
        $syllableAlgorithm = new SyllableAlgorithm($databaseManager, $userInputReader, $patternExtractor);

        echo "Press: " . "\n";
        echo "1: Syllable SENTENCE" . "\n";
        echo "2: Syllable WORD" . "\n";
        echo "3: Syllable WORD using DATABASE" . "\n";
        echo "4: RESET  DATABASE" . "\n";

        $input = trim(fgets(STDIN, 1024));

        switch ($input){
            case 1:
                $syllableAlgorithm->syllableSentence();
                break;
            case 2:
                $syllableAlgorithm->syllableWord();
                break;
            case 3:
                $syllableAlgorithm->syllableWord();
//                var_dump($syllableAlgorithm->syllableUsingDataBase($userInputReader->getInputWord()));
                break;
            case 4:
                $databaseManager->deleteAllTablesData();
                break;
        }

    }

}