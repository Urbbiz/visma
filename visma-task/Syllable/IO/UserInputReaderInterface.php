<?php


namespace Syllable\IO;


interface UserInputReaderInterface
{
    public function getInputWord():string;


    public function getInputSentence():string;


    public function getSentenceWordsInArray($givenSentence);


}