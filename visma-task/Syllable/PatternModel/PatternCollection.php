<?php

namespace Syllable\PatternModel;


class PatternCollection {

    private array $patterns=[];


    function addPattern(Pattern $pattern): void
    {
        $this->patterns[]= $pattern;
    }

    function getPatterns(): array
    {
        return $this->patterns;
    }
}

