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


//        $db = new Database();
//        $db->connect();

        $databaseManager = new DatabaseManager();
//        $user->setPatternsStatement(2, "Apelsinas");   // idedam i duombaze nauja info
        $databaseManager->getAllPatterns();
//        $user->getPatternsWithCountCheck();
//        $user->getAllPatternsStatement(1,'.mis1');
//        $user->setPatternsStatementFromFile();
//
//        $databaseManager->setPatternsToDatabase(DIR."data/inputfile.txt");

//        echo "Ar gavom ka nors is duombazes?  ".$databaseManager->getAllPatterns()[0]."Atsakyk prasau....";


        $userInput = new UserInput;
        $SyllableAlgorithm = new SyllableAlgorithm();

        echo "Press: " . "\n";
        echo "1: Syllable SENTENCE" . "\n";
        echo "2: Syllable WORD" . "\n";
        echo "3: Syllable WORD using DATABASE" . "\n";
        echo "4: RESET  DATABASE" . "\n";
        $input = trim(fgets(STDIN, 1024));

        if ($input == 1) {
            echo "You chose to syllable SENTENCE" . "\n";

            $givenSentence = $userInput->getInputSentence();  // paduoda ivesta zodi
            $sentenceToWordArray = $userInput->getSentenceWordsInArray($givenSentence);

            $patternExtractor = new PatternExtractor();
            $patternsResult = $patternExtractor->getPatterns(DIR . "data/inputfile.txt"); // issitraukiam txt failo turini.
            $syllableSentence = '';
            foreach ($sentenceToWordArray as $word) {
                $syllableWord = $SyllableAlgorithm->syllable($word, $patternsResult);
                $syllableSentence .= $syllableWord->dashResult;
                echo $syllableWord->dashResult . " ";
            }
            exit(0);
        } elseif ($input == 2) {

//        $d=mktime();
//        echo "Created date is " . date("Y-m-d h:i:sa", $d);
            $logger = new Logger();
//        $logger->log(""," test message time of message:".date("Y-m-d h:i:sa", $d)."\n");


            // $userInput = new UserInput;
            $givenWord = $userInput->getInputWord();  // paduoda ivesta zodi

            $startTime = microtime(true); // laiko pradzia

            $patternExtractor = new PatternExtractor();
            $patternsResult = $patternExtractor->getPatterns(DIR . "data/inputfile.txt"); // issitraukiam txt failo turini.


            // $SyllableAlgorithm = new SyllableAlgorithm();
            $syllableResult = $SyllableAlgorithm->syllable($givenWord, $patternsResult);

            echo "Syllable result: " . $syllableResult->dashResult . "\n";   // parodo isskiemenuota zodi.

            // var_dump($syllableResult);


            $endTime = microtime(true); //laiko pabaiga
            $executionTime = round($endTime - $startTime, 4); // programos veikimo laikas suapvalintas iki 4 skaiciu po kablelio
            echo "Execution time: $executionTime seconds";

            $logger->info("Syllable method took{$executionTime} seconds, syllabed word; {$givenWord}.");


        }elseif ($input == 3){

$patternsCollection = $databaseManager->getAllPatterns();

if(count($patternsCollection->getPatterns())==0) {
    $databaseManager->setPatternsToDatabase(DIR . "data/inputfile.txt");
}

                $databaseManager->getAllPatterns();
            $givenWord = $userInput->getInputWord();  // paduoda ivesta zodi

            $wordInDatabase = $databaseManager->getWord($givenWord);

            if($wordInDatabase !=false){
//                Echo $wordInDatabase['syllableValue'];
                $result = new SyllableResult();
                $result->dashResult= $wordInDatabase['syllableValue'];
                $id = $wordInDatabase['id'];
                $result->matchedPatterns = $databaseManager->getRelatedPatterns($id);

                var_dump($result);

            }else {
                $patternsCollection = $databaseManager->getAllPatterns();
                $syllableResult = $SyllableAlgorithm->syllable($givenWord, $patternsCollection);
                $databaseManager->addWord($givenWord, $syllableResult->dashResult);
                $wordInDatabase = $databaseManager->getWord($givenWord);
                var_dump($wordInDatabase);
                $id = $wordInDatabase['id'];
                $patternIds= $databaseManager->getPatternIds($syllableResult->matchedPatterns);
                var_dump($patternIds);
                $databaseManager->addRelatedPatterns($id,$patternIds);
                var_dump($syllableResult);
            }


        }elseif ($input == 4){
            $databaseManager->deleteConnectionTableData();
            $databaseManager->deleteWordsTableData();
            $databaseManager->deletePatternsData();

        }
    }

}