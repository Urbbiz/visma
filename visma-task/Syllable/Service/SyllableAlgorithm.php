<?php

namespace Syllable\Service;

use Syllable\Service;
use Syllable\IO\ExtractionValues;
use Syllable\PatternModel\PatternCollection;

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
}