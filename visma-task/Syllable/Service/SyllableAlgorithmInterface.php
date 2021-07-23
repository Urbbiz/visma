<?php

namespace Syllable\Service;

use  Syllable\Service;
use Syllable\PatternModel\PatternCollection;

interface SyllableAlgorithmInterface
{
    public function syllable(string $givenWord, PatternCollection $patternResult): SyllableResult;
}
