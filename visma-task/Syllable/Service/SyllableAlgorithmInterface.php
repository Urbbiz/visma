<?php

namespace Syllable\Service;

use  Syllable\Service;
use Syllable\PatternModel\PatternCollection;

interface SyllableAlgorithmInterface
{
//    public function syllable(string $givenWord, PatternCollection $patternResult): SyllableResult;

    public function syllableUsingDataBase($givenWord): SyllableResult;

    public function syllableWord();

    public function syllableSentence();
}
