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
use Syllable\IO\UserInput;



class Application
{


    public function runApp ()
    {

        $databaseManager = new DatabaseManager();
        $databaseManager->getAllPatterns();


        $userInput = new UserInput;
        $syllableAlgorithm = new SyllableAlgorithm();


        //Ateiciai perdaryti i switch funkcija vietoj ifelse
        echo "Press: " . "\n";
        echo "1: Syllable SENTENCE" . "\n";
        echo "2: Syllable WORD" . "\n";
        echo "3: Syllable WORD using DATABASE" . "\n";
        echo "4: RESET  DATABASE" . "\n";
        $input = trim(fgets(STDIN, 1024));

        if ($input == 1) {
            $syllableAlgorithm->syllableSentence();
        } elseif ($input == 2) {
            $syllableAlgorithm->syllableWord();
        }elseif ($input == 3){
            var_dump($syllableAlgorithm->syllableUsingDataBase($userInput->getInputWord()));
        }elseif ($input == 4){
            $databaseManager->deleteConnectionTableData();
            $databaseManager->deleteWordsTableData();
            $databaseManager->deletePatternsData();

        }
    }

}