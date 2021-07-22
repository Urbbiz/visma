<?php

namespace Syllable\Service;


use Syllable\Database\DatabaseManager;
use Syllable\IO\UserInput;
use Syllable\PatternModel\PatternExtractor;
use Syllable\Service;
use Syllable\IO\ExtractionValues;
use Syllable\PatternModel\PatternCollection;
use Syllable\App\Logger;

class SyllableAlgorithm implements SyllableAlgorithmInterface
{
    function  syllable(string $givenWord, PatternCollection $patternResult): SyllableResult
    {
        $givenWord =$this->addDots($givenWord);// uzdedam taskus is priekio ir galo duotam zodziui

        $syllableResult = new SyllableResult();

        $foundValues= [];
        foreach ($patternResult->getPatterns()as $pattern){  // einam per masyva be skaiciu

            $addedResult = false;
            $found=false;
            do {                          // ieskom skiemens givenWorde
                $offset = 0;
                if ($found != false) {    // jeigu randa atitikmeni

                    $offset = $found + 1;    // found pozicija kur rado, nustatom offset, kad ieskotu nuo toliau, negu rado
                    $snippet = $pattern->__toString();  // pasiimam is paduoto masyvo lygiai ta pati ka radom, tik su skaiciais.

                    if($addedResult == false){   // <---apsisaugojam nuo pasikartojanciu patternu.
                        $syllableResult->matchedPatterns[]= $snippet;
                        $addedResult = true;
                    }

                    $snippetIndex = 0;   // char indexas skiemenyje, kuri radom(mis3)

                    for($i= 0; $i < strlen($snippet); $i++ ){
                        $number = intval($snippet[$i]);  //tai yra pvz m raide ir bando , jeigu ne skaicius, grazins nuli, nes pas mus nera nulio.
                        if($number > 0 ){   // jeigu daugiau uz 0 , reiskia rado skaiciu (3)
                            $index = $snippetIndex + $found -1;  // inexas tai yra vieta po kurio irasinesim ta skaiciu , musu duotame zodyje.
                            if (!array_key_exists($index, $foundValues) || $foundValues[$index] < $number ){ // tikrinam ar jau buvo toks indexas tame masyve, jeigu buvo  tai irasom didesni, jeigu nebuvo, tai tiesiog ierasom nauja
                                $foundValues[$index] = $number;
                            }
                        }else {
                            $snippetIndex ++;   // didins kai tik atras raide, o ne skaiciu
                        }
                    }
                    // echo "positionInWord: ". $found .", value: " . $patternResult->RawPatterns[$key] . "\n";
                }
                $found = stripos($givenWord, $pattern->getPatternWithoutNumbers(), $offset);  // ieskom value duotam zodyje , nuo vietos kuria nurodo offset.

            }while($found != false);   // sukam cikla tol, kol randam zodyje kelis skiemenu atitikmenis
        }

        $syllableResult->dashResult = $this->numbersToDash($givenWord, $foundValues);
        $syllableResult->withNumbers = $this->insertNumbers($givenWord, $foundValues);
        return  $syllableResult;
    }



    // <--------pakeicia nelyginius skaicius i -   ir isveda galutini atsakyma-------->
    private function insertNumbers($givenWord, $foundValues)
    {
        $finalResult = "";
        for ($i=0; $i < strlen($givenWord) ; $i++) {
            $finalResult .=  $givenWord[$i];  // pridedam raide
            if(array_key_exists($i,$foundValues)) {
                $finalResult .=  $foundValues[$i];   // pridedam skaiciu
            }
        }
        return trim($finalResult,".");
    }


    private function numbersToDash($givenWord, $foundValues)
    {
        $finalResult = "";
        for ($i=0; $i < strlen($givenWord) ; $i++) {
            $finalResult .=  $givenWord[$i];  // pridedam raide
            if(array_key_exists($i,$foundValues ) && $foundValues[$i]% 2 ==1 ){
                $finalResult .=  "-";   // jeigu egzistuoja ir nelyginis pakeiciam i -
            }
        }

        return trim($finalResult,".");
    }


    // <--------prideda taskus prie duoto zodzio pradzioj ir gale-------->
    protected function addDots($givenWord)
    {
        $givenWord = ".".$givenWord.".";  // uzdedam taskus

        return $givenWord;
    }


    public function syllableUsingDataBase($givenWord):SyllableResult
    {
        $databaseManager = new DatabaseManager();
        $patternsCollection = $databaseManager->getAllPatterns();

        if(count($patternsCollection->getPatterns())==0) {
            $databaseManager->setPatternsToDatabase(DIR . "data/inputfile.txt");
        }

        $databaseManager->getAllPatterns();
        $wordInDatabase = $databaseManager->getWord($givenWord);

        if($wordInDatabase !==false){
            $result = new SyllableResult();
            $result->dashResult= $wordInDatabase['syllableValue'];
            $id = $wordInDatabase['id'];
            $result->matchedPatterns = $databaseManager->getRelatedPatterns($id);
            return $result;
        }else {
            $patternsCollection = $databaseManager->getAllPatterns();
            $syllableResult = $this->syllable($givenWord, $patternsCollection);
            $databaseManager->addWord($givenWord, $syllableResult->dashResult);
            $wordInDatabase = $databaseManager->getWord($givenWord);
            $id = $wordInDatabase['id'];
            $patternIds= $databaseManager->getPatternIds($syllableResult->matchedPatterns);
            $databaseManager->addRelatedPatterns($id,$patternIds);
            return $syllableResult;
        }
    }

    public  function syllableWord()
    {

//        $d=mktime();
//        echo "Created date is " . date("Y-m-d h:i:sa", $d);
//        $logger = new Logger();
        $logger = new Logger();
//        $logger->log(""," test message time of message:".date("Y-m-d h:i:sa", $d)."\n");


        // $userInput = new UserInput;
        $userInput= new UserInput();
        $givenWord = $userInput->getInputWord();  // paduoda ivesta zodi

        $startTime = microtime(true); // laiko pradzia

        $patternExtractor = new PatternExtractor();
        $patternsResult = $patternExtractor->getPatterns(DIR . "data/inputfile.txt"); // issitraukiam txt failo turini.


         $SyllableAlgorithm = new SyllableAlgorithm();
        $syllableResult = $this->syllable($givenWord, $patternsResult);

        echo "Syllable result: " . $syllableResult->dashResult . "\n";   // parodo isskiemenuota zodi.

        // var_dump($syllableResult);


        $endTime = microtime(true); //laiko pabaiga
        $executionTime = round($endTime - $startTime, 4); // programos veikimo laikas suapvalintas iki 4 skaiciu po kablelio
        echo "Execution time: $executionTime seconds";

        $logger->info("Syllable method took{$executionTime} seconds, syllabed word; {$givenWord}.");

    }

    public function  syllableSentence()
    {
        echo "You chose to syllable SENTENCE" . "\n";
        $userInput= new UserInput();
        $givenSentence = $userInput->getInputSentence();  // paduoda ivesta zodi
        $sentenceToWordArray = $userInput->getSentenceWordsInArray($givenSentence);

        $patternExtractor = new PatternExtractor();
        $patternsResult = $patternExtractor->getPatterns(DIR . "data/inputfile.txt"); // issitraukiam txt failo turini.
        $syllableSentence = '';
        foreach ($sentenceToWordArray as $word) {
            $syllableWord = $this-> syllable($word, $patternsResult);
            $syllableSentence .= $syllableWord->dashResult;
            echo $syllableWord->dashResult . " ";
        }
        exit(0);
    }
}