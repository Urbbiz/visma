<?php

namespace Syllable\PatternModel;

use Syllable\PatternModel;


class Pattern
{

    function __construct($value) {
        $this->rawPattern = $value;
//        $this->patternNoNumbers = str_replace(['1','2','3','4','5','6','7','8','9','0'], "",$value );
        $this->patternNoNumbers = preg_replace("/[1-9]/","", $value);
    }
    private string $rawPattern;
    private string $patternNoNumbers;


    function  __toString(): string {
        return $this->rawPattern;

    }


    function getPatternWithoutNumbers():string
    {
        return $this->patternNoNumbers;
    }
}





