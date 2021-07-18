<?php

namespace Syllable\Service;

use  Syllable\Service;
use Syllable\PatternModel\PatternCollection;


interface SyllableAlgorithmInterface
{
function  syllable(string $givenWord, PatternCollection $patternResult): SyllableResult;
}