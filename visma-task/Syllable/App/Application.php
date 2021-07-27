<?php

namespace Syllable\App;

use PhpParser\Node\Stmt\Echo_;
use Syllable\Database\Database;
use Syllable\Database\DatabaseManager;
//use Psr\Log\Logger;
use Syllable\App;
use Syllable\PatternModel\Pattern;
use Syllable\Service\SyllableAlgorithm;
use Syllable\Service\SyllableResult;
use Syllable\PatternModel\PatternExtractor;
use Syllable\PatternModel\PatternCollection;
use Syllable\IO\UserInputReader;
use Syllable\App\Logger;

class Application
{
    public function runApp()
    {
        $databaseManager = new DatabaseManager();
        $patternExtractor = new PatternExtractor();
        $userInputReader = new UserInputReader();
        $logger = new Logger();
        $syllableAlgorithm = new SyllableAlgorithm($databaseManager, $userInputReader, $patternExtractor, $logger);

        echo "Press: " . "\n";
        echo "1: Syllable SENTENCE" . "\n";
        echo "2: Syllable WORD" . "\n";
        echo "3: Syllable WORD using DATABASE" . "\n";
        echo "4: RESET  DATABASE" . "\n";

        $input = trim(fgets(STDIN, 1024));

        switch ($input) {
            case 1:
                echo "You chose to syllable SENTENCE" . "\n";
//                $syllableAlgorithm->syllableWord();
                $syllableSentence = $syllableAlgorithm->syllableSentence();
//
                echo"Syllable result: : " . $syllableSentence;
                break;
            case 2:
                $syllableResult = $syllableAlgorithm->syllableWord();
                echo "Execution time: $syllableResult->processingDuration seconds ";
                echo "Syllable result: " . $syllableResult->dashResult . "\n";   // parodo isskiemenuota zodi.
                $logger->info("Syllable method took{$syllableResult->processingDuration} seconds");
                break;
            case 3:
                $syllableResult = $syllableAlgorithm->syllableUsingDataBase($userInputReader->getInputWord());
                echo "Syllable result: " . $syllableResult->dashResult . "\n";   // parodo isskiemenuota zodi.

                break;
            case 4:
                $databaseManager->deleteAllTablesData();
                break;
            case 5:
//                $patternCollection = new PatternCollection();
//                $patternCollection->addPattern(new Pattern('.An3'));
//                $patternCollection->addPattern(new Pattern('dri2us'));
//                $result = $syllableAlgorithm->syllable("Andrius", $patternCollection);
//                var_dump($result);
//                break;
        }
    }
}
