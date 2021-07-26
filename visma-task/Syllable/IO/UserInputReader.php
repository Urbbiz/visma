<?php

namespace Syllable\IO;

class UserInputReader implements UserInputReaderInterface
{

    public function getInputWord(): string
    {
        echo "Please Enter the word you want to syllable", "\n";
        echo "Enter Word here: ";

        $givenWord = trim(fgets(STDIN, 1024));

        echo "The word you entered is: $givenWord" . "\n";

        return $givenWord;
    }

    public function getInputSentence(): string
    {
        echo "Please Enter the SENTENCE you want to syllable", "\n";
        echo "Enter SENTENCE here: ";

        $givenSentence = trim(fgets(STDIN, 1024));

        echo "The SENTENCE entered is: $givenSentence" . "\n";

        return $givenSentence;
    }

    public function getSentenceWordsInArray($givenSentence)
    {

        $sentenceWordArray = preg_split("/[^\w]*([\s]+[^\w]*)/", $givenSentence);
//var_dump($sentenceWordArray);
        return $sentenceWordArray;
    }
}
